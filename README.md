# Cloudis
toDO : cloudiMaker pas terminé !

Permet d'interagir avec cloudinary

>**NOTE:** Ce plugin ne fonctionne qu'avec Winter.cms > v1.2.2 et les autres modules Waka

## Installation

Ce plugin est disponible via  [Composer](http://getcomposer.org/).

```bash
composer require winter/wn-blocks-plugin
```

### Autres librairises installés 
* "jrm2k6/cloudder": "dev-waka" ( il faut appeler le fork dans le main composer :  )
```php
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/charlesStOlive/cloudder"
        },
    ],
    "require": {
        "php": "^8.0.2",
        "winter/storm"
```
<!-- ### Autres composants installés
Composer va installer s'ils n'existent pas encore 
* wn-waka-productor
* wn-waka-utils -->

## Modèle disponible
### CloudiFile 
basé sur `\Waka\Cloudis\Models\Settings` ce modèle enregistre les images directement dans cloudinary. 
#### Les méthodes 
```php
public function getCloudiUrl($width = 400, $height = 400, $format = "auto", $crop = "fill", $otherOptions = [])
//Cette fonction peremet d'obtenir l'url. la variable . widdth $height $format et $cro applique une surtransformation sur les instructions $otherOptions qui attend les instructions cloudinary pour les transformations 

public function getVideoUrl($options)
//La variable $options attend les instructions de transformation

public function getColumnThumb($width = 75, $height = 30, $options = [])
//La variable $options attend les instructions de transformation

public function getUrl($options = [])
//La variable $options attend les instructions de transformation

public function deleteCloudi()
//supprime l'image cloudinary

public function getCloudiIdAttribute()
//donne l'id au format folder/id

public function getIdPathAttribute()
//donne l'id au format folder:id ( utile pour les montages complexes avec des layers)
```
### Montage
Un ensemble de méthodes et d'interfaces pour piloter des montages photos. 

### Biblio
Une mini bibliothèque qui permet de charger des docuements sur cloudinary pour les utiliser aillers ou dans des montages. Les formats possibles  : 
* image
* video 
* images ( une collection d'images )
* raw ( notamment pour charger des polices de caractères)

## Ajouts TWIG
### Filtres
* getCloudiUrl
``` 
{{ element | getCloudiUrl(120, 120, 'jpg', 'fill' )}} 
```
* getCloudiMontageUrl ( uniquement sur un objet CloudiFile )
 ``` 
{{ cloudiFileElement | getCloudiMontageUrl('slug_du_montage', 120,  120, 'jpg', 'fill' )}} 
```

### Fonctions 
* biblioVideo
* biblioImage



