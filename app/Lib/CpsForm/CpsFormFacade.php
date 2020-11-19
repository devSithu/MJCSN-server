<?php
/**
 * Created by PhpStorm.
 * User: yujiro.takezawa
 * Date: 2016/01/20
 * Time: 20:05
 */
namespace App\Lib\CpsForm;

use Illuminate\Support\Facades\Facade;

class CpsFormFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cpsformfacade';
    }
}
