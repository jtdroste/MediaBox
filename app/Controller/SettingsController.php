<?php
App::uses('AppController', 'Controller');
/**
 * Settings Controller
 *
 * @property Setting $Setting
 * @property SessionComponent $Session
 */
class SettingsController extends AppController {
	public $components = array('Session');
	public $uses       = array('Config', 'User', 'UserIp', 'Permission');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->protect(true);

		if ( !in_array('Administrator', $this->userperms) ) {
			throw new ForbiddenException('These are not the droids you are looking for.');
		}
	}

	public function index() {
		$this->set('title', 'Settings - Main Configuration');
		$this->set('settings', $this->Config->find('all', array('conditions' => array(
			'Config.name !=' => 'mediabox_setup'
		))));
		$this->set('is_setup', $this->Config->get('mediabox_setup', false));
	}

	public function save() {
		if ( $this->request->is('post') ) {
			$this->layout = 'ajax';

			$id      = $this->request->data['pk'];
			$name    = $this->request->data['name'];
			$value   = $this->request->data['value'];
			$default = uniqid();

			$config = $this->Config->get($name, $default);

			if ( $config === $default ) {
				$this->redirect('/settings', 400);
			}

			$this->Config->id = $id;

			switch ( true ) {
				case ( is_bool($config) ):
					if ( $value != 'true' && $value != 'false' )  {
						$this->set('message', 'Invalid value. Please enter true or false!');
						$this->response->statusCode(400);

						return;
					}
				break;

				case ( is_numeric($config) ):
					if ( !is_numeric($value) ) {
						$this->set('message', 'Invalid value. Please enter a number!');
						$this->response->statusCode(400);

						return;
					}
				break;
			}

			
			$this->Config->saveField('value', $value);
		} else {
			$this->redirect('/settings');
		}
	}

	public function users() {
		$this->set('title', 'Settings - User Accounts');
		$this->set('users', $this->User->find('all'));
		$this->set('is_setup', $this->Config->get('mediabox_setup', false));
	}

	public function user($id=false) {
		if ( !is_numeric($id) || $id == false ) {
			$this->redirect('/settings/users', 400);
		}
		$userinfo = $this->User->findById($id);
		if ( empty($userinfo) ) {
			$this->redirect('/settings/users', 400);
		}

		// Saving
		if ( $this->request->is('post') ) {
			$this->layout = false;
			$this->autoRender = false;

			$field = strtolower($this->request->data['name']);
			$value = $this->request->data['value'];

			if ( in_array($field, array('id')) ) {
				$this->response->body('Invalid field passed.');
				$this->response->statusCode(400);
				
				return $this->response;
			}

			if ( in_array($field, array('privacy_level')) && !is_numeric($value)) {
				$this->response->body('This field has to be a numeric value!');
				$this->response->statusCode(400);
				
				return $this->response;
			}

			if ( $field = 'permissions' ) {
				$this->response->body('ToDo');
				$this->response->statusCode(400);
				
				return $this->response;
			}

			if ( $field == 'password' && !empty($value) ) {
				$value = Security::hash($value, 'blowfish');
			}

			$this->User->id = $id;
			$this->User->saveField($field, $value);

			// Deal with MediaBox setup
			if ( $id == 1 ) {
				$setup = $this->Config->findByName('mediabox_setup');

				if ( !empty($setup) && $setup['Config']['value'] == 'false' ) {
					$this->Config->id = $setup['Config']['id'];
					$this->Config->saveField('value', 'true');
				}
			}

			return;
		}

		// Displaying
		$this->set('title', 'MediaBox - Editing User');
		$this->set('user', $userinfo);
		$this->set('permissions', $this->Permission->find('all'));
	}

	public function logs() {
		$this->set('title', 'Settings - Logs');
	}
}
