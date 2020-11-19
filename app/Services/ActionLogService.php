<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Contracts\Dao\ActionLogDaoInterface;
use App\Contracts\Dao\CommunityDaoInterface;
use App\Contracts\Services\ActionLogServiceInterface;

class ActionLogService implements ActionLogServiceInterface
{
    private $actionLogDao,$communityDao;

    /**
     * Class Constructor
     * @param actionLogDaoInterface
     * @return
     */
    public function __construct(ActionLogDaoInterface $actionLogDao,CommunityDaoInterface $communityDao)
    {
        $this->actionLogDao = $actionLogDao;
        $this->communityDao = $communityDao;
    }

   /**
     * create header data
     *
     * $data
     * @return void
     */
    public function createHeaderData($data)
    {
        $changeData = $this->changeID($data);
        $this->actionLogDao->createHeaderData($changeData);
    }

     /**
     * change user ID
     *
     * @param $data
     * @return void
     */
    public function changeID($data)
    {
     
      $user_number = $this->communityDao->changeUserNumber($data['user_number']);
     
      $resultData = [
        'user_number' => $user_number[0]['user_number'],
        'action' => $data['action'],
        'action_at' => $data['action_at'],
        'parameter' => $data['parameter'],
        'point' =>  $data['point'],
        'os' => $data['os'],
        'os_version' => $data['os_version'],
        'app' => $data['app'],
        'app_version' => $data['app_version'],
        ];
      return $resultData;
    }

}
