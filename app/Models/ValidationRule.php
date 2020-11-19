<?php

namespace App\Models;

class ValidationRule extends BaseModel
{
    //
    const RULE_REQUIRED = [
        'validation_rule_id' => 1,
        'view_name' => "必須",
        'rule_name' => 'required',
    ];
    const RULE_UNIQUE = [
        'validation_rule_id' => 4,
        'view_name' => "重複チェック",
        'rule_name' => 'unique',
    ];
    const RULE_MIN = [
        'validation_rule_id' => 8,
        'view_name' => "最小",
        'rule_name' => 'min',
    ];
    const RULE_MAX = [
        'validation_rule_id' => 9,
        'view_name' => "最大",
        'rule_name' => 'max',
    ];
    const RULE_SIZE_LENGTH = [
        'validation_rule_id' => 10,
        'view_name' => "桁数",
        'rule_name' => 'size_length',
    ];
    const RULE_MIN_LENGTH = [
        'validation_rule_id' => 12,
        'view_name' => "最小桁数",
        'rule_name' => 'min_length',
    ];
    const RULE_MAX_LENGTH = [
        'validation_rule_id' => 13,
        'view_name' => "最大桁数",
        'rule_name' => 'max_length',
    ];
    //------------------    DATA FORMAT   ---------------------------
    const RULE_STRING = [
        'validation_rule_id' => 2,
        'view_name' => "文字列",
        'rule_name' => 'string',
        'is_data_format' => true,
        'order' => 1,
    ];
    const RULE_EMAIL = [
        'validation_rule_id' => 3,
        'view_name' => "メールアドレス",
        'rule_name' => 'email',
        'is_data_format' => true,
        'order' => 2,
    ];
    const RULE_TEL = [
        'validation_rule_id' => 5,
        'view_name' => "電話番号",
        'rule_name' => 'tel',
        'is_data_format' => true,
        'order' => 3,
    ];
    const RULE_POST_NUMBER = [
        'validation_rule_id' => 6,
        'view_name' => "郵便番号",
        'rule_name' => 'postcode_jp',
        'is_data_format' => true,
        'order' => 4,
    ];
    const RULE_ALPHA_NUMBERIC = [
        'validation_rule_id' => 7,
        'view_name' => "英数字",
        'rule_name' => 'ascii',
        'is_data_format' => true,
        'order' => 5,
    ];
    const RULE_NUMBERIC = [
        'validation_rule_id' => 11,
        'view_name' => "数字",
        'rule_name' => 'numeric',
        'is_data_format' => true,
        'order' => 6,
    ];

    private static $required_data;
    protected $primaryKey = 'validation_rule_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['validation_rule_id',
        'view_name',
        'rule_name'];

    public static function getRequired()
    {
        if (empty(self::$required_data)) {
            self::$required_data = self::where("view_name", "=", "必須")->first();
        }

        return self::$required_data;
    }

    public static function getUnique()
    {
        return self::where("view_name", "=", "重複チェック")->first();
    }

    public static function getDataFormats()
    {
        return static::all()->where("is_data_format", true)->sortBy('order')->values();
    }

    public static function getAlphaNum()
    {
        return self::where("view_name", "=", "英数字")->first();
    }

    public static function getMin()
    {
        return self::where("view_name", "=", "最小")->first();
    }

    public static function getSizeLength()
    {
        return self::where("view_name", "=", "桁数")
            ->first();
    }

    public static function getMinLength()
    {
        return self::where("view_name", "=", "最小桁数")->first();
    }

    public static function getMaxLength()
    {
        return self::where("view_name", "=", "最大桁数")->first();
    }

    public function isRequired()
    {
        $required = self::getRequired();

        return $this->validation_rule_id == $required->validation_rule_id;
    }

    public static function all($columns = [])
    {
        return collect((new \ReflectionClass(static::class))->getConstants())->where(with(new static )->getKeyName(), '<>', null)->map(function ($item) {
            return (new static )->setRawAttributes($item);
        })->values();
    }

    public static function find($id, $columns = array())
    {
        return static::all()->where('id', $id)->first();
    }

    public static function dataFormatName($type)
    {
        return static::getDataFormats()->where('rule_name', $type)->first()->view_name ?? 'unknown';
    }

    public static function dataFormat($type)
    {
        return static::getDataFormats()->where('rule_name', $type)->first()->id;
    }

    public static function rule($type)
    {
        return static::all()->where('rule_name', $type)->first()->id;
    }

}
