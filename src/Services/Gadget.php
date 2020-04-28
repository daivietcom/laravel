<?php namespace VnSource\Services;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;

class Gadget{

	protected $blade;
	protected $app;
	protected $gadgets = [];
    protected $groups = [];
    protected $themeGadgets = null;

	public function __construct(BladeCompiler $blade, $app) {
		$this->blade = $blade;
		$this->app = $app;

        $this->blade->extend(function ($view) {
            $pattern = $this->createMatcher('gadget');
            $replace = '$1<?php echo Gadget::get$2; ?>';
            return preg_replace($pattern, $replace, $view);
        });
        $this->blade->extend(function ($view) {
            $pattern = $this->createMatcher('gadgets');
            $replace = '$1<?php echo Gadget::group$2; ?>';
            return preg_replace($pattern, $replace, $view);
        });
	}

	public function register($name, $gadget)
	{
		$this->gadgets[$name] = $gadget;
		$this->registerBlade($name);
	}

	protected function registerBlade($name)
	{
		$this->blade->extend(function ($view) use ($name) {
			$pattern = $this->createMatcher($name);
			$replace = '$1<?php echo Gadget::'.$name.'$2; ?>';
			return preg_replace($pattern, $replace, $view);
		});
	}

	protected function createMatcher($function)
	{
		return '/(?<!\w)(\s*)@'.$function.'(\s*\(.*\))/';
	}

	public function has($name)
	{
		return array_key_exists($name, $this->gadgets);
	}

	public function __call($name, array $parameters = array())
	{
		return $this->call($name, $parameters);
	}

	public function call($name, array $parameters = array())
	{
        if ($this->has($name)) {
            $callback = $this->gadgets[$name]['callback'];
            return $this->getCallback($callback, $parameters);
        }
        return null;
	}

	public function get()
	{
        $parameters = func_get_args();
        $name = array_shift($parameters);
		if ($this->has($name)) {
			$callback = $this->gadgets[$name]['callback'];
			return $this->getCallback($callback, $parameters);
		}
		return null;
	}

	protected function getCallback($callback, array $parameters)
	{
		if ($callback instanceof Closure) {
			return $this->createCallableCallback($callback, $parameters);
		} elseif (is_string($callback)) {
			return $this->createStringCallback($callback, $parameters);
		} else {
			return null;
		}
	}

	protected function createStringCallback($callback, array $parameters)
	{
		if (function_exists($callback)) {
			return $this->createCallableCallback($callback, $parameters);
		} else {
			return $this->createClassesCallback($callback, $parameters);
		}
	}

	protected function createCallableCallback($callback, array $parameters)
	{
		return call_user_func_array($callback, $parameters);
	}

	protected function createClassesCallback($callback, array $parameters)
	{
		list($className, $method) = Str::parseCallback($callback, 'register');
		$instance = $this->app->make($className);
		$callable = array($instance, $method);
		return $this->createCallableCallback($callable, $parameters);
	}

    public function group($name)
    {
        if ($this->themeGadgets === null) {
            $this->loadThemeGadget();
        }
        if (empty($this->themeGadgets[$name])) {
            return null;
        } else {
            $gadgetResult = '';
            foreach ($this->themeGadgets[$name] as $gadget) {
                if ($gadget['visible'] && $this->has($gadget['name'])) {
                    $callback = $this->gadgets[$gadget['name']]['callback'];
                    $gadgetResult .= $this->getCallback($callback, empty($gadget['parameters'])?[]:(array)$gadget['parameters']);
                }
            }
            return $gadgetResult;
        }
    }

    public function addGroup($name, $description = '') {
	    if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->groups[$key] = $this->app['translator']->trans($value);
	        }
        } else {
            $this->groups[$name] = $this->app['translator']->trans($description);
        }
    }

    public function getGroup() {
	    return $this->groups;
    }

    public function getGadget() {
        return $this->gadgets;
    }

    public function getThemeGadget() {
        if ($this->themeGadgets === null) {
            $this->loadThemeGadget();
        }
        return $this->themeGadgets;
    }

    protected function loadThemeGadget() {
        $gadgetPath = resource_path('views/frontend/gadget.json');
        if(!empty($this->app['config']->get('site.theme'))) {
            $gadgetPath = base_path('vendor/'.$this->app['config']->get('site.theme').'/gadget.json');
        }
        $this->themeGadgets = load_json($gadgetPath);
    }

}
