<?php

namespace App\Models;

use App\Exceptions\NotPrimaryDeleteException;
use App\Exceptions\NotPrimaryUpdateException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Auth;

/**
 * App\Models\BaseModel
 *
 * @property-read mixed $created_at
 * @property-read mixed $id
 * @mixin \Eloquent
 */
class BaseModel extends Model
{

    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $softDeletes = false;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if ($this->primaryKey === 'id') {
            $this->primaryKey = $this->getForeignKey();
        }
        if (!$this->softDeletes) {
            $this->forceDeleting = true;
        }
    }

    public static function boot()
    {
        static::creating(function ($model) {
            if (!isset($model->is_set_created_by)) {
                $model->created_by = Auth::guard('admin')->user()->user_id ?? 1;
            }
            unset($model->is_set_created_by);
            if (!isset($model->is_set_updated_by)) {
                $model->updated_by = Auth::guard('admin')->user()->user_id ?? 1;
            }
            unset($model->is_set_updated_by);
        });

        parent::boot();
    }

    public static function allArrayByPrimary()
    {
        $model = new static;

        return static::get()
            ->keyBy($model->getKeyName());
    }

    /**
     * @param $where
     * @param $params
     *
     * @return
     * @throws \Exception
     */
    public static function updateByPrimary($where, $params)
    {
        $model = new static;
        $count = $model->where($where)
            ->update($params);

        if ($count != 1) {
            throw new NotPrimaryUpdateException("Updated count is not 1");
        }

        return $count;
    }

    /**
     * @param $where
     *
     * @return mixed
     * @throws NotPrimaryDeleteException
     */
    public static function deleteByPrimary($where)
    {
        $model = new static;
        $count = $model->where($where)
            ->delete();

        if ($count != 1) {
            throw new NotPrimaryDeleteException("Deleted count is not 1");
        }

        return $count;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function getCreatedAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return date("Y/m/d H:i", strtotime($value));
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function getIdAttribute($value)
    {
        if (empty($value)) {
            return $this->getKey();
        }

        return $value;
    }

    /**
     * @param $value
     */
    public function setUpdatedByAttribute($value)
    {
        $this->is_set_updated_by = true;
        $this->attributes['updated_by'] = $value;
    }

    /**
     * @param $value
     */
    public function setCreatedByAttribute($value)
    {
        $this->is_set_created_by = true;
        $this->attributes['created_by'] = $value;
    }

    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    public function usesSoftDeletes()
    {
        return $this->softDeletes;
    }

    public function deleteWithTrack()
    {
        if (!$this->usesSoftDeletes() && $this->exists) {
            //trail the deleted record by updating the updated_by... in case of not using soft-deletes
            $this->forceFill([$this->getDeletedAtColumn() => $this->freshTimestampString()])->save();
        }
        return $this->delete();
    }

    public function getForeignKey()
    {
        return Str::snake(class_basename($this)) . '_id';
    }

    public function belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
    {
        // If no relation name was given, we will use this debug backtrace to extract
        // the calling method's name and use that as the relationship name as most
        // of the time this will be what we desire to use for the relationships.
        if (is_null($relation)) {
            $relation = $this->guessBelongsToRelation();
        }

        $instance = $this->newRelatedInstance($related);

        // If no foreign key was supplied, we can use a backtrace to guess the proper
        // foreign key name by using the name of the relationship function, which
        // when combined with an "_id" should conventionally match the columns.
        if (is_null($foreignKey)) {
            $foreignKey = Str::snake($relation) . '_id';
        }

        // Once we have the foreign key names, we'll just create a new Eloquent query
        // for the related models and returns the relationship instance which will
        // actually be responsible for retrieving and hydrating every relations.
        $ownerKey = $ownerKey ?: $instance->getKeyName();

        return new \Illuminate\Database\Eloquent\Relations\BelongsTo(
            $instance->newQuery(), $this, $foreignKey, $ownerKey, $relation
        );
    }

    public static function collect(array $models = [])
    {
        return (new static )->newCollection($models);
    }

}
