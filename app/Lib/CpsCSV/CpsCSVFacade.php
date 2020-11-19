<?php
/**
 * Created by PhpStorm.
 * User: yujiro.takezawa
 * Date: 2016/01/20
 * Time: 20:05
 */
namespace App\Lib\CpsCSV;

use Illuminate\Support\Facades\Facade;

class CpsCSVFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cpscsvfacade';
    }
}
