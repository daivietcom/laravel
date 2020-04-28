<?php

if (!function_exists('load_lang')) {
    function load_lang($name, $result = false)
    {
        return app('translator')->load($name, $result);
    }
}

if (!function_exists('get_lang')) {
    function get_lang($name)
    {
        return app('translator')->gets($name);
    }
}

if (!function_exists('add_script')) {
    function add_script($script, $type = ASSET_THEME)
    {
        return app('vns')->addScript($script, $type);
    }
}

if (!function_exists('show_script')) {
    function show_script($script, $type = ASSET_THEME)
    {
        return app('vns')->showScript($script, $type);
    }
}
if (!function_exists('add_style')) {
    function add_style($style, $type = ASSET_THEME)
    {
        return app('vns')->addStyle($style, $type);
    }
}
if (!function_exists('show_style')) {
    function show_style($style, $type = ASSET_THEME)
    {
        return app('vns')->showStyle($style, $type);
    }
}
if (!function_exists('script')) {
    function script()
    {
        return app('vns')->script();
    }
}
if (!function_exists('style')) {
    function style()
    {
        return app('vns')->style();
    }
}
if (!function_exists('show_image')) {
    function show_image($image, $attributes = array())
    {
        return app('vns')->showImage($image, $attributes);
    }
}
if (!function_exists('set_script_path')) {
    function set_script_path($path)
    {
        return app('vns')->scriptPath($path);
    }
}
if (!function_exists('set_style_path')) {
    function set_style_path($path)
    {
        return app('vns')->stylePath($path);
    }
}
if (!function_exists('set_image_path')) {
    function set_image_path($path)
    {
        return app('vns')->imagePath($path);
    }
}
if (!function_exists('set_asset_path')) {
    function set_asset_path($path)
    {
        app('vns')->imagePath($path.'/images');
        app('vns')->stylePath($path.'/css');
        app('vns')->scriptPath($path.'/js');
    }
}
if (!function_exists('script_path')) {
    function script_path($path)
    {
        return app('vns')->scriptPath($path, true);
    }
}
if (!function_exists('style_path')) {
    function style_path($path)
    {
        return app('vns')->stylePath($path, true);
    }
}
if (!function_exists('image_path')) {
    function image_path($path)
    {
        return app('vns')->imagePath($path, true);
    }
}
if (!function_exists('script_object')) {
    function script_object(array $array = array())
    {
        if (is_assoc($array)) {
            $js = '[';
            foreach ($array as $value) {
                switch (gettype($value)) {
                    case "string":
                        if (strpos($value, 'function') === 0 || strpos($value, '$(') === 0) {
                            $js .= $value;
                        } else {
                            $value = str_replace("\n", '\n', $value);
                            $value = str_replace("'", "\'", $value);
                            $js .= "'$value'";
                        }
                        break;
                    case "integer":
                    case "double":
                        $js .= $value;
                        break;
                    case "boolean":
                        $js .= $value == false ? 'false' : 'true';
                        break;
                    case "array":
                        $js .= script_object($value);
                        break;
                    case "NULL":
                        $js .= 'null';
                        break;
                    default:
                        break;
                }
                $js .= ',';
            }
            $js = rtrim($js, ',');
            $js .= ']';
        } else {
            $js = '{';
            foreach ($array as $key => $value) {
                $key = str_replace("'", "\'", $key);
                $js .= "'$key': ";
                switch (gettype($value)) {
                    case "string":
                        if (strpos($value, 'function') === 0 || strpos($value, '$(') === 0) {
                            $js .= $value;
                        } else {
                            $value = str_replace("\n", '\n', $value);
                            $value = str_replace("'", "\'", $value);
                            $js .= "'$value'";
                        }
                        break;
                    case "integer":
                    case "double":
                        $js .= $value;
                        break;
                    case "boolean":
                        $js .= $value == false ? 'false' : 'true';
                        break;
                    case "array":
                        $js .= script_object($value);
                        break;
                    case "NULL":
                        $js .= 'null';
                        break;
                    default:
                        break;
                }
                $js .= ',';
            }
            $js = rtrim($js, ',');
            $js .= '}';
        }
        return $js;
    }
}
if (!function_exists('is_assoc')) {
    function is_assoc($array)
    {
        return is_numeric(implode('', array_keys($array)));
    }
}

