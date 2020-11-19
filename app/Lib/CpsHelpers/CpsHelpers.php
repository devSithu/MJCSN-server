<?php

if (!function_exists('format_datetime')) {

    function format_datetime($date_time, $type = 1)
    {
        if (empty($date_time)) {
            return "";
        }

        $dt = \Carbon\Carbon::parse($date_time);
        if ($type == 1) {
            return $dt->format("Y/m/d H:i");
        } else if ($type == 2) {
            return $dt->format("Y年m月d日 H:i");
        }
    }

}

if (!function_exists('format_mmdd')) {

    function format_mmdd($date_time, $type = 1)
    {
        $dt = \Carbon\Carbon::parse($date_time);
        if ($type == 1) {
            return $dt->format("m/d");
        } else if ($type == 2) {
            return $dt->format("m月d日");
        } else if ($type == 3) {
            return $dt->format("m_d");
        }
    }

}

if (!function_exists('format_date')) {

    function format_date($date_time, $type = 1)
    {
        if (empty($date_time)) {
            return "";
        }

        $dt = \Carbon\Carbon::parse($date_time);
        if ($type == 1) {
            return $dt->format("Y/m/d");
        } else if ($type == 2) {
            return $dt->format("Y年m月d日");
        }
    }

}

if (!function_exists('format_time')) {

    function format_time($date_time, $type = 1)
    {
        $dt = \Carbon\Carbon::parse($date_time);
        if ($type == 1) {
            return $dt->format("H:i");
        } else if ($type == 2) {
            return $dt->format("H:i:s");
        }
    }

}

if (!function_exists('carbon')) {

    function carbon($datetime = 'now')
    {
        return \Carbon\Carbon::parse($datetime);
    }
}

if (!function_exists('is_active_route')) {

    function is_active_route($path)
    {
        if (!is_array($path)) {
            return str_is($path . "*", request()->url());
        }

        foreach ($path as $value) {
            if (is_array($value) && !array_key_exists(0, $value)) {
                // for 2nd level menu
                foreach ($value as $val) {
                    if (str_is($val[0] . "*", request()->url())) {
                        return true;
                    }
                }
            } else {
                if (str_is($value[0] . "*", request()->url())) {
                    return true;
                }
            }
        }
    }

}

if (!function_exists('cps_trans')) {

    function cps_trans($id = null)
    {
        if (is_null($id)) {
            return app('translator');
        }

        $exhibition_id = \Route::input('exhibition_id');

        if (empty($exhibition_id)) {
            return trans($id);
        }

        $namespace = $exhibition_id;
        $custom_path = Config::get("custom_resources.path") . "/" . $exhibition_id . "/lang";
        Lang::addNamespace($namespace, $custom_path);

        $newId = $namespace . '::' . $id;
        $translatedValue = app('translator')->trans($newId);

        // If the translater can't locate the key ($newId), it will return the same value as the given key ($newId)
        if ($translatedValue == $newId) {
            $translatedValue = app('translator')->trans($id);
        }

        return $translatedValue;
    }

}

if (!function_exists('auto_linker')) {

    function auto_linker($str)
    {
        if (strpos($str, '</a>') === false) {
            $pat_sub = preg_quote('-._~%:/?#[]@!$&\'()*+,;=', '/'); // 正規表現向けのエスケープ処理
            $pat = '/((http|https):\/\/[0-9a-z' . $pat_sub . ']+)/i'; // 正規表現パターン
            $rep = '<a href="\\1" target=\"_BLANK\">\\1</a>'; // \\1が正規表現にマッチした文字列に置き換わります

            $str = preg_replace($pat, $rep, $str); // 実処理
        }
        return $str;
    }

}

if (!function_exists('breadcrumbs')) {

    function breadcrumbs()
    {
        $breadcrumbs = [];
        $first = \Route::current();
        $currentRoute = $first;
        $currentParam = $currentRoute->parameters();

        while (true) {
            $breadcrumbs[] = ['display' => $currentRoute->getDisplayName(),
                'href' => $first == $currentRoute ? null : route($currentRoute->getName(), $currentParam)];
            $parent = $currentRoute->getParentName();
            if (!$parent) {
                break;
            }
            $currentRoute = \Route::getRoutes()
                ->getByName($parent);
        }

        return array_reverse($breadcrumbs);
    }

}

