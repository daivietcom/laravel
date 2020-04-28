<?php namespace VnSource\Traits;

trait ModuleServiceProviderTrait {

    public $modulePath;
    public $moduleNameSpace;
    public $moduleName;

    public function initializationModule() {
        $reflector = new \ReflectionClass(get_class($this));
        $this->modulePath = dirname($reflector->getFileName());
        $this->moduleNameSpace = $reflector->getNamespaceName();
        $this->moduleName = str_replace([base_path('vendor\\'), base_path('vendor/'), '\\'],['', '', '/'],$this->modulePath);

        $this->app['vns']->registerModuleNameSpace($this->moduleName, $this->moduleNameSpace);

        //Config
         if($this->app['files']->exists($this->modulePath.'/config.php')) {
             $this->mergeConfigFrom(
                 $this->modulePath.'/config.php',
                 $this->moduleName
             );
         }

        //Language
        if($this->app['files']->isDirectory($this->modulePath.'/Languages')) {
            load_lang($this->moduleName);
        }

        //View
        if($this->app['files']->isDirectory($this->modulePath.'/Views')) {
            $this->loadViewsFrom($this->modulePath.'/Views', $this->isTheme()?'Frontend':$this->moduleNameSpace);
        }

        //Hook View
        if(isset($this->hookView)) {
            foreach ($this->hookView as $key => $value) {
                $this->app['config']->set('hooks.'.$key.'.'.str_random(6), $this->moduleNameSpace.'::'.$value);
            }
        }

        //Gadget Group
        if(isset($this->gadgetGroup)) {
            $this->app['gadget']->addGroup($this->gadgetGroup);
        }

        //Gadget
        if(isset($this->gadget)) {
            foreach ($this->gadget as $key => $value) {
                $value['module'] = $this->app['vns']->modules[$this->moduleName];
                $this->app['gadget']->register($key, $value);
            }
        }

        //Cast
        if(isset($this->castPost)) {
            foreach ($this->castPost as $key => $value) {
                $this->app['config']->set('site.cast.post.'.$key, $value);
            }
        }
        if(isset($this->castCategory)) {
            foreach ($this->castCategory as $key => $value) {
                $this->app['config']->set('site.cast.category.'.$key, $value);
            }
        }
        if(isset($this->castUri)) {
            foreach ($this->castUri as $key => $value) {
                $this->app['config']->set('site.uri.'.$key, $value);
            }
        }

        //Permissions
        if(isset($this->permissions)) {
            foreach ($this->permissions as $key => $value) {
                $this->app['config']->set('site.permissions.'.$key, $value);
            }
        }

        //Route
        if($this->app['files']->exists($this->modulePath.'/Routes/web.php')) {
            \Illuminate\Support\Facades\Route::group([
                'middleware' => 'web',
                'namespace' => $this->moduleNameSpace . '\\Controllers',
            ], function ($router) {
                require $this->modulePath.'/Routes/web.php';
            });
        }
        if($this->app['files']->exists($this->modulePath.'/Routes/api.php')) {
            \Illuminate\Support\Facades\Route::group([
                'middleware' => 'auth:api',
                'namespace' => $this->moduleNameSpace . '\\Controllers',
                'prefix' => 'api',
            ], function ($router) {
                require $this->modulePath.'/Routes/api.php';
            });
        }
    }

    protected function isTheme() {
        return isset($this->isTheme) && $this->isTheme;
    }
}