if (!function_exists('set_active')) {
    function set_active($uri, $class = 'active')
    {
        if (strpos($uri, '/*') === false) {
            return app('request')->is($uri) ? $class : '';
        } else {
            return (app('request')->is($uri) | app('request')->is(str_replace('/*', '', $uri))) ? $class : '';
        }
    }
}

if (!function_exists('breadcrumb')) {
    function breadcrumb($datas)
    {
        $html = '<ul class="breadcrumb"><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . url('') . '" rel="nofollow" itemprop="url"><span itemprop="title">' . __('Homepage') . '</span></a></li>';
        if (is_string($datas)) {
            $uri = app('request')->fullUrl();
            $html .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . url($uri) . '" rel="nofollow" itemprop="url"><span itemprop="title">' . $datas . '</span></a></li>';
        } else {
            foreach ($datas as $data) {
                $html .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
                if (!isset($data['uri'])) {
                    $data['uri'] = app('request')->fullUrl();
                }
                $html .= '<a href="' . url($data['uri']) . '" rel="nofollow" itemprop="url"><span itemprop="title">' . $data['name'] . '</span></a>';
                $html .= '</li>';
            }
        }

        $html .= '</ul>';
        return $html;
    }
}

if (!function_exists('replace_config')) {
    function replace_config($name, $value)
    {
        if (is_array($value)) {
            if (is_assoc($value)) {
                foreach ($value as $config) {
                    app('config')->push($name, $config);
                }
            } else {
                foreach ($value as $key => $config) {
                    replace_config($name . '.' . $key, $config);
                }
            }
        } else {
            app('config')->set($name, $value);
        }
    }
}

if (!function_exists('admin_url')) {
    function admin_url($path)
    {
        $path = trim(config('site.admin_path'), '/') . '/' . trim($path, '/');
        return url($path);
    }
}

if (!function_exists('date_in_range')) {
    function date_in_range($start_date, $end_date)
    {
        $start = Carbon\Carbon::parse($start_date);
        $end = Carbon\Carbon::parse($end_date);
        $now = Carbon\Carbon::now();

        if ($now->lt($start)) {
            return -1;
        } elseif ($now->gt($end)) {
            return 1;
        } else {
            return 0;
        }
    }
}

if (!function_exists('get_thumb')) {
    function get_thumb($src)
    {
        return preg_replace('/([0-9]{4})\/([0-9]{2})\/([0-9a-z]{32})\.jpg/', '$1/$2/thumbs/$3.jpg', $src);
    }
}

