<?php namespace VnSource\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMakeCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module package';


    protected $replaces;

    protected $files = [
        'category' => ['provider.category', 'model.category', 'model.post', 'repository.category', 'interface.repository.category', 'repository.post', 'interface.repository', 'request.category', 'request.post.store', 'request.post.update', 'controller.category', 'controller.backend.category', 'controller.backend.post', 'route.api.category', 'view.admin.category', 'view.list', 'view.detail'],
        'post' => ['provider.post', 'model.post', 'repository.post', 'interface.repository', 'request.post.store', 'request.post.update', 'controller.post', 'controller.backend.post', 'route.api', 'view.admin.post', 'view.detail'],
        'backend' => ['provider.backend', 'controller.backend', 'route.api', 'view.admin'],
        'backend.model' => ['provider.model', 'model', 'repository', 'interface.repository', 'request', 'controller.backend.model', 'route.api', 'view.admin'],
        'backend.frontend.model' => ['provider.model', 'model', 'repository', 'interface.repository', 'request', 'controller.backend.model', 'controller', 'route.api', 'route.web', 'view.admin', 'view.index'],
        'backend.frontend' => ['provider.backend', 'controller.backend', 'controller', 'route.api', 'route.web', 'view.admin', 'view.index'],
        'frontend' => ['provider', 'controller', 'route.web', 'view.index'],
        'frontend.model' => ['provider', 'model', 'controller', 'route.web', 'view.index'],
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->line(__('Welcome to the module Creator.'));

        // Get the name arguments and the age option from the input instance.
        $name = strtolower($this->argument('name'));

        if (!$this->laravel['files']->exists(base_path('vendor/'.$name))) {

            list($organization, $repository) = explode('/', $name);

            $uses = explode(',', str_replace(' ','', $this->option('use')));

            if (in_array('all', $uses)) {
                $uses = ['category','helper','config','lang'];
            } elseif (in_array('category', $uses)) {
                $uses = array_diff($uses, ['post','backend','frontend','model']);
            } elseif (in_array('post', $uses)) {
                $uses = array_diff($uses, ['backend','frontend','model']);
            }

            $namespaceDefault = str_replace(['/','-','_'],['\\','',''],ucwords($name,'/-_'));
            $displayNameDefault = ucwords(str_replace(['-','_'],' ', $repository));

            $attributes = [
                'name' => $name,
                'organization' => $organization,
                'repository' => $repository,
                'author' => []
            ];

            $attributes['namespace'] = $this->ask(__('Enter namespace'), $namespaceDefault);
            $attributes['displayName'] = $this->ask(__('Enter display name'), $displayNameDefault);
            $attributes['description'] = $this->ask(__('Enter description for module'), "The VnSource Module {$attributes['displayName']}");
            $attributes['homepage'] = $this->ask(__('Enter your homepage'), "https://github.com/{$name}");
            $attributes['license'] = $this->ask(__("Enter module's license"), 'MIT');
            $attributes['image'] = $this->ask(__('Enter image for module'), 'default');
            $attributes['author']['name'] = $this->ask(__("Enter author's name"), config('site.name'));
            $attributes['author']['email'] = $this->ask(__("Enter author's email"), config('site.email'));

            $this->createModule($attributes, $uses);

        } else {
            $this->comment(__('Module already exists!'));
        }
    }

    protected function createModule(array $attributes, $uses) {
        $moduleJson = [
            'name' => $attributes['name'],
            'displayName' => $attributes['displayName'],
            'type' => 'module',
            'description' => $attributes['description'],
            'homepage' => $attributes['homepage'],
            'image' => $attributes['image'],
            'license' => $attributes['license'],
            'version' => '1.0.0',
            'authors' => [$attributes['author']],
            'autoload' => [
                'files' => [],
                'psr-4' => [
                    $attributes['namespace'].'\\' => '/'
                ]
            ],
            'providers' => [
                $attributes['namespace'].'\\ModuleServiceProvider'
            ]
        ];
        if ($moduleJson['image'] == 'default') {
            unset($moduleJson['image']);
        }
        if (in_array('helper', $uses)) {
            array_push($moduleJson['autoload']['files'], 'helper.php');
        }

        if (save_json(base_path("vendor/{$attributes['name']}/module.json"), $moduleJson) !== false) {
            $this->setReplaces($attributes);
            $files = $this->getFiles($attributes['name']);

            $fileGroup = [];
            if (in_array('category', $uses)) {
                array_push($fileGroup, 'category');
            } elseif (in_array('post', $uses)) {
                array_push($fileGroup, 'post');
            } else {
                if (in_array('backend', $uses)) {
                    array_push($fileGroup, 'backend');
                }
                if (in_array('frontend', $uses)) {
                    array_push($fileGroup, 'frontend');
                }
                if (in_array('model', $uses)) {
                    array_push($fileGroup, 'model');
                }
            }
            $fileGroup = implode('.',$fileGroup);

            foreach ($this->files[$fileGroup] as $file) {
                $this->writeFile($file, $files[$file]);
            }

            if (in_array('helper', $uses)) {
                $this->writeFile('helper', $files['helper']);
            }

            if (in_array('config', $uses)) {
                $this->writeFile('config', $files['config']);
            }

            if (in_array('lang', $uses)) {
                $this->writeFile('lang', $files['lang']);
            }

            $modules = load_json(resource_path('modules.json'));

            $key = array_search($moduleJson['name'], array_column($modules, 'name'));
            if ($key !== false) {
                unset($this->modules[$key]);
            }
            $moduleJson['nameBase64'] = base64_encode($moduleJson['name']);
            $moduleJson['enabled'] = true;

            array_push($modules, $moduleJson);
            save_json(resource_path('modules.json'), $modules);

            $this->comment(__('Create module successfully.'));
        } else {
            $this->comment(__('Create module failed.'));
        }
    }

    protected function writeFile($stub, $path) {
        if (is_array($_path = $path)) {
            list($stub, $path) = $_path;
        }
        $content = $this->laravel['files']->get(__DIR__."/stubs/{$stub}.stub");
        $content = str_replace(array_keys($this->replaces), array_values($this->replaces), $content);

        $fullPath = base_path('vendor/'.$path);

        if (!$this->laravel['files']->exists($directory = $this->laravel['files']->dirname($fullPath))) {
            $this->laravel['files']->makeDirectory($directory, 0755, true);
        }

        $this->laravel['files']->put($fullPath, $content);
        $this->line('<info>Created file: </info>' . $path);
    }

    protected function getFiles($name) {
        return [
            'provider.category' =>  $name.'/ModuleServiceProvider.php',
            'provider.post' =>  $name.'/ModuleServiceProvider.php',
            'provider.backend' =>  $name.'/ModuleServiceProvider.php',
            'provider.model' =>  $name.'/ModuleServiceProvider.php',
            'provider' =>  $name.'/ModuleServiceProvider.php',

            'model.category' =>  $name.'/'.$this->replaces['DummyCategoryModel'].'.php',
            'model.post' =>  $name.'/'.$this->replaces['DummyModel'].'.php',
            'model' =>  $name.'/'.$this->replaces['DummyModel'].'.php',

            'repository.category' =>  $name.'/CategoryRepository.php',
            'repository.post' =>  $name.'/'.$this->replaces['DummyModel'].'Repository.php',
            'repository' =>  $name.'/'.$this->replaces['DummyModel'].'Repository.php',

            'interface.repository.category' =>  $name.'/CategoryRepositoryInterface.php',
            'interface.repository' =>  $name.'/'.$this->replaces['DummyModel'].'RepositoryInterface.php',

            'request.category' =>  $name.'/CategoryRequest.php',
            'request.post.store' =>  $name.'/StoreRequest.php',
            'request.post.update' =>  $name.'/UpdateRequest.php',
            'request' =>  $name.'/'.$this->replaces['DummyModel'].'Request.php',

            'controller.backend.category' =>  $name.'/Controllers/Backend/'.$this->replaces['DummyCategoryController'].'.php',
            'controller.backend.post' =>  $name.'/Controllers/Backend/'.$this->replaces['DummyController'].'.php',
            'controller.backend.model' =>  $name.'/Controllers/Backend/'.$this->replaces['DummyController'].'.php',
            'controller.backend' =>  $name.'/Controllers/Backend/'.$this->replaces['DummyController'].'.php',
            'controller.category' =>  $name.'/Controllers/'.$this->replaces['DummyController'].'.php',
            'controller.post' =>  $name.'/Controllers/'.$this->replaces['DummyController'].'.php',
            'controller' =>  $name.'/Controllers/'.$this->replaces['DummyController'].'.php',

            'route.api.category' =>  $name.'/Routes/api.php',
            'route.api' =>  $name.'/Routes/api.php',
            'route.web' =>  $name.'/Routes/web.php',

            'view.admin.category' =>  $name.'/Views/hook/admin.blade.php',
            'view.admin.post' =>  $name.'/Views/hook/admin.blade.php',
            'view.admin' =>  $name.'/Views/hook/admin.blade.php',
            'view.index' =>  $name.'/Views/index.blade.php',
            'view.list' =>  $name.'/Views/list.blade.php',
            'view.detail' =>  $name.'/Views/detail.blade.php',

            'helper' => $name.'/helpers.php',
            'config' => [
                'return',
                $name.'/config.php'
            ],
            'lang' => [
                'return',
                $name.'/Languages/'.$this->laravel['config']->get('app.locale').'.php'
            ],

        ];
    }

    protected function setReplaces(array $attributes) {
        $className = str_replace(['-','_'], '', ucwords($attributes['repository'],'-_'));
        $this->replaces = [
            'DummyNamespace' => $attributes['namespace'],
            'DummyModel' => $className,
            'DummyCategoryModel' => $className.'Category',
            'DummyCategoryController' => $className.'CategoryController',
            'DummyController' => $className.'Controller',
            'DummyDisplayName' => $attributes['displayName'],
            'DummyDescription' => $attributes['description'],
            'DummyRepo' => str_replace(['-','_'],'',$attributes['repository'])
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, __('Name of the new module')],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['use', 'u', InputOption::VALUE_REQUIRED, __('Use option: backend,frontend,model,post,category,lang,helper,config,all'), 'all']
        ];
    }
}