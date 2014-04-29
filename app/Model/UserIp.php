<?php
App::uses('AppModel', 'Model');

class UserIp extends AppModel {
	public $name = 'UserIP';
	public $useTable = 'users_ips';

	public $hasOne = array('User');
}