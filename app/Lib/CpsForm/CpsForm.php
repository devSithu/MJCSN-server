<?php

/**
 * Created by PhpStorm.
 * User: yujiro.takezawa
 * Date: 2016/01/20
 * Time: 19:37
 */

namespace App\Lib\CpsForm;

use App\Exceptions\InvalidStateException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Request;
use Session;
use \Illuminate\Support\HtmlString;

class CpsForm
{

    private $form_id;
    private $input_name = "fid";

    public function __construct()
    {
        $this->form_id = Request::input($this->input_name, str_random(16));
    }

    /**
     * @param        $name
     * @param string $default
     * @return mixed
     */
    public function old($name, $default = "")
    {
        return old($name, $default);
    }

    public function getFormId()
    {
        return $this->form_id;
    }

    public function hasFormId()
    {
        return !empty($this->form_id);
    }

    public function getInputName()
    {
        return $this->input_name;
    }

    public function start()
    {
        if (!$this->hasFormId()) {

        }
    }

    public function keep($is_seminar_first = null)
    {
        Request::flashOnly($this->input_name);
        $keepVal = array_merge(Session::get($this->form_id, []), Request::all());
        if ($is_seminar_first == null && !Request::has('seminars') && array_key_exists('seminars', $keepVal)) {
            unset($keepVal['seminars']);
        }
        Session::put($this->form_id, $keepVal);
        view()->share([$this->getInputName() => $this->getFormId()]);
    }

    public function keepExcept($keys)
    {
        Request::flashOnly($this->input_name);
        $keepVal = array_merge(Session::get($this->form_id, []), Request::except(is_array($keys) ? $keys : func_get_args()));
        if (!Request::has('flag_items') && array_key_exists('flag_items', $keepVal)) {
            unset($keepVal['flag_items']);
        }
        Session::put($this->form_id, $keepVal);
        view()->share([$this->getInputName() => $this->getFormId()]);
    }

    public function keepOnly($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();
        Request::flashOnly($this->input_name);
        $keepVal = Session::get($this->form_id, []);
        foreach ($keys as $key) {
            array_set($keepVal, $key, request($key));
        }
        Session::put($this->form_id, $keepVal);
        view()->share([$this->getInputName() => $this->getFormId()]);
    }

    public function setSession($value)
    {
        Session::put($this->form_id, $value);

        return $this->form_id;
    }

    public function end()
    {
        Session::forget($this->stampKey());
        Session::forget($this->form_id);
    }

    public function renderFormIdField()
    {
        if (strpos($this->form_id, '"') !== false) {
            $real_fid = substr($this->form_id, 0, strpos($this->form_id, '"')) . '">';
            $other_text = htmlentities(substr($this->form_id, strpos($this->form_id, '"')));
            $fid = $real_fid . $other_text;
        } else {
            $fid = $this->form_id;
        }
        return new HtmlString('<input type="hidden" name="' . $this->input_name . '" value="' . $fid . '">');
    }

    /**
     * @param $name
     * @param $default
     * @return mixed|null
     */
    public function oldOrSession($name, $default = null)
    {
        if (Session::hasOldInput($name)) {
            return old($name);
        }

        if (Session::has($this->form_id . "." . $name)) {
            return Session::get($this->form_id . "." . $name);
        }

        if (!is_null($default)) {
            return $default;
        }

        return null;
    }

    /**
     * @return array|mixed
     */
    public function sessionAll()
    {
        if ($this->hasSessionFormId()) {
            return Session::get($this->form_id);
        }

        return [];
    }

    public function validationData()
    {
        $data = [];
        if ($this->hasSessionFormId()) {
            $data = Session::get($this->form_id);
        }

        foreach (request()->all() as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    public function session($name)
    {
        if (Session::has($this->form_id . "." . $name)) {
            return Session::get($this->form_id . "." . $name);
        }

        return null;
    }

    public function updateSession($name, $value)
    {
        return Session::put($this->form_id . "." . $name, $value);
    }

    public function removeSession($name)
    {
        return Session::remove($this->form_id . "." . $name);
    }

    public function isChecked($name, $value)
    {
        $values = $this->oldOrSession($name);

        if (empty($values) || !is_array($values) || !array_key_exists($value, $values)) {
            return false;
        }

        return $values[$value] == "on";
    }

    public function hasSessionFormId()
    {
        return Session::has($this->form_id);
    }

    public function booleanToString($boolean)
    {
        return ($boolean) ? "true" : "false";
    }

    /**
     * @param bool $has
     * @return string
     */
    public function getErrorClass($has = true)
    {
        return $has ? "has-error" : "";
    }

    /**
     * @param $msg
     * @param $class
     * @return string
     */
    public function printErrorMessage($msg, $class = "")
    {
        $tag = "<div class=\"qb-error-box {$class} \">{$msg}</div>";

        return $tag;
    }

    public function input($key = null, $default = null)
    {
        if ($this->hasSessionFormId()) {
            $data = Session::get($this->form_id);
            return array_get($data, $key, request()->input($key, $default));
        }
        return request()->input($key, $default);
        //TODO: check Session::get($this->form_id . '.' . $key, request()->input($key, $default));
    }

    private function stampKey()
    {
        return "_stamps" . "." . $this->getFormId();
    }

    private function readStampValue($stamps)
    {
        return array_map(function ($stamp) {
            if (is_string($stamp)) {
                return $stamp;
            }
            return class_basename($stamp);
        }, array_wrap($stamps));
    }

    public function seal($stamps)
    {
        session([$this->stampKey() => array_merge(session($this->stampKey(), []), $this->readStampValue($stamps))]);
    }

    public function unseal($stamps)
    {
        session([$this->stampKey() => array_diff(session($this->stampKey(), []), $this->readStampValue($stamps))]);
    }

    public function isSealedOrFail($stamps, $failback = null)
    {
        if (!$this->hasSessionFormId() || array_diff($this->readStampValue($stamps), session($this->stampKey(), []))) {
            if ($failback) {
                throw new HttpResponseException(redirect($failback));
            }
            if (config('app.debug')) {
                throw new InvalidStateException('Form is not sealed with ' . implode(',', $this->readStampValue($stamps)));
            }
            abort(404);
        }
    }

    public function checkFormSessionOrFail($failback = null)
    {
        if (!CpsForm::hasSessionFormId()) {
            throw new HttpResponseException(redirect($failback ?: abort(404)));
        }
    }

}
