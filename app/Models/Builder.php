<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;

/**
 * Description of Builder
 *
 * @author truong.nguyen
 */
class Builder extends \Illuminate\Database\Eloquent\Builder
{

    public function update(array $values)
    {
        return parent::update(\Illuminate\Support\Arr::add($values, "updated_by", Auth::guard('admin')->user()->user_id));
    }

    public function delete()
    {
        if ($this->getModel()->usesSoftDeletes()) {
            return parent::delete();
        } else {
            return $this->toBase()->delete();
        }
    }

    public function findOrFail($id, $columns = ['*'])
    {
        if ($id > 2147483647) { //avoid: SQLSTATE[22003]: Numeric value out of range: 7 ERROR
            throw (new ModelNotFoundException)->setModel(
                get_class($this->model), $id
            );
        }
        return parent::findOrFail($id, $columns);
    }

    public function deleteWithTrack()
    {
        if (!$this->getModel()->usesSoftDeletes()) {
            //trail the deleted record by updating the updated_by... in case of not using soft-deletes
            $this->update([$this->getModel()->getDeletedAtColumn() => $this->getModel()->freshTimestampString()]);
            return $this->forceDelete();
        }
        return $this->delete();
    }

}
