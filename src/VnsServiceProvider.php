<?php namespace VnSource;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class VnsServiceProvider extends ServiceProvider
{
    protected $loader;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->loader = require base_path('vendor/autoload.php');
    }
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    public function boot()
    {

        if($this->app['files']->exists(config_path('custom.php'))) {

            Blade::extend(function ($value) {
                return preg_replace('/\@navbarcpanel\(([^\)]+)\)/', '<?php navbar_cpanel(${1}); ?>', $value);
            });

            if (empty($this->app['config']->get('site.theme'))) {
                $this->loadViewsFrom(resource_path('views/frontend'), 'Frontend');
                $this->app['gadget']->addGroup([
                    'menu' => 'Menu area',
                    'home.top' => 'Homepage top area'
                ]);
            }

            $this->app['gadget']->addGroup([
                'left' => 'Left sidebar area',
                'right' => 'Right sidebar area',
                'comment' => 'Comment area'
            ]);

            \Carbon\Carbon::setLocale($this->app['config']->get('app.locale'));

        } else {
            $this->addPublishes();
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $customs = (array)$this->app['config']->get('custom');
        $this->app['config']->set('custom', []);
        foreach ($customs as $key => $val) {
            $this->app['config']->set($key, (ends_with($key,'.url')||ends_with($key,'.redirect'))?url($val):$val);
        }

        unset($customs);

        $this->app->singleton('translator', function($app){
          return new \VnSource\Services\Translation($app);
        });

        $this->app->singleton('gadget', function ($app) {
            $blade = $app['view']->getEngineResolver()->resolve('blade')->getCompiler();
            return new \VnSource\Services\Gadget($blade, $app);
        });

        $this->app->singleton('avatar', function ($app) {
            return new \VnSource\Services\Avatar($app);
        });

        $this->app->singleton('agent', function ($app) {
            return new \VnSource\Services\Agent($app);
        });

        $this->app->singleton('vns', function ($app) {
            return new \VnSource\Services\VnS($app);
        });

        $this->app->singleton(\Repositories\Module\ModuleRepositoryInterface::class, function($app){
            return new \Repositories\Module\ModuleRepository($app);
        });
        $this->app->singleton(
            \Repositories\User\UserRepositoryInterface::class,
            \Repositories\User\UserRepository::class
        );
        $this->app->singleton(
            \Repositories\Group\GroupRepositoryInterface::class,
            \Repositories\Group\GroupRepository::class
        );
        $this->app->singleton(
            \Repositories\Post\PostRepositoryInterface::class,
            \Repositories\Post\PostRepository::class
        );
        $this->app->singleton(
            \Repositories\Term\TermRepositoryInterface::class,
            \Repositories\Term\TermRepository::class
        );

        $this->registerModule();

        if ($this->app->runningInConsole()) {
            $this->commands([
                \VnSource\Console\ModuleMakeCommand::class
            ]);
        }
    }

    protected function registerModule()
    {
        $modules = load_json(resource_path('modules.json'), true);
        $defaultTheme = $this->app['config']->get('site.theme');
        foreach ($modules as $module) {
            if ($module['type'] == 'module' && $module['enabled'] == true) {
                $this->initModule($module);
            } elseif ($module['name'] == $defaultTheme) {
                $this->initModule($module);
            }
        }
    }

    protected function initModule($module) {
        $this->app['vns']->registerModuleInfo($module);
        if (!empty($module['autoload']['files'])) {
            foreach ($module['autoload']['files'] as $file) {
                if ($this->app['files']->exists(base_path('vendor/'.$module['name'].'/'.$file))) {
                    require base_path('vendor/'.$module['name'].'/'.$file);
                }
            }
        }
        if (!empty($module['autoload']['psr-4'])) {
            foreach ($module['autoload']['psr-4'] as $namepace => $path) {
                $this->loader->setPsr4($namepace, base_path('vendor/'.$module['name'].'/'.$path));
            }
        }
        if (!empty($module['providers'])) {
            foreach ($module['providers'] as $provider) {
                $this->app->register($provider);
            }
        }
    }

    protected function addPublishes() {
        $this->publishes([
            __DIR__.'/../publishes/config' => config_path(),
            __DIR__.'/../publishes/resources' => resource_path(),
            __DIR__.'/../publishes/database' => database_path(),
            __DIR__.'/../publishes/app' => app_path(),
            __DIR__.'/../publishes/routes' => base_path('routes'),
            __DIR__.'/../publishes/public' => public_path()
        ]);
    }

   public function provides()
   {
       return ['vns','translator','gadget', 'avatar', 'agent'];
   }
}
