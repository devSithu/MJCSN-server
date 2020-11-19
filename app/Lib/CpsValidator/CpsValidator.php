<?php

namespace App\Lib\CpsValidator;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Support\Arr;

/**
 * Description of CpsValidator
 *
 * @author harunaga
 */
class CpsValidator extends \Illuminate\Validation\Validator
{

    public function validateDistinct($attribute, $value, $parameters)
    {
        if (\in_array('narrow', $parameters)) {
            $path          = $this->getLeadingPartBeforeLastWildCard($attribute);
            $attributeData = \Illuminate\Validation\ValidationData::extractDataFromPath($path, $this->data);
            $pattern       = str_replace('\*', '[^.]+', preg_quote($this->getPrimaryAttribute($attribute), '#'));
            $data          = Arr::where(Arr::dot($attributeData), function ($value, $key) use ($attribute, $pattern) {
                        return $key != $attribute && (bool) preg_match('#^' . $pattern . '\z#u', $key);
                    });
            return !in_array($value, array_values($data));
        }
        return parent::validateDistinct($attribute, $value, $parameters);
    }

    public function getLeadingPartBeforeLastWildCard($attribute)
    {
        $attributeName = $this->getPrimaryAttribute($attribute);
        $left          = rtrim($attribute, @end(explode('*', $attributeName)));
        $exploded      = explode(".", $left);
        array_pop($exploded);
        return implode(".", $exploded);
    }

    public function validateUnique($attribute, $value, $parameters)
    {
        if (isset($this->extensions['unique'])) {
            return $this->callExtension('unique', [$attribute, $value, $parameters, $this]);
        }
        return parent::validateUnique($attribute, $value, $parameters);
    }

}
