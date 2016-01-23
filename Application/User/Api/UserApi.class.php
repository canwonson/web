<?php
namespace User\Api;
use User\Model\UserModel;
use User\Api\Api;

class UserApi extends Api
{
	protected function _init(){
		$this->model = new UserModel();
	}

	/**
	 * 登陆
	 */
	public function login($account, $password, $type = 1){
        return $this->model->login($account, $password, $type);
    }
}
?>