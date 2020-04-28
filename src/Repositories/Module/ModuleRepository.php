<?php namespace Repositories\Module;

class ModuleRepository implements ModuleRepositoryInterface
{
    protected $modules;
    protected $defaultTheme;
    protected $jsonPath;
    protected $app;
    protected $cacheTime = 24*60;

    public function __construct($app) {
        $this->app = $app;
        $this->jsonPath = resource_path('modules.json');
        $this->modules = load_json($this->jsonPath);
        $this->defaultTheme = config('site.theme');
    }

    public function getAll($type = null) {

        $modules = array_reduce($this->modules, function($all, $module) use ($type) {
            if (empty($type) || $module['type'] == $type) {
                list($organization, $repository) = explode('/', strtolower($module['name']));
                $info = $this->info($organization, $repository);
                if (!empty($info) && version_compare($info['version'], $module['version'], '>')) {
                    $module['version_new'] = $info['version'];
                }
                array_push($all, $this->format($module));
            }
            return $all;
        }, []);

        return $modules;
    }

    public function update($name, array $attributes) {
        if (is_base64($name)) {
            $name = base64_decode($name);
        }
        $key = array_search($name, array_column($this->modules, 'name'));
        if (isset($attributes['toggleStatus'])) {
            $attributes = [
                'enabled' => (bool)$attributes['enabled']
            ];
        }
        $this->modules[$key] = array_merge($this->modules[$key], $attributes);
        if (save_json($this->jsonPath, $this->modules) !== false) {
            return $this->format($this->modules[$key]);
        }
        return false;
    }

    public function check($github) {
        list($organization, $repository) = explode('/', str_replace('https://github.com/','', strtolower(rtrim($github, '/'))));
        $info = $this->info($organization, $repository);
        if (($key = array_search("{$organization}/{$repository}", array_column($this->modules, 'name'))) !== false) {
            $info['installed'] = $this->modules[$key]['version'];
            $info['update'] = version_compare($info['version'], $info['installed'], '>');
        }
        return $this->format($info);
    }

    public function download($name) {
        if (is_base64($name)) {
            $name = base64_decode($name);
        }
        $name = strtolower($name);
        list($organization, $repository) = explode('/', $name);
        $info = $this->info($organization, $repository);
        if (empty($info)) {
            return false;
        }
        $zipPath = "modules/{$organization}_{$repository}.{$info['version']}.zip";
        if (!$this->app['files']->exists(storage_path('app/'.$zipPath))) {
            $content = curl_get($info['zip_url']);
            if (empty($content)) {
                return false;
            }
            $this->app['filesystem']->put(
                $zipPath,
                $content
            );
        }
        return true;
    }

    public function unpack($name, $version) {
        if (is_base64($name)) {
            $name = base64_decode($name);
        }
        $name = strtolower($name);
        list($organization, $repository) = explode('/', $name);
        $zipPath = storage_path("app/modules/{$organization}_{$repository}.{$version}.zip");
        $vendorPath = base_path('vendor/'.$name);
        if ($this->app['files']->exists($zipPath)) {
            if ($this->app['files']->exists($vendorPath)) {
                $info = load_json($vendorPath.'/module.json');
                if (!empty($info)) {
                    if ($this->app['files']->exists($publicPath = public_path($name))) {
                        $this->app['files']->copyDirectory($publicPath, $vendorPath.'/public');
                        $this->app['files']->deleteDirectory($publicPath);
                    }
                    $this->app['zipper']->make(storage_path("app/modules/backup/{$organization}_{$repository}.{$info['version']}.zip"))->add($vendorPath)->close();
                    $this->app['files']->deleteDirectory($vendorPath);
                }
            }
            $this->app['zipper']->make($zipPath)->folder($repository.'-master')->extractTo($vendorPath);
            $info = load_json($vendorPath.'/module.json');
            return $this->format($info);
        }
        return false;
    }

    public function store($name) {
        if (is_base64($name)) {
            $name = base64_decode($name);
        }
        $name = strtolower($name);
        $info = load_json(base_path("vendor/{$name}/module.json"));
        if (!empty($info)) {
            $key = array_search($info['name'], array_column($this->modules, 'name'));
            if ($key !== false) {
                unset($this->modules[$key]);
            }
            $info['nameBase64'] = base64_encode($info['name']);
            if ($info['type'] == 'module') {
                $info['enabled'] = false;
            }
            array_push($this->modules, $info);
            if (save_json($this->jsonPath, $this->modules) !== false) {
                if ($this->app['files']->exists($vendorPath = base_path("vendor/{$name}/public"))) {
                    $this->app['files']->copyDirectory($vendorPath, public_path($name));
                }
                return $this->format($info);
            }
        }
        return false;
    }

    public function delete($name)
    {
        if (is_base64($name)) {
            $name = base64_decode($name);
        }
        $name = strtolower($name);
        list($organization, $repository) = explode('/', $name);
        if (($key = array_search("{$organization}/{$repository}", array_column($this->modules, 'name'))) !== false) {
            $module = $this->modules[$key];
            unset($this->modules[$key]);
            if (save_json($this->jsonPath, $this->modules) !== false) {
                $vendorPath = base_path('vendor/'.$name);
                if ($this->app['files']->exists($vendorPath)) {
                    $info = load_json($vendorPath.'/module.json');
                    if (!empty($info)) {
                        if ($this->app['files']->exists($publicPath = public_path($name))) {
                            $this->app['files']->copyDirectory($publicPath, $vendorPath.'/public');
                            $this->app['files']->deleteDirectory($publicPath);
                        }
                        $this->app['zipper']->make(storage_path("app/modules/backup/{$organization}_{$repository}.{$info['version']}.zip"))->add($vendorPath)->close();
                        $this->app['files']->deleteDirectory($vendorPath);
                    }
                }
            }
            return $module;
        }

        return false;
    }

    protected function info($organization, $repository) {
        return $this->app['cache']->remember("ModuleInfo_{$organization}/{$repository}", $this->cacheTime, function () use ($organization, $repository) {
            $latest = json_decode(curl_get("https://api.github.com/repos/{$organization}/{$repository}/releases/latest"), true);
            if (!isset($latest['message'])) {
                $info = json_decode(curl_get("https://raw.githubusercontent.com/{$organization}/{$repository}/{$latest['name']}/module.json"), true);
                if (!empty($info)) {
                    $info['zip_url'] = "https://github.com/{$organization}/{$repository}/archive/{$latest['name']}.zip";
                    return $info;
                }
            }
            return [];
        });
    }

    protected function format($module) {
        unset($module['autoload']);
        unset($module['providers']);
        unset($module['zip_url']);

        if (empty($module['nameBase64'])) {
            $module['nameBase64'] = base64_encode($module['name']);
        }
        if ($module['type'] == 'module') {
            if (empty($module['image'])) {
                $module['image'] = '/themes/admin/images/icons/puzzle.png';
            }
        } else {
            $module['default'] = ($module['name'] == $this->defaultTheme);
            if (empty($module['image'])) {
                $module['image'] = '/themes/admin/images/icons/palette.png';
            }
        }
        return $module;
    }
}