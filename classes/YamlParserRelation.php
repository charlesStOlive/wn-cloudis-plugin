<?php namespace Waka\Cloudis\Classes;

use Yaml;

class YamlParserRelation
{
    public $src;
    public $options;

    public function __construct($modelMontage, $dataFormontages)
    {
        //trace_log('yamlparser construct--');
        $twigableOptions = $modelMontage->options;
        //les utls sont encodés pour cloudi en : ici on va faire l'inverse, seul la src doit avoir des /
        $productorData = [
            'src' => str_replace(':', '/', $modelMontage->src->cloudiId),
            'masque' => $modelMontage->masque->cloudiId ?? null,
        ];

        $dataFormontages = array_merge(['wp' => $productorData], $dataFormontages);
        //trace_log($dataFormontages);
        $optionsParsed = \Twig::parse($twigableOptions, $dataFormontages);
        $montageArray = Yaml::parse($optionsParsed);
        //trace_log($montageArray);
        $this->src = $montageArray['src'];
        $this->options = $montageArray['options'];
    }
}