if (!function_exists('response_json')) {

    function response_json($data = [], array $metadata = [], $status = 200, array $headers = [])
    {
        $response = array_merge($status == 200 ? ["data" => $data] : [], $metadata);

        return \Response::json($response, $status, $headers);
    }

    function response_json_error($status, $errors = [], array $headers = [])
    {
        $errors = $errors ?: [["code" => $status, "source" => request()->path()]];

        return response_json([], ["errors" => $errors], $status, $headers);
    }

}

if (!function_exists('implode_array_object')) {

    function implode_array_object(string $glue, array $pieces, $key_or_func)
    {
        return \implode($glue, array_map(is_callable($key_or_func) ? $key_or_func : function ($obj) use ($key_or_func) {
            return $obj[$key_or_func];
        }, $pieces));
    }

}

if (!function_exists('format_visitor_item_value')) {

    function format_visitor_item_value($items)
    {
        $value = "";
        foreach ($items as $item) {
            $v = $item["value"];
            if ($item->is_other) {
                $v = empty($v) ? 'その他' : ('その他:' . $v);
            }
            $value .= ($v . ",");
        }

        return e(rtrim($value, ","));
    }

}

if (!function_exists('json_encode_with_function')) {

    function json_encode_with_function($array)
    {
        $string = json_encode($array);
        $string = str_replace('"%%', '', $string);
        $string = str_replace('%%"', '', $string);
        $string = str_replace('\r', '', $string);
        $string = str_replace('\n', '', $string);

        return $string;
    }
}

if (!function_exists('auto_version')) {

    function auto_version($file)
    {
        if (strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file)) {
            return $file;
        }

        $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
        return $file . "?fv=" . $mtime;
    }
}

if (!function_exists('csp_script_if')) {

    function csp_script_if($script)
    {
        if (strpos($script, '/') !== 0) {
            $script = '/scripts/' . str_replace('.', '/', $script) . '.js';
        }
        if (\file_exists($_SERVER['DOCUMENT_ROOT'] . $script)) {
            return csp_script($script);
        }
    }
}

if (!function_exists('csp_script')) {

    function csp_script($script)
    {
        if (strpos($script, '/') !== 0) {
            $script = '/scripts/' . str_replace('.', '/', $script) . '.js';
        }
        return (config('app.debug') ? PHP_EOL : '') . '<script src="' . auto_version($script) . '" ></script>';
    }
}

if (!function_exists('csp_scope')) {

    function csp_scope($vars, $scope = 'vars')
    {
        return '<script class="_scope hidden" hidden data-scope="' . $scope . '" type="text/json" >'
        . json_encode($vars, JSON_HEX_TAG | (config('app.debug') ? JSON_PRETTY_PRINT : 0))
        . '</script>'
        . csp_script('/js/scope.js');
    }
}

if (!function_exists('valOrNull')) {

    function valOrNull($val)
    {
        return $val === '' ? null : $val;
    }
}

if (!function_exists('route_input')) {

    function route_input($key = null)
    {
        return $key ? Route::input($key) : Route::current()->parameters();
    }
}

if (!function_exists('url_input')) {

    function url_input($key = null, $default = null)
    {
        $params = array_merge(Route::current()->parameters(), request()->query());
        return $key ? array_get($params, $key, $default) : $params;
    }
}

if (!function_exists('parent_route')) {

    function parent_route($name = 'current')
    {
        //TODO: calculate the parent route from the given name
        $route = Route::current();
        return route($route->getParentName(), $route->parameters());
    }
}