if (!function_exists('curl_get')) {
    function curl_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6531.22.7 (compatible; Googlebot-Mobile/2.1; +http://www.google.com/bot.html)');
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

if (!function_exists('curl_post')) {
    function curl_post($url, $fields)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
    }
}

if (!function_exists('url_headers')) {
    function url_headers($url)
    {
        $headers = get_headers($url);
        array_shift($headers);
        $new_headers = [];
        foreach ($headers as $header) {
            $ex = explode(': ', $header);
            $new_headers[strtolower($ex[0])] = $ex[1];
        }
        return $new_headers;
    }
}

if (!function_exists('fix_media_url')) {
    function fix_media_url($content)
    {
        return preg_replace('/src="media\/([0-9]{4}\/[0-9]{2}\/[0-9a-z]{32}\.jpg)"/', 'src="' . config('filesystems.disks.local.url') . '$1"', $content);
    }
}

if (!function_exists('to_ascii')) {
    function to_ascii($str)
    {
        if (!$str) return false;
        else {
            $unicode = array(
                'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
                "c" => "ç",
                'd' => 'đ',
                'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
                'i' => 'í|ì|ỉ|ĩ|ị',
                'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
                'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
                'y' => 'ý|ỳ|ỷ|ỹ|ỵ|Y|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
                'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
                'C' => 'Ç',
                'D' => 'Đ',
                'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
                'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
                'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
                'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
                'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            );
            foreach ($unicode as $nonUnicode => $uni) $str = preg_replace("/($uni)/i", $nonUnicode, $str);
            return $str;
        }
    }
}

if (!function_exists('to_utf8')) {
    function to_utf8($str)
    {
        if (!$str) return false;
        else {
            $viqr = array(
                '&aacute;' => 'á', '&agrave;' => 'à', '&#7843;' => 'ả', '&atilde;' => 'ã',
                '&#7841;' => 'ạ', '&#259;' => 'ă', '&#7855;' => 'ắ', '&#7863;' => 'ặ',
                '&#7857;' => 'ằ', '&#7859;' => 'ẳ', '&acirc;' => 'â', '&#7845;' => 'ấ',
                '&#7847;' => 'ầ', '&#7849;' => 'ẩ', '&#7851;' => 'ẫ', '&#7853;' => 'ậ',
                '&#273;' => 'đ', '&eacute;' => 'é', '&egrave;' => 'è', '&#7867;' => 'ẻ',
                '&#7869;' => 'ẽ', '&#7865;' => 'ẹ', '&ecirc;' => 'ê', '&#7871;' => 'ế',
                '&#7873;' => 'ề', '&#7875;' => 'ể', '&#7877;' => 'ễ', '&#7879;' => 'ệ',
                '&iacute;' => 'í', '&igrave;' => 'ì', '&#7881;' => 'ỉ', '&#297;' => 'ĩ',
                '&#7883;' => 'ị', '&oacute;' => 'ó', '&ograve;' => 'ò', '&#7887;' => 'ỏ',
                '&otilde;' => 'õ', '&#7885;' => 'ọ', '&ocirc;' => 'ô', '&#7889;' => 'ố',
                '&#7891;' => 'ồ', '&#7893;' => 'ổ', '&#7895;' => 'ỗ', '&#7897;' => 'ộ',
                '&#417;' => 'ơ', '&#7899;' => 'ớ', '&#7901;' => 'ờ', '&#7903;' => 'ở',
                '&#7905;' => 'ỡ', '&#7907;' => 'ợ', '&uacute;' => 'ú', '&ugrave;' => 'ù',
                '&#7911;' => 'ủ', '&#361;' => 'ũ', '&#7909;' => 'ụ', '&#432;' => 'ư',
                '&#7913;' => 'ứ', '&#7915;' => 'ừ', '&#7917;' => 'ử', '&#7919;' => 'ữ',
                '&#7921;' => 'ự', '&yacute;' => 'ý', '&#7923;' => 'ỳ', '&#7927;' => 'ỷ',
                '&#7929;' => 'ỹ', '&#7925;' => 'ỵ', '&Aacute;' => 'Á', '&Agrave;' => 'À',
                '&#7842;' => 'Ả', '&Atilde;' => 'Ã', '&#7840;' => 'Ạ', '&#258;' => 'Ă',
                '&#7854;' => 'Ắ', '&#7862;' => 'Ặ', '&#7856;' => 'Ằ', '&#7858;' => 'Ẳ',
                '&#7860;' => 'Ẵ', '&Acirc;' => 'Â', '&#7844;' => 'Ấ', '&#7846;' => 'Ầ',
                '&#7848;' => 'Ẩ', '&#7850;' => 'Ẫ', '&#7852;' => 'Ậ', '&#272;' => 'Đ',
                '&Eacute;' => 'É', '&Egrave;' => 'È', '&#7866;' => 'Ẻ', '&#7868;' => 'Ẽ',
                '&#7864;' => 'Ẹ', '&Ecirc;' => 'Ê', '&#7870;' => 'Ế', '&#7872;' => 'Ề',
                '&#7874;' => 'Ể', '&#7876;' => 'Ễ', '&#7878;' => 'Ệ', '&Iacute;' => 'Í',
                '&Igrave;' => 'Ì', '&#7880;' => 'Ỉ', '&#296;' => 'Ĩ', '&#7882;' => 'Ị',
                '&Oacute;' => 'Ó', '&Ograve;' => 'Ò', '&#7886;' => 'Ỏ', '&#7884;' => 'Ọ',
                '&Ocirc;' => 'Ô', '&#7888;' => 'Ố', '&#7890;' => 'Ồ', '&#7892;' => 'Ổ',
                '&#7894;' => 'Ỗ', '&#7896;' => 'Ộ', '&#416;' => 'Ơ', '&#7898;' => 'Ớ',
                '&#7900;' => 'Ờ', '&#7902;' => 'Ở', '&#7904;' => 'Ỡ', '&#7906;' => 'Ợ',
                '&Uacute;' => 'Ú', '&Ugrave;' => 'Ù', '&#7910;' => 'Ủ', '&#360;' => 'Ũ',
                '&#7908;' => 'Ụ', '&#431;' => 'Ư', '&#7912;' => 'Ứ', '&#7914;' => 'Ừ',
                '&#7916;' => 'Ử', '&#7918;' => 'Ữ', '&#7920;' => 'Ự', '&Yacute;' => 'Ý',
                '&#7922;' => 'Ỳ', '&#7926;' => 'Ỷ', '&#7928;' => 'Ỹ', '&#7924;' => 'Ỵ',
                'CafeLand - ' => ''
            );
            foreach ($viqr as $uni => $nonUnicode) $str = str_replace($uni, $nonUnicode, $str);
            return $str;
        }
    }
}

if (!function_exists('to_slug')) {
    function to_slug($str, $seperator = '-')
    {
        $str = ltrim(chop($str));
        $str = to_ascii($str);
        $str = preg_replace("[\W]", $seperator, $str);
        $str = str_replace("_", $seperator, $str);
        $str = preg_replace("/[$seperator]{2,}/", $seperator, $str);
        $str = ltrim(rtrim($str, $seperator), $seperator);
        return strtolower($str);
    }
}

if (!function_exists('navbar_cpanel')) {
    function navbar_cpanel($data)
    {
        $user = Auth::user();
        if (!isset($data['permission']) || $user->can($data['permission'])) {
            if (isset($data['name'])) {
                app('config')->push('angularstate', [
                    'name' => $data['name'],
                    'url' => $data['url'],
                    'template' => $data['template'],
                    'label' => $data['label'],
                ]);
                unset($data['url']);
                unset($data['template']);
                unset($data['permission']);
            } elseif (isset($data['sub'])) {
                $totalSub = count($data['sub']);
                for ($index = 0; $index < $totalSub; $index++) {
                    if (!isset($data['sub'][$index]['permission']) || $user->can($data['sub'][$index]['permission'])) {
                        if (isset($data['sub'][$index]['name'])) {
                            app('config')->push('angularstate', [
                                'name' => $data['sub'][$index]['name'],
                                'url' => $data['sub'][$index]['url'],
                                'template' => $data['sub'][$index]['template'],
                                'label' => $data['sub'][$index]['label'],
                            ]);
                            unset($data['sub'][$index]['url']);
                            unset($data['sub'][$index]['template']);
                            unset($data['sub'][$index]['permission']);
                        }
                        if (isset($data['sub'][$index]['hidden'])) {
                            unset($data['sub'][$index]);
                        }
                    }
                }
            }
            app('config')->push('navbarcpanel', $data);
        }

        unset($user);
    }
}

if (!function_exists('get_navbar_cpanel')) {
    function get_navbar_cpanel()
    {
        $navbarCpanel = [];

        if (!empty($moduleNavbarCpanel = config('navbarcpanel'))) {
            foreach ($moduleNavbarCpanel as $item) {
                array_push($navbarCpanel, $item);
            }
        }

        return $navbarCpanel;
    }
}

if (!function_exists('get_angular_state')) {
    function get_angular_state()
    {
        $angularState = [];
        if (!empty($moduleAngularState = config('angularstate'))) {
            foreach ($moduleAngularState as $item) {
                array_push($angularState, $item);
            }
        }

        return $angularState;
    }
}

if (!function_exists('name_to_char')) {
    function name_to_char($name)
    {
        $_name = explode(' ', trim($name));
        if (in_array(config('app.locale'), ['vi'])) {
            $char = mb_substr(last($_name), 0, 1);
        } else {
            $char = mb_substr(head($_name), 0, 1);
        }
        return strtoupper($char);
    }
}

if (!function_exists('paginator_url')) {
    function paginator_url($url)
    {
        $urls = explode('?', $url);
        if (count($urls) > 1 && preg_match("/page=([0-9]+)/", $urls[1], $datas)) {
            $url = rtrim(rtrim($urls[0], '/') . ($datas[1] == 1 ? '' : '/' . $datas[1]) . '?' . preg_replace('/page=[0-9]+[&]*/', '', $urls[1]), '?');
        }
        return $url;
    }
}

if (!function_exists('cast_object')) {
    function cast_object($obj, $from, $to)
    {
        $lenF = strlen($from);
        $lenT = strlen($to);
        return unserialize(str_replace('O:' . $lenF . ':"' . $from . '"', 'O:' . $lenT . ':"' . $to . '"', serialize($obj)));
    }
}

if (!function_exists('cast_uri')) {
    function cast_uri($data, $type = 'post')
    {
        $uri = config('site.uri.' . $type);
        if(!is_array($data)) {
            $data = $data->toArray();
        }
        foreach ((array)$data as $key => $value) {
            if(!is_array($value)) {
                $uri = str_replace('{' . $key . '}', $value, $uri);
            }
        }
        return '/' . trim($uri, '/');
    }
}

if (!function_exists('cache_time')) {
    function cache_time()
    {
        $time = config('site.cache');
        return rand($time[0], $time[1]);
    }
}

if (!function_exists('proxy_image')) {
    function proxy_image($url)
    {
        return (config('site.ssl') ? 'https' : 'http') . '://images.weserv.nl/?url=' . urlencode(preg_replace('/^(http:\/\/|https:\/\/|\/\/)/', '', $url));
    }
}

if (!function_exists('minify_html')) {
    function minify_html($html, $clean = false)
    {
        if ($clean == true) {
            $html = preg_replace('@<!--.*?-->@siu', '', $html);
            $html = preg_replace('@<script[^>]*?.*?</script>@siu', '', $html);
            $html = preg_replace('@<head>.*?</head>@siu', '', $html);
        }
        // Clean comments
        $html = preg_replace('/<!--([^\[|(<!)].*)/', '', $html);
        $html = preg_replace('/(?<!\S)\/\/\s*[^\r\n]*/', '', $html);
        // Clean Whitespace
        $html = preg_replace('/[ ]{2,}/', ' ', $html);
        $html = preg_replace('/(\r?\n)/', '', $html);
        $html = preg_replace('/[ ]+,/', ',', $html);

        if ($clean == true) {
            $html = preg_replace('/[ ]*</', '<', $html);
            $html = preg_replace('/>[ ]*/', '>', $html);
        }

        return $html;
    }
}

if (!function_exists('load_json')) {
    function load_json($path, $assoc = true)
    {
        if (!file_exists($path)) {
            return false;
        }
        return json_decode(file_get_contents($path), $assoc);
    }
}

if (!function_exists('is_base64')) {
    function is_base64($string)
    {
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) return false;

        if (!$decoded = base64_decode($string, true)) return false;

        if (base64_encode($decoded) != $string) return false;

        return true;
    }
}

if (!function_exists('save_json')) {
    function save_json($path, $content, $options = JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT)
    {
        if (is_array($content)) {
            $content = json_encode($content, $options);
        }
        if (!app('files')->exists($directory = app('files')->dirname($path))) {
            app('files')->makeDirectory($directory, 0755, true);
        }
        return app('files')->put($path, $content);
    }
}
