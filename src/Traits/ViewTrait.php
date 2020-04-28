<?php namespace VnSource\Traits;

use View;

trait ViewTrait {

    protected $moduleViewPath;
    protected $moduleViewPackage;

    protected function baseView($view, $datas = []) {
        if (View::exists('Frontend::' . $view)) {
            $view = 'Frontend::' . $view;
        }
        return view($view, $datas);
    }

    protected function assetView($view, $directory, $datas = []) {
        $prefix = '';
        if ($directory != null) {
            $prefix = str_replace('/','.',$directory) . '.';
        }
        if (View::exists('Frontend::' . $prefix . $view)) {
            $view = 'Frontend::' . $prefix . $view;
        } else {
            if($prefix != null) {
                set_asset_path('themes/' . $directory);
            }
            $view = $prefix . $view;
        }
        return view($view, $datas);
    }

    protected function gadgetView($view, $datas = [], $suffix = null) {
        $this->initModuleView();
        if (View::exists('Frontend::modules.' . $this->moduleViewPath . '.gadgets.' . ($suffix==null?$view:$view.'_'.$suffix))) {
            $view =  'Frontend::modules.' . $this->moduleViewPath . '.gadgets.' . ($suffix==null?$view:$view.'_'.$suffix);
        } else {
            $view = $this->moduleViewPackage . '::' . $view;
        }
        return view($view, $datas);
    }

    protected function moduleView($view, $datas = [], $suffix = null) {
        $this->initModuleView();
        if (View::exists('Frontend::modules.' . $this->moduleViewPath . '.' . ($suffix==null?$view:$view.'_'.$suffix))) {
            $view =  'Frontend::modules.' . $this->moduleViewPath . '.' . ($suffix==null?$view:$view.'_'.$suffix);
        } else {
            $view = $this->moduleViewPackage . '::' . $view;
        }
        return view($view, $datas);
    }

    protected function initModuleView() {
        $reflector = new \ReflectionClass(get_class($this));
        $venderPaths = explode('/', str_replace([base_path('vendor\\'), base_path('vendor/'), '\\'],['', '', '/'],dirname($reflector->getFileName())));
        $this->moduleViewPath = $venderPaths[0].'/'.$venderPaths[1];
        $this->moduleViewPackage = app('vns')->getModuleNameSpace($this->moduleViewPath);
    }
}
