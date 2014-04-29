<?php
App::uses('AppModel', 'Model');

class Request extends AppModel {
	public $name = 'Request';

	public $hasOne = array('User');
}