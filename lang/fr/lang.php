<?php

return [
    'controllers' => [
        'biblios' => [
            'label' => 'Biblios',
        ],
        'montages' => [
            'label' => 'Montages',
        ],
    ],
    'driver' => [
        'babyler' => [
            'description' => 'Permet de créer des montages photos',
            'label' => 'Créer un montage',
        ],
        'execute' => [
            'success' => [
                'message' => 'Lien crée',
            ],
        ],
    ],
    'menu' => [
        'biblios' => 'Bibliothèque Cloudinary',
        'biblios_desc' => 'Images et vidéo de la bibliothèque Cloudinary',
        'description' => 'Gestion des montages photos pour les models de l\'application',
        'montages' => 'Montages',
        'settings' => 'Options Images',
        'settings_description' => 'Réglage des valeurs par defaut ( images, couleurs, image manquante)',
    ],
    'models' => [
        'biblio' => [
            'e' => [
                'name' => 'Le nom est requis',
                'slug' => 'Le champs est requis et doit être unique',
            ],
            'name' => 'Nom de l&#039;image',
            'options' => 'Options',
            'slug' => 'code',
            'src' => 'Image',
            'srcv' => 'Vidéo',
            'type' => 'Type d&#039;image',
            'label' => 'Bibliothèque viméo',
            'load_options' => 'Options de chargement',
        ],
        'montage' => [
            'e' => [
                'data_source' => 'Vous devez choisir un modèle',
                'state' => 'Le champs état est obligatoire',
            ],
            'masque' => 'Masque ou autres sources (xl-masque-lx)',
            'memo' => 'Memo',
            'name' => 'Nom du montage',
            'slug' => 'Slug/Code',
            'src' => 'Image (xl-src-lx)',
            'tab_edit' => 'Editer',
            'test' => 'Tester un montage',
            'use_files' => 'Utilise des fichiers propres',
            'waka_session' => 'Clef pour LP',
            'label' => 'Montage',
        ],
    ],
    'montage' => [
        'name' => 'Nom du montage',
        'slug' => 'Slug',
    ],
    'settings' => [
        'cloudinary_path' => 'Chemin cloudinary',
        'logo' => 'Logo',
        'logo_com' => 'Cette image sera disponible dans word, excel, etc.',
        'police_1' => 'Police pour les montages',
        'police_1_com' => 'Code à utiliser : {nom_du_chemin}:police:police1',
        'police_2' => 'Police 2 pour les montages',
        'police_2_com' => 'Code à utiliser : {nom_du_chemin}:police:police2',
        'police_3' => 'Police 3 pour les montages',
        'police_3_com' => 'Code à utiliser : {nom_du_chemin}:police:police3',
        'unknown' => 'Image manquante',
        'unknown_com' => 'Image de remplacement pour les assets manquant',
    ],
    'blocks' => [
        'bibliocloudi' => [
            'name' => 'Média de la bibliothèque Cloudi',
            'description' => 'Affiche un média depuis cloudinary',
            'cloudi_code' => 'ID/Code du média',
            'auto_play' => 'Activer autoPlay',
        ],
    ],
];
