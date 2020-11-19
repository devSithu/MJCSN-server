<?php

namespace App\Providers;

use CpsCSV;
use Illuminate\Support\ServiceProvider;
use Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::resolver(function () {
            return new \App\Lib\CpsValidator\CpsValidator(...func_get_args());
        });

        Validator::extend('japan_tel', function ($attribute, $value, $parameters, $validator) {
            if (preg_match("/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/", $value)) {
                return true;
            }

            return false;
        });

        Validator::extend('name_kana', function ($attribute, $value, $parameters, $validator) {
            if (preg_match("/^[　・ー=＝ァ-ヶ\- ]+$/u", $value)) {
                return true;
            }

            return false;
        });

        // /**
        //  * 半角英数のみ
        //  */
        Validator::extend('only_han_eisu', function ($attribute, $value, $parameters, $validator) {
            if (preg_match("/^[a-zA-Z0-9]+$/", $value)) {
                return true;
            }

            return false;
        });

        /**
         * ぴったり桁数
         * sizeだと半角数字のみの場合、整数値として認識してしまうため
         */
        Validator::extend('size_length', function ($attribute, $value, $parameters, $validator) {
            if (mb_strlen($value) == $parameters[0]) {
                return true;
            }

            return false;
        });
        Validator::replacer('size_length', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':size', $parameters[0], $message);
        });
        /**
         * 最大の長さをチェック
         * maxだと半角数字のみの場合、最大値として認識してしまうため
         */
        Validator::extend('max_length', function ($attribute, $value, $parameters, $validator) {
            if (mb_strlen($value) <= $parameters[0]) {
                return true;
            }

            return false;
        });
        Validator::replacer('max_length', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':max', $parameters[0], $message);
        });
        /**
         * 最小の長さをチェック
         * minだと半角数字のみの場合、最小値として認識してしまうため
         */
        Validator::extend('min_length', function ($attribute, $value, $parameters, $validator) {
            if (mb_strlen($value) >= $parameters[0]) {
                return true;
            }

            return false;
        });
        Validator::replacer('min_length', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':min', $parameters[0], $message);
        });

        Validator::extend('csv_check_header', function ($attribute, $value, $parameters, $validator) {
            $file_contents = CpsCSV::getContents($value);

            if (!$file_contents) {
                return false;
            }
            $csv_header = $file_contents[0];
            return !array_diff_assoc($parameters, $csv_header);
        });

        Validator::extend('tel', function ($attribute, $value, $parameters, $validator) {
//            if (preg_match("/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/", $value)) {
            //                return true;
            //            }
            if (preg_match("/^[0-9]{2,4}[0-9]{2,4}[0-9]{3,4}$/", $value)) {
                return true;
            }

            return false;
        });

        Validator::extend('password_character_used', function ($attribute, $value, $parameters, $validator) {
            if (preg_match('/^[A-Za-z\d!#$%&\'()*+\-.\/:;<=>?@^_`{|}~]+$/u', $value)) {
                return true;
            }

            return false;
        });

        Validator::extend('password_rules', function ($attribute, $value, $parameters, $validator) {
            $rules = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$!#$%&'()*+\-.\/:;<=>?@^_`{|}~])[A-Za-z\d$!#$%&'()*+\-.\/:;<=>?@^_`{|}~]+$/u";
            if (preg_match($rules, $value)) {
                return true;
            }

            return false;
        });

        Validator::extend('password_simple_rules', function ($attribute, $value, $parameters, $validator) {
            $rules = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d$!#$%&'()*+\-.\/:;<=>?@^_`{|}~]+$/u";
            if (preg_match($rules, $value)) {
                return true;
            }

            return false;
        });

        Validator::extend('postcode_jp', function ($attribute, $value, $parameters, $validator) {
//            return preg_match("/^[0-9]{3}[\-]{0,1}[0-9]{4}$/", $value);
            return preg_match("/^[0-9]{7}$/", $value);
        });

        Validator::extend('ascii', function ($attribute, $value, $parameters, $validator) {
            return preg_match("/^[[:ascii:]]+$/", $value);
        });

        Validator::extend('time', function ($attribute, $value, $parameters, $validator) {
            return preg_match("/^([0-9]|[0-1][0-9]|2[0-3]):([0-9]|[0-5][0-9])$/", $value);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
