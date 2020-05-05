<?php

return [
    'ssl' => FALSE,
    'big_site' => FALSE,
    'per_sitemap' => 20000,
    'user_register' => TRUE,
    'user_default_active' => TRUE,
    'user_default_group' => 'member',
    'password_length' => 6,
    'max_auth_attempts' => 5,
    'auth_attempts_expire' => 1,
    'name' => 'Open source VnSource',
    'title' => 'Open source VnSource for Laravel',
    'description' => 'Modules package for the Laravel 5 framework.',
    'image' => '/images/default-image.jpg',
    'email' => 'demo.vns@gmail.com',
    'maintenance' => FALSE,
    'maintenance_notice' => '<div class="title">Website đang bảo tr&igrave;.<br />Ch&uacute;ng t&ocirc;i sẽ quay lại ngay!</div>',
    'theme' => '',
    'per_page' => 30,
    'admin_path' => 'admin123',
    'cryptKey' => 'VnSource 2017',
    'cryptIV ' => 'Author By Mr.VnS',
    'cache' => [0,0],
    'cast' => [
        'post' => [],
        'category' => []
    ],
    'uri' => [
        'post' => '{slug}-{id}',
        'category' => 'category/{slug}-{id}'
    ],
    'permissions' => [
        'dashboard' => 'View dashboard',
        'config' => 'Site config',
        'module' => 'Module management',
        'theme' => 'Theme management',
        'user' => 'User management',
        'group' => 'Group management',
        'tag' => 'Tag management',
        'comment' => 'Comment management',
        'contact' => 'Contact management',
        'media' => 'Media management',
        'media.view' => 'Media view',
        'media.upload' => 'Media upload',
        'media.delete' => 'Media delete',
        'media.folder' => 'Create folder',
        'media.rename' => 'Media rename',
        'statistic' => 'Statistics',
        'statistic.real-time' => 'Statistics real time',
        'charge' => 'Charge management',
        'charge.history' => 'Charge history',
        'charge.statistic' => 'Charge statistic',
        'charge.config' => 'Charge config',
    ],
    'media' => [
        'image' => 'media',
        'video' => 'media',
        'other' => 'media',
        'mimes' => 'jpeg,bmp,png,ico,bin,exe,h264,mp4,mpeg,flv,avi,oga,wma,mp3,aac,wav,xls,doc,chm,pdf,txt'
    ]
];
