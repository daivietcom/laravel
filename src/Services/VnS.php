<?php namespace VnSource\Services;

use Models\Attempt;

class VnS {
    protected $app;
    protected $_script = array();
    protected $_style = array();
    protected $_unpkgPath = 'https://unpkg.com/';
    protected $_cdnjsPath = 'https://cdnjs.cloudflare.com/ajax/libs/';
    protected $_scriptPath = '/scripts/';
    protected $_stylePath = '/styles/';
    protected $_imagePath = '/images/';
    protected $_attrSkip = array('data-pattern');
    protected $newLine = "\n";
    public $modules = [];

	public function __construct($app) {
        $this->app = $app;
        $this->scriptPath('scripts');
        $this->stylePath('styles');
        $this->imagePath('images');
	}

	public function registerModuleInfo($module) {
	    $this->modules[$module['name']] = [
	        'displayName' => $module['displayName'],
            'description' => $module['description'],
            'authors' => $module['authors'],
            'version' => $module['version']
        ];
    }

    public function getModuleInfo($name) {
        return $this->modules[$name];
    }

    public function registerModuleNameSpace($name, $namespace) {
        $this->modules[$name]['namespace'] = $namespace;
    }

    public function getModuleNameSpace($name) {
        return $this->modules[$name]['namespace'];
    }

    public function addScript($script, $type = FALSE) {
        if (is_string($script)){
            $script = array(
                $script => $type
            );
        }
        $this->_script = array_merge($this->_script, $script);
        return true;
    }

    public function showScript($script, $type = ASSET_THEME) {
        if (is_string($script)){
            $script = array(
                $script => $type
            );
        }
        $html = '';
        $source = '';
        foreach ($script as $key => $value) {
            if ($value == ASSET_CONTENT){
                $source .= $key.$this->newLine;
            }
            else {
                switch ($value) {
                  case ASSET_ROOT:
                    $key = asset($key);
                    break;
                  case ASSET_BOWER:
                    $key = '/bower_components/'.$key;
                    break;
                  case ASSET_NODE:
                    $key = '/node_modules/'.$key;
                    break;
                  case ASSET_THEME:
                    $key = $this->_scriptPath.$key;
                    break;
                  case ASSET_UNPKG:
                    $key = $this->_unpkgPath.$key;
                    break;
                  case ASSET_CDNJS:
                      $key = $this->_cdnjsPath.$key;
                    break;
                  case ASSET_REMOTE:
                  default:
                    break;
                }
                $html .= '<script type="text/javascript" src="' . $key . '"></script>'.$this->newLine;
            }
        }
        if ( ! empty($source)){
            $html .= '<script type="text/javascript">'.$this->newLine;
            $html .= $source;
            $html .= '</script>';
        }
        return $html;
    }

    public function showStyle($style, $type = ASSET_THEME) {
        if (is_string($style)){
            $style = array(
                $style => $type
            );
        }
        $html = '';
        $source = '';
        foreach ($style as $key => $value) {
            if ($value == ASSET_CONTENT){
                $source .= $key.$this->newLine;
            }
            else {
                switch ($value) {
                  case ASSET_ROOT:
                    $key = asset($key);
                    break;
                  case ASSET_BOWER:
                    $key = '/bower_components/'.$key;
                    break;
                  case ASSET_NODE:
                    $key = '/node_modules/'.$key;
                    break;
                  case ASSET_THEME:
                    $key = $this->_stylePath.$key;
                    break;
                  case ASSET_UNPKG:
                    $key = $this->_unpkgPath.$key;
                    break;
                    case ASSET_CDNJS:
                        $key = $this->_cdnjsPath.$key;
                        break;
                  case ASSET_REMOTE:
                  default:
                    break;
                }
                $html .= '<link rel="stylesheet" type="text/css" href="' . $key . '" />'.$this->newLine;
            }
        }
        if ( ! empty($source)){
            $html .= '<style type="text/css">'.$this->newLine;
            $html .= $source;
            $html .= '</style>';
        }
        return $html;
    }

