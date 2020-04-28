<?php namespace Http\Controllers\Backend;

use Illuminate\Http\Request;
use File;
use Config;
use Storage;
use Models\Group;

class ConfigController extends \App\Http\Controllers\Controller
{

    public function jsonConfig() {
        load_lang('config');
        $json = [
        [
            'title' => __('General'),
            'icon' => 'fa fa-cogs',
            'data' => [
                'site.name' => [
                    'title' => __('Site name'),
                    'type' => 'text',
                    'value' => config('site.name')
                ],
                'site.title' => [
                    'title' => __('Site title'),
                    'type' => 'text',
                    'value' => config('site.title'),
                    'help' => __('Default title for website.')
                ],
                'site.description' => [
                    'title' => __('Description'),
                    'type' => 'textarea',
                    'value' => config('site.description'),
                    'help' => __('In a few words, explain what this site is about.')
                ],
                'site.email' => [
                    'title' => __('Email'),
                    'type' => 'text',
                    'value' => config('site.email')
                ],
                'site.per_page' => [
                    'title' => __('Posts per page'),
                    'type' => 'number',
                    'value' => (int)config('site.per_page')
                ],
                'site.maintenance' => [
                    'title' => __('Maintenance website'),
                    'type' => 'switch',
                    'value' => config('site.maintenance'),
                    'data' => [
                        [
                            'value' => true,
                            'text' => __('Close')
                        ],
                        [
                            'value' => false,
                            'text' => __('Open')
                        ]
                    ]
                ],
                'site.maintenance_notice' => [
                    'title' => __('Maintenance notice'),
                    'type' => 'ckeditor',
                    'value' => config('site.maintenance_notice')
                ]
            ]
        ],
        [
            'title' => __('Sendmail'),
            'icon' => 'fa fa-envelope',
            'data' => [
                'mail.driver' => [
                    'title' => __('Mail driver'),
                    'type' => 'select',
                    'value' => config('mail.driver'),
                    'data' => [
                        [
                            'id' => 'smtp',
                            'name' => 'SMTP'
                        ],
                        [
                            'id' => 'mail',
                            'name' => 'Mail'
                        ],
                        [
                            'id' => 'sendmail',
                            'name' => 'Sendmail'
                        ]
                    ]
                ],
                'mail.host' => [
                    'title' => __('SMTP host address'),
                    'type' => 'text',
                    'value' => config('mail.host')
                ],
                'mail.port' => [
                    'title' => __('SMTP host port'),
                    'type' => 'number',
                    'value' => (int)config('mail.port')
                ],
                'mail.encryption' => [
                    'title' => __('Mail Encryption Protocol'),
                    'type' => 'select',
                    'value' => config('mail.encryption'),
                    'data' => [
                        [
                            'id' => 'tls',
                            'name' => 'TLS'
                        ],
                        [
                            'id' => 'ssl',
                            'name' => 'SSL'
                        ]
                    ]
                ],
                'mail.from.address' => [
                    'title' => __('Email address'),
                    'type' => 'email',
                    'value' => config('mail.from.address')
                ],
                'mail.from.name' => [
                    'title' => __('Display name'),
                    'type' => 'text',
                    'value' => config('mail.from.name')
                ],
                'mail.username' => [
                    'title' => __('SMTP server username'),
                    'type' => 'text',
                    'value' => config('mail.username')
                ],
                'mail.password' => [
                    'title' => __('SMTP server password'),
                    'type' => 'password',
                    'value' => config('mail.password')
                ],
            ]
        ],
        [
            'title' => __('Authentication'),
            'icon' => 'fa fa-shield',
            'data' => [
                'site.user_register' => [
                    'title' => __('Open registration'),
                    'type' => 'switch',
                    'value' => config('site.user_register'),
                    'data' => [
                        [
                            'value' => true,
                            'text' => __('Enabled')
                        ],
                        [
                            'value' => false,
                            'text' => __('Disabled')
                        ]
                    ]
                ],
                'site.user_default_active' => [
                    'title' => __('Automatically activate users'),
                    'type' => 'switch',
                    'value' => config('site.user_default_active'),
                    'data' => [
                        [
                            'value' => true,
                            'text' => __('Enabled')
                        ],
                        [
                            'value' => false,
                            'text' => __('Disabled')
                        ]
                    ]
                ],
                'site.user_default_group' => [
                    'title' => __('Default group'),
                    'type' => 'select',
                    'value' => config('site.user_default_group'),
                    'data' => array_reduce(Group::all('code', 'name')->toArray(), function($all, $item) {
                        array_push($all, [
                            'id' => $item['code'],
                            'name' => __($item['name'])
                        ]);
                        return $all;
                    }, [])
                ],
                'site.password_length' => [
                    'title' => __('Minimum password length'),
                    'type' => 'number',
                    'value' => (int)config('site.password_length')
                ],
                'site.max_login_attempts_identity' => [
                    'title' => __('Maximum auth attempts'),
                    'type' => 'number',
                    'value' => (int)config('site.max_auth_attemptsy')
                ],
                'site.auth_attempts_expire' => [
                    'title' => __('Auth attempts expire'),
                    'type' => 'number',
                    'value' => (int)config('site.auth_attempts_expire')
                ],

                'services.facebook.enable' => [
                    'title' => __('Auth with Facebook'),
                    'type' => 'switch',
                    'value' => config('services.facebook.enable'),
                    'data' => [
                        [
                            'value' => true,
                            'text' => __('Enabled')
                        ],
                        [
                            'value' => false,
                            'text' => __('Disabled')
                        ]
                    ]
                ],
                'services.facebook.client_id' => [
                    'title' => __('Facebook client id'),
                    'type' => 'text',
                    'value' => config('services.facebook.client_id')
                ],
                'services.facebook.client_secret' => [
                    'title' => __('Facebook client secret'),
                    'type' => 'password',
                    'value' => config('services.facebook.client_secret')
                ],

                'services.google.enable' => [
                    'title' => __('Auth with Google'),
                    'type' => 'switch',
                    'value' => config('services.google.enable'),
                    'data' => [
                        [
                            'value' => true,
                            'text' => __('Enabled')
                        ],
                        [
                            'value' => false,
                            'text' => __('Disabled')
                        ]
                    ]
                ],
                'services.google.client_id' => [
                    'title' => __('Google client id'),
                    'type' => 'text',
                    'value' => config('services.google.client_id')
                ],
                'services.google.client_secret' => [
                    'title' => __('Google client secret'),
                    'type' => 'password',
                    'value' => config('services.google.client_secret')
                ],

                'services.twitter.enable' => [
                    'title' => __('Auth with Twitter'),
                    'type' => 'switch',
                    'value' => config('services.twitter.enable'),
                    'data' => [
                        [
                            'value' => true,
                            'text' => __('Enabled')
                        ],
                        [
                            'value' => false,
                            'text' => __('Disabled')
                        ]
                    ]
                ],
                'services.twitter.client_id' => [
                    'title' => __('Twitter client id'),
                    'type' => 'text',
                    'value' => config('services.twitter.client_id')
                ],
                'services.twitter.client_secret' => [
                    'title' => __('Twitter client secret'),
                    'type' => 'password',
                    'value' => config('services.twitter.client_secret')
                ],

                'services.linkedin.enable' => [
                    'title' => __('Auth with LinkedIn'),
                    'type' => 'switch',
                    'value' => config('services.linkedin.enable'),
                    'data' => [
                        [
                            'value' => true,
                            'text' => __('Enabled')
                        ],
                        [
                            'value' => false,
                            'text' => __('Disabled')
                        ]
                    ]
                ],
                'services.linkedin.client_id' => [
                    'title' => __('LinkedIn client id'),
                    'type' => 'text',
                    'value' => config('services.linkedin.client_id')
                ],
                'services.linkedin.client_secret' => [
                    'title' => __('LinkedIn client secret'),
                    'type' => 'password',
                    'value' => config('services.linkedin.client_secret')
                ],

                'services.github.enable' => [
                    'title' => __('Auth with GitHub'),
                    'type' => 'switch',
                    'value' => config('services.github.enable'),
                    'data' => [
                        [
                            'value' => true,
                            'text' => __('Enabled')
                        ],
                        [
                            'value' => false,
                            'text' => __('Disabled')
                        ]
                    ]
                ],
                'services.github.client_id' => [
                    'title' => __('GitHub client id'),
                    'type' => 'text',
                    'value' => config('services.github.client_id')
                ],
                'services.github.client_secret' => [
                    'title' => __('GitHub client secret'),
                    'type' => 'password',
                    'value' => config('services.github.client_secret')
                ],

                'services.bitbucket.enable' => [
                    'title' => __('Auth with Bitbucket'),
                    'type' => 'switch',
                    'value' => config('services.bitbucket.enable'),
                    'data' => [
                        [
                            'value' => true,
                            'text' => __('Enabled')
                        ],
                        [
                            'value' => false,
                            'text' => __('Disabled')
                        ]
                    ]
                ],
                'services.bitbucket.client_id' => [
                    'title' => __('Bitbucket client id'),
                    'type' => 'text',
                    'value' => config('services.bitbucket.client_id')
                ],
                'services.bitbucket.client_secret' => [
                    'title' => __('Bitbucket client secret'),
                    'type' => 'password',
                    'value' => config('services.bitbucket.client_secret')
                ]
            ]
        ],
        [
            'title' => __('Media'),
            'icon' => 'fa fa-picture-o',
            'data' => [
                'site.media.image' => [
                    'title' => __('Default image disk'),
                    'type' => 'select',
                    'value' => config('site.media.image'),
                    'data' => [
                        [
                            'id' => 'media',
                            'name' => __('Local')
                        ],
                        [
                            'id' => 's3',
                            'name' => __('Amazon S3')
                        ],
                        [
                            'id' => 'rackspace',
                            'name' => __('Rackspace')
                        ]
                    ]
                ],
                'site.media.video' => [
                    'title' => __('Default video disk'),
                    'type' => 'select',
                    'value' => config('site.media.video'),
                    'data' => [
                        [
                            'id' => 'media',
                            'name' => __('Local')
                        ],
                        [
                            'id' => 's3',
                            'name' => __('Amazon S3')
                        ],
                        [
                            'id' => 'rackspace',
                            'name' => __('Rackspace')
                        ]
                    ]
                ],
                'site.media.other' => [
                    'title' => __('Default other disk'),
                    'type' => 'select',
                    'value' => config('site.media.other'),
                    'data' => [
                        [
                            'id' => 'media',
                            'name' => __('Local')
                        ],
                        [
                            'id' => 's3',
                            'name' => __('Amazon S3')
                        ],
                        [
                            'id' => 'rackspace',
                            'name' => __('Rackspace')
                        ]
                    ]
                ],
                'site.media.mimes' => [
                    'title' => __('MIME types allowed'),
                    'type' => 'text',
                    'value' => config('site.media.mimes')
                ],

                'filesystems.disks.s3.key' => [
                    'title' => __('Amazon S3 key'),
                    'type' => 'text',
                    'value' => config('filesystems.disks.s3.key')
                ],
                'filesystems.disks.s3.secret' => [
                    'title' => __('Amazon S3 secret'),
                    'type' => 'password',
                    'value' => config('filesystems.disks.s3.secret')
                ],
                'filesystems.disks.s3.region' => [
                    'title' => __('Amazon S3 region'),
                    'type' => 'text',
                    'value' => config('filesystems.disks.s3.region')
                ],
                'filesystems.disks.s3.bucket' => [
                    'title' => __('Amazon S3 bucket'),
                    'type' => 'text',
                    'value' => config('filesystems.disks.s3.bucket')
                ],

                'filesystems.disks.rackspace.username' => [
                    'title' => __('Rackspace username'),
                    'type' => 'text',
                    'value' => config('filesystems.disks.rackspace.username')
                ],
                'filesystems.disks.rackspace.key' => [
                    'title' => __('Rackspace key'),
                    'type' => 'password',
                    'value' => config('filesystems.disks.rackspace.key')
                ],
                'filesystems.disks.rackspace.container' => [
                    'title' => __('Rackspace container'),
                    'type' => 'text',
                    'value' => config('filesystems.disks.rackspace.container')
                ],
                'filesystems.disks.rackspace.endpoint' => [
                    'title' => __('Rackspace endpoint'),
                    'type' => 'text',
                    'value' => config('filesystems.disks.rackspace.endpoint')
                ],
                'filesystems.disks.rackspace.region' => [
                    'title' => __('Rackspace region'),
                    'type' => 'text',
                    'value' => config('filesystems.disks.rackspace.region')
                ],
                'filesystems.disks.rackspace.url_type' => [
                    'title' => __('Rackspace url_type'),
                    'type' => 'text',
                    'value' => config('filesystems.disks.rackspace.url_type')
                ],
            ]
        ]];
        return response()->json($json);
    }

    public function postConfig(Request $request)
    {
        $json = array(
            'message' => __('Unknow error'),
            'success' => FALSE
        );

        $customs = require config_path('custom.php');
        $configs = $request->all();
        unset($configs['api_token']);

        foreach ($configs as $key => $value) {
            $config = config($key);
            $type = gettype($config);
            if ($type != 'NULL') {
                settype($value, gettype($config));
            }
            if($config != $value) {
                $customs[$key] = $value;
            }
        }
        if(File::put(config_path('custom.php'),"<?php\r\nreturn ". var_export($customs, true) .';') !== false) {
            $json['success'] = true;
        }

        return response()->json($json);
    }
}
