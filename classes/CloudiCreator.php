<?php namespace Waka\Cloudis\Classes;

use Yaml;
use Waka\Cloudis\Models\Montage;
use Twig;

class CloudiCreator
{
    public $montage;
    public array $vars;
    public array $options;
    //
    private $ds;
    private $startData;
    //
    public function __construct($template, $vars, $options = [])
    {
        $this->montage = Montage::where('slug', $template)->first();
        $this->ds = $vars;
        $this->startData = [];
        $this->options = $options;
    }

    public function createLink()
    {
        $twigableOptions = $this->montage->options;

        $src = $this->montage->src->cloudiId;
        $masque = $this->montage->masque->cloudiId ?? null;

        //les utls sont encodés pour cloudi en : ici on va faire l'inverse, seul la src doit avoir des /
        $productorData = [
            'src' => str_replace(':', '/',$src),
            'masque' => str_replace('/', ':',$masque),
        ];

        $dataFormontages = array_merge(['wp' => $productorData], $this->ds);
        //trace_log($dataFormontages);
        $optionsParsed = Twig::parse($twigableOptions, $dataFormontages);
        $montageArray = Yaml::parse($optionsParsed);
        //trace_log($montageArray);
        $source = $montageArray['src'];
        $options = $montageArray['options'];

        //trace_log($source);
        //trace_log($options);

        return \Cloudder::secureShow($source, $options);

        
    }
}
