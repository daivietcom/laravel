<?php namespace VnSource\Services;

use Countable;
use Illuminate\Translation\MessageSelector;
use Illuminate\Contracts\Translation\Translator as TranslatorContract;

class Translation implements TranslatorContract {
	protected $_lang = array();
	protected $lang = array();
	public $is_loaded = array();
	protected $locale;
 	protected $selector;
 	protected $app;

	public function __construct($app) {
		$this->app = $app;
		$this->locale = $this->app['config']->get('app.locale');
        $this->load('laravel');
		$this->load('system');
	}

	public function trans($item, array $replace = [], $name = NULL) {
		return $this->get($item, $replace, $name);
	}

	public function getFromJson($item, $replace = NULL, $name = NULL) {
		return $this->get($item, $replace, $name);
	}

	public function transChoice($item, $number, array $replace = [], $name = null) {
		if (is_array($number) || $number instanceof Countable) {
				$number = count($number);
		}
		$replace['count'] = $number;

		$trans = $this->get($item, $replace, $name);
		return $this->getSelector()->choose($trans, $number, $this->locale);
	}

	public function get($item, $replace = NULL, $name = NULL) {
		if (empty($name)) {
			return $this->replace(isset($this->_lang[$item]) ? $this->_lang[$item] : $item, $replace);
		}
		$name = strtolower($name);

		return $this->replace(isset($this->lang[$name], $this->lang[$name][$item]) ? $this->lang[$name][$item] : $item, $replace);
	}

	public function gets($name) {
		$name = strtolower($name);
		if ( in_array($name, $this->is_loaded)){
			return $this->lang[$name];
		} else {
			return [];
		}
	}

	protected function replace($lang, $replace = NULL) {
		if (empty($replace)){
			return $lang;
		}
		foreach((array)$replace as $key => $value){
			$lang = str_replace(':'.$key, $value, $lang);
		}
		return $lang;
	}

	public function load($name, $result = false) {
		$_name = strtolower($name);
		if (strpos($_name, '/') !== False) {
			$path = base_path('vendor/' . $name . '/Languages/' .$this->locale.'.php');
		} else {
			$path = base_path('resources/languages/'.$this->locale.'/'.$name.'.php');
		}

		if ($result) {
		    return require($path);
        }

        if ( !in_array($_name, $this->is_loaded) && file_exists($path)) {
                $language = require($path);
                $this->is_loaded[] = $_name;
                if ( ! empty($language)) {
                    $this->_lang = array_merge($this->_lang, $language);
                    $this->lang[$_name] = $language;
                }
                unset($language);
        }
	}


	public function getLocale() {
		return $this->locale;
	}
	public function setLocale($locale) {
		$this->locale = $locale;
	}

	public function getSelector()
	{
			if (! isset($this->selector)) {
					$this->selector = new MessageSelector;
			}

			return $this->selector;
	}

	public function setSelector(MessageSelector $selector)
	{
			$this->selector = $selector;
	}
}
