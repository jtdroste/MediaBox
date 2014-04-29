<?php
App::uses('AppModel', 'Model');

class Permission extends AppModel {
	public $name = 'Permission';

	public $hasAndBelongsToMany = array(
		'User' => array(
			'className'  => 'User',
			'joinTable'  => 'user_permission',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'permission_id'
		),
	);
}