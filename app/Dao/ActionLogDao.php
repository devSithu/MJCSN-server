<?php

namespace App\Dao;

use App\Models\ActionLog;
use App\Contracts\Dao\ActionLogDaoInterface;


class ActionLogDao implements ActionLogDaoInterface
{
     /**
     * create header data
     *
     * @param $data
     * @return void
     */
    public function createHeaderData($changeData)
    {
        ActionLog::create($changeData);
    }

}