if (!function_exists('strtoarray')) {

    function strtoarray(string $str, $options = [])
    {
        $options = array_merge([
            'delimiter' => ',',
            'filter' => true,
            'unique' => true,
            'trim' => true,
        ], $options);
        $arr = explode($options['delimiter'], $str);
        $arr = $options['trim'] ? array_map('trim', $arr) : $arr;
        $arr = $options['filter'] ? array_filter($arr) : $arr;
        $arr = $options['unique'] ? array_unique($arr) : $arr;
        return $arr;
    }
}

if (!function_exists('lambda')) {

    /**
     * <b>Caution</b> this function is very dangerous because it allows execution of arbitrary PHP code.
     * Its use thus is discouraged. If you have carefully verified that there is no other option than to use this function,
     * pay special attention not to pass <b>any user provided data</b> into it without properly validating it beforehand.
     */
    function lambda($exp, string $exp1 = '')
    {
        $use = is_string($exp) ? [] : $exp;
        $exp = is_string($exp) ? $exp : $exp1;

        if ($use) {
            extract($use, EXTR_SKIP);
            $using = ' use ($' . implode(',$', array_keys($use)) . ')';
        }

        $body = explode("=>", $exp, 2)[1] ?? '';
        $body = trim($body, '{}' . " \t\n\r\0\x0B");
        if ((!str_contains($body, ';')) || (!trim(explode(";", $body)[1] ?? ''))) {
            $body = 'return ' . $body . ';';
        }

        $params = trim(explode("=>", $exp)[0], '()' . " \t\n\r\0\x0B");

        return eval('return function(' . $params . ')' . ($using ?? '') . ' { ' . $body . ' };');
    }
}

if (!function_exists('path')) {

    function path()
    {
        return implode(DIRECTORY_SEPARATOR, func_get_args());
    }
}

if (!function_exists('throw_response_if')) {

    function throw_response_if($boolean, $content)
    {
        if ($boolean) {
            throw new Illuminate\Http\Exceptions\HttpResponseException(response($content));
        }
    }
}

if (!function_exists('change_csv_error_format')) {

    /**
     * for csv error body
     * this function change validation error format to csv line type
     * @param array $validator->errors()
     * @return string formatted csv body data in (line number, field, message) type
     */
    function change_csv_error_format($errors)
    {
        $csv_body = '';
        $errors_message = [];
        foreach ($errors->toArray() as $key => $message) {
            if (preg_match('/^csv_file_data\.[0-9]/', $key)) {
                preg_match_all('!\d+!', $key, $matches);
                $errors_message[] = [
                    0 => ((int) $matches[0][0] + 2) . ' 行目',
                    1 => substr($message[0], 0, strpos($message[0], '：')),
                    2 => substr($message[0], strpos($message[0], '：') + 3),
                ];
            } else if (preg_match('/^booths\.[0-9]/', $key)) {
                preg_match_all('!\d+!', $key, $matches);
                if (array_key_exists(2, $matches[0])) {
                    $matches = $matches[0][2];
                } else {
                    if (array_key_exists(1, $matches[0])) {
                        $matches = $matches[0][1];
                    } else {
                        $matches = $matches[0][0];
                    }
                }
                $errors_message[] = [
                    0 => ((int) $matches + 2) . ' 行目',
                    1 => substr($message[0], 0, strpos($message[0], '：')),
                    2 => substr($message[0], strpos($message[0], '：') + 3),
                ];
            } else if (preg_match('/^promotions\.[0-9]/', $key)) {
                preg_match_all('!\d+!', $key, $matches);

                if (array_key_exists(1, $matches[0])) {
                    $matches = $matches[0][1];
                } else {
                    $matches = $matches[0][0];
                }

                $errors_message[] = [
                    0 => ((int) $matches + 2) . ' 行目',
                    1 => substr($message[0], 0, strpos($message[0], '：')),
                    2 => substr($message[0], strpos($message[0], '：') + 3),
                ];
            } else {
                $errors_message[] = [
                    0 => '',
                    1 => '',
                    2 => $message[0],
                ];
            }
        }
        foreach ($errors_message as $key => $message) {
            $csv_body .= CpsCSV::toLineFromArray($message);
        }
        return $csv_body;
    }

}
