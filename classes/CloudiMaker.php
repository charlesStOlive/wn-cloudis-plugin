<?php namespace Waka\Cloudis\Classes;

use Yaml;
use Closure;
use Lang; 
use \Waka\Productor\Classes\Abstracts\BaseProductor;

class CloudiMaker extends BaseProductor
{
    public $src;
    public $options;

    public static $config = [
        'label' => 'waka.cloudis::lang.driver.babyler.label',
        'icon' => 'icon-mjml',
        'description' => 'waka.cloudis::lang.driver.babyler.description',
        'productorModel' => \Waka\Cloudis\Models\Montage::class,
        'productorCreator' => \Waka\Cloudis\Classes\CloudiCreator::class,
        'productor_yaml_config' => '~/plugins/waka/cloudis/models/montage/productor_config.yaml',
        'methods' => [
            'createLink' => [
                'label' => 'Creer un lien',
                'handler' => 'createLink',
            ],
        ],
    ];

    public static function execute($templateCode, $productorHandler, $allDatas):array {
        //trace_log($allDatas);
        $modelId = Arr::get($allDatas, 'modelId');
        $modelClass = Arr::get($allDatas, 'modelClass');
        $creationMode = Arr::get($allDatas, 'creation_mode');
        $dsMap = Arr::get($allDatas, 'dsMap', null);
        //trace_log('$allDatas!!!',$allDatas);
        //
        $targetModel = $modelClass::find($modelId);
        $morphName = null;
        $targetId = null;
        $unique = false;
        $data = [];
        //
        if($creationMode == 'unique') {
            $unique = true;
        }
        $ModifiedDsMap =   Arr::get($allDatas, 'productorDataArray.change_dsMap', $dsMap);
        if ($targetModel) {
            $morphName = $targetModel->getMorphClass();
            $targetId =  $targetModel->id;
            $data = $targetModel->dsMap($ModifiedDsMap);
        } else {
            //trace_log('je n ai pas de $targetModel');
        }
        $dataForScene =   Arr::get($allDatas, 'productorDataArray.model_data_before', '');
        $dataForScene =  \Yaml::parse(\Twig::parse($dataForScene, $data));
        if(!$dataForScene) $dataForScene = [];
        $options = [
                'modelId' => $modelId,
                'class' => $modelClass,
                'scene_slug' => Arr::get($allDatas, 'productorDataArray.scene_slug', null),
                'unique' => $unique,
                'morphName' => $morphName,
                'targetId' =>$targetId,
            
        ];
        //trace_log('data!', $data);
        if($productorHandler == "createLink") {
            $link = self::createLink($templateCode, $data, $options);
            return [
                'message' => 'Lien crée',
                'btn' => [
                    'label' => 'Ouvrir',
                    'request' => 'onOpenLink',
                    'link' => $link
                ],
            ];
        }
    }

    public static function updateFormwidget($slug, $formWidget, $dsMap = null)
    {
        $productorModel = self::getProductor($slug);
        $formWidget->getField('model_data_before')->value = $productorModel->default_data;
        $formWidget->getField('change_dsMap')->value = $dsMap;
        //Je n'ais pas trouvé de solution pour charger les valeurs. donc je recupère les asks dans un primer temps avec une valeur par defaut qui ne marche pas et je le réajoute ensuite.... 
        // $formWidget = self::getAndSetAsks($productorModel, $formWidget);
        return $formWidget;
    }


    public static function createLink(string $templateCode, array $vars, array $options, Closure $callback = null)
    {
        // Créer l'instance de pdf
        $creator = self::instanciateCreator($templateCode, $vars, $options);
        // Appeler le callback pour définir les options
        if (is_callable($callback)) {
            $callback($creator);
        }

        try {
            return $creator->createLink();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
