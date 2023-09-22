<?php

namespace Waka\Cloudis\Classes\Traits;

use Waka\Cloudis\Classes\YamlParserRelation;
use \Waka\Cloudis\Models\Settings as CloudisSettings;

trait CloudiTrait
{
    /*
     * Constructor
     */
    public static function bootCloudiTrait()
    {
        static::extend(function ($model) {
            /*
             * Define relationships
             */
            $model->morphToMany['montages'] = [
                'Waka\Cloudis\Models\Montage',
                'name' => 'montageable',
                'table' => 'waka_cloudis_montageables',
                'pivot' => ['errors'],
                'delete' => true,
            ];
            $model->bindEvent('model.beforeDelete', function () use ($model) {
                $model->clouderDeleteAll(false);
            });
        });
    }

    /**
     * Retourne les imageCloudi de ce modèle.
     */
    public function getCloudiKeys($strict = true)
    {
        $cloudiKeys = [];
        $attachOnes = $this->attachOne;

        //trace_log($this->attachOne);

        foreach ($attachOnes as $key => $value) {
            if(is_array($value)) {
                $value = $value[0];
            }
            if ($strict) {
                if ($value == 'Waka\Cloudis\Models\CloudiFile' && $this->{$key}) {
                    array_push($cloudiKeys, $this->{$key});
                }
            } else {
                if ($value == 'Waka\Cloudis\Models\CloudiFile') {
                    array_push($cloudiKeys, $this->{$key});
                }
            }
        }
        $attachMany = $this->attachMany;
        foreach($attachMany as $key => $value) {
            if(is_array($value)) {
                $value = $value[0];
            }
            if ($strict) {
                if ($value == 'Waka\Cloudis\Models\CloudiFile' && $this->{$key}) {
                    foreach($this->{$key} as $index => $elem) {
                        array_push($cloudiKeys, $elem);
                    }
                    
                }
            } else {
                if ($value == 'Waka\Cloudis\Models\CloudiFile') {
                    foreach($this->{$key} as $index => $elem) {
                        array_push($cloudiKeys, $elem);
                    }
                }
            }
            
        }
        return $cloudiKeys;
    }

    /**
     *
     */
    // public function getMontage($modelMontage, $opt = null)
    // {
    //     $model = $this;
    //     $parser = new YamlParserRelation($modelMontage, $model);
    //     $options = $parser->options;
    //     if (!$parser->options) {
    //         return null;
    //     }
    //     if ($opt) {
    //         array_push($options['transformation'], $opt);
    //     }
    //     if(!$parser->src) {
    //         //Si la source n'est pas trouvé
    //        $parser->src = $this->getErrorImage();
    //     }
    //     $url = \Cloudder::secureShow($parser->src, $options);

    //     return $url;
    // }
    

    public function dsGetCloudiMontage($key, $field, $opt)
    {
        //trace_log('dsGetCloudiMontage--------------------------');

        $key = $this->dsGetValueFrom($key, $field, $opt);
        //
        $basicFinalOptions = [
            'crop' => 'fit',
            'height' => 500,
            'width' => 500,
        ];
        $slug = $field['params']['slug'] ?? 500;
        $finalOptions = $field['params']['options'] ?? [];
        $montage = \Waka\Cloudis\Models\Montage::where('slug', $slug)->first();
        $dataFormontages = $this->dsMap('forCloudi');
        $parser = new YamlParserRelation($montage, $dataFormontages);
        $options = $parser->options;
        $finalOptions = array_merge($basicFinalOptions, $finalOptions);
        array_push($options['transformation'], $finalOptions);
        return [
            'path' => \Cloudder::secureShow($parser->src, $options),
            'width' => $finalOptions['width'],
            'height' => $finalOptions['height'],
        ];
    }

    public function dscloudiColor($key, $field, $opt)
    {
        $key = $this->dsGetValueFrom($key, $field, $opt);
        return ltrim($this->{$key}, '#');
    }

    public function dsGetCloudiUrl($key, $field, $opt)
    {
        $key = $this->dsGetValueFrom($key, $field, $opt);
        $width = $field['params']['width'] ?? null;
        $height = $field['params']['height'] ?? null;
        $format = $field['params']['format'] ?? null;
        $crop = $field['params']['crop'] ?? null;
        return [
            'path' => $this->{$key}->getCloudiUrl($width, $height, $format, $crop),
            'width' => $width,
            'height' => $height,
        ];
    }

    public function dscloudiId($key, $field, $opt)
    {
        $key = $this->dsGetValueFrom($key, $field, $opt);
        $value = $this->{$key}->cloudiId;
        return str_replace('/', ':', $value);
    }

    public function getcloudiKey($key)
    {
        return  $this->{$key}->cloudiId;
    }

    public function dsConfigMontage()
    {
        return
            [
                'slug' => [
                    'label' => 'Entrez le code de l image',
                ],
                'params' => [
                    'label' => 'Paramètres de l\'image',
                    'type' => 'nestedform',
                    'form' => [
                        'fields' => [
                            'width' => [
                                'label' => 'width',
                                'span' => 'left',
                            ],
                            'height' => [
                                'label' => 'width',
                                'span' => 'right',
                            ],
                            'crop' => [
                                'label' => 'crop-mode',
                            ]   
                        ],
                    ],
                ]

            ];
    }

    /**
     *
     */
    public function getErrorImage()
    {
        $cloudiSettings = CloudisSettings::instance();
        return $cloudiSettings->unknown->cloudiId;
    }

    public function getUrlErrorImage()
    {
        $cloudiSettings = CloudisSettings::instance();
        return $cloudiSettings->unknown->getUrl();
    }

    /**
     * Supprime tout les cloudis
     */
    public function clouderDeleteAll()
    {
        $imgs = $this->getCloudiKeys();
        foreach ($imgs as $img) {
            //trace_log($img->disk_name);
        }
        if(!empty($imgs)) {
            // throw new \ApplicationException('to check');
        } else {
            // trace_log('pas de cloudi ici');
        }
        

        foreach ($imgs as $img) {
            $img->deleteCloudi();
        }
    }
}
