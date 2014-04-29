<?php
App::uses('AppModel', 'Model');

class User extends AppModel {
	public $name = 'User';

	public $hasAndBelongsToMany = array(
		'Permission' => array(
			'className'  => 'Permission',
			'joinTable'  => 'user_permission',
			'foreignKey' => 'permission_id',
			'associationForeignKey' => 'user_id'
		),
	);

	public function beforeSave($options = array()) {
		if ( !empty($this->data['User']['password']) ) {
			$this->data['User']['password'] = Security::hash($this->data['User']['password'], 'blowfish');
		}
	}
}