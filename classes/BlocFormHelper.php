<?php

namespace Waka\Cloudis\Classes;



/**
 * The Block class.
 */
class BlocFormHelper 
{

    public static function getBiblioVideoCloudiOptions($formWidget, $formField) {
        return  \Waka\Cloudis\Models\Biblio::where('type', 'video')->pluck('name', 'slug')->toArray();
    }

}