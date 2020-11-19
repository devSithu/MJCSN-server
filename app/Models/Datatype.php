<?php

namespace App\Models;

/**
 * App\Models\DataType
 *
 * @property integer        $data_type_id
 * @property string         $name
 * @property string         $input_type
 * @property integer        $created_by
 * @property integer        $updated_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string         $deleted_at
 * @property-read mixed     $id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DataType whereDataTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DataType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DataType whereInputType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DataType whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DataType whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DataType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DataType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DataType whereDeletedAt($value)
 * @mixin \Eloquent
 */
class DataType extends BaseModel
{
    const TYPE_TEXT = [
        'data_type_id' => 1,
        'name' => 'テキスト（1行）',
        'input_type' => 'text',
        'is_freeanswer' => true,
        'order' => 1,
        'is_view' => true,
    ];
    const TYPE_TEXT_AREA = [
        'data_type_id' => 2,
        'name' => 'テキストエリア（複数行）',
        'input_type' => 'textarea',
        'is_freeanswer' => true,
        'order' => 2,
        'is_view' => true,
    ];
    const TYPE_SELECT = [
        'data_type_id' => 3,
        'name' => 'プルダウン（単数回答）',
        'input_type' => 'select',
        'is_freeanswer' => false,
        'order' => 3,
        'is_view' => true,
    ];
    const TYPE_RADIO = [
        'data_type_id' => 4,
        'name' => 'ラジオボタン（単数回答）',
        'input_type' => 'radio',
        'is_freeanswer' => false,
        'order' => 4,
        'is_view' => true,
    ];
    const TYPE_CHECKBOX = [
        'data_type_id' => 5,
        'name' => 'チェックボックス（複数回答）',
        'input_type' => 'checkbox',
        'is_freeanswer' => false,
        'order' => 5,
        'is_view' => true,
    ];
    const TYPE_PASSWORD = [
        'data_type_id' => 6,
        'name' => 'パスワード',
        'input_type' => 'password',
        'is_freeanswer' => false,
        'order' => 6,
        'is_view' => false,
    ];
    const TYPE_NAME = [
        'data_type_id' => 7,
        'name' => '氏名',
        'input_type' => 'name',
        'is_freeanswer' => false,
        'order' => 7,
        'is_view' => false,
    ];

    /**
     * @param $data_type_id
     * @return bool
     */
    public static function isChoiceType($data_type_id)
    {
        return in_array($data_type_id, [3, 4, 5]);
    }

    public static function getForForm()
    {
        return static::all()->where("is_view", true);
    }

    public static function all($columns = [])
    {
        return collect((new \ReflectionClass(static::class))->getConstants())->where('data_type_id', '<>', null)->map(function ($item) {
            return (new static )->setRawAttributes($item);
        })->values();
    }

    public static function find($id, $columns = array())
    {
        return static::all()->where('id', $id)->first();
    }

    public static function type($type)
    {
        return static::all()->where('input_type', $type)->first()->data_type_id;
    }

    public static function name($type)
    {
        return static::all()->where('input_type', $type)->first()->name;
    }

}