    public function showImage($image, $attributes = array()) {
        if(strpos($image, '//:') === FALSE) {
            $image = $this->_imagePath.$image;
        }
        return '<img src="' . $image . '"' . $this->attributes($attributes) . ' />';
    }

    public function addStyle($style, $type = FALSE) {
        if (is_string($style)){
            $style = array(
                $style => $type
            );
        }
        $this->_style = array_merge($this->_style, $style);
        return true;
    }

    public function script() {
        $html = '';
        $source = '';
        foreach ($this->_script as $key => $value) {
            if ($value == ASSET_CONTENT){
                $source .= $key.$this->newLine;
            }
            else {
                switch ($value) {
                  case ASSET_ROOT:
                    $key = asset($key);
                    break;
                  case ASSET_THEME:
                    $key = $this->_scriptPath.$key;
                    break;
                  case ASSET_UNPKG:
                    $key = $this->_unpkgPath.$key;
                    break;
                  case ASSET_REMOTE:
                  default:
                    break;
                }
                $html .= '<script type="text/javascript" src="' . $key . '"></script>'.$this->newLine;
            }
        }
        if ( ! empty($source)){
            $html .= '<script type="text/javascript">$(function(){'.$this->newLine;
            $html .= $source;
            $html .= '});</script>';
        }
        return $html;
    }

    public function style() {
        $html = '';
        $source = '';
        foreach ($this->_style as $key => $value) {
            if ($value == ASSET_CONTENT){
                $source .= $key.$this->newLine;
            }
            else {
                switch ($value) {
                  case ASSET_ROOT:
                    $key = asset($key);
                    break;
                  case ASSET_THEME:
                    $key = $this->_stylePath.$key;
                    break;
                  case ASSET_UNPKG:
                    $key = $this->_unpkgPath.$key;
                    break;
                  case ASSET_REMOTE:
                  default:
                    break;
                }
                $html .= '<link rel="stylesheet" type="text/css" href="' . $key . '" />'.$this->newLine;
            }
        }
        if ( ! empty($source)){
            $html .= '<style type="text/css">'.$this->newLine;
            $html .= $source;
            $html .= '</style>';
        }
        return $html;
    }

    public function scriptPath($path, $get = false) {
        if($get===false) {
            $this->_scriptPath = asset($path).'/';
        } else {
            return $this->_scriptPath . $path;
        }
    }

    public function stylePath($path, $get = false) {
        if($get===false) {
            $this->_stylePath = asset($path).'/';
        } else {
            return $this->_stylePath . $path;
        }
    }

    public function imagePath($path, $get = false) {
        if($get===false) {
            $this->_imagePath = asset($path).'/';
        } else {
            return $this->_imagePath . $path;
        }
    }

    protected function attributeElement($key, $value)
    {
        if (is_numeric($key)) $key = $value;

        if ( ! is_null($value)) {
            if (in_array($key, $this->_attrSkip)) return $key . '="' . $value . '"';

            return $key . '="' . e($value) . '"';
        };

        return NULL;
    }

    public function attributes(array $attributes)
    {
        $html = array();
        foreach ($attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if ( ! is_null($element)) $html[] = $element;
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    public function Decrypt($input) {

        if ($input == "") { return $input; }

        try {
            $input = base64_decode($input);
            $input = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->app['config']->get('site.cryptKey'), $input, MCRYPT_MODE_CBC, $this->app['config']->get('site.cryptIV'));
        } catch(\Exception $e) {
        }
        return preg_replace('/[^\r\n\t\x20-\x7E\xA0-\xFF]/', '', $input);
    }

    public function Encrypt($input) {

        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
        $pad = $block - (strlen($input) % $block);
        $input .= str_repeat(chr($pad), $pad);

        $input = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->app['config']->get('site.cryptKey'), $input, MCRYPT_MODE_CBC, $this->app['config']->get('site.cryptIV'));
        $input = base64_encode($input);
        return $input;
    }
}
