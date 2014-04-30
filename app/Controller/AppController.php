<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $components = array('Session');
	public $uses = array('User', 'Config');

	public $userid    = 0;
	public $username  = 'Guest';
	public $userinfo  = array();
	public $userperms = array();

	public function beforeFilter() {
		$logged_in = false;
		$this->Config->preload();

		if ( $this->Session->check('user.id') ) {
			$userid = $this->Session->read('user.id');

			$user = $this->User->findById($userid);

			if ( !empty($user) ) {
				unset($user['User']['password']); //security yo!

				// Setup permissions
				$permissions = array();
				foreach ( $user['Permission'] AS $perm )
					$permissions[] = $perm['name'];

				$this->userid    = $userid;
				$this->username  = $user['User']['username'];
				$this->userinfo  = $user['User'];
				$this->userperms = $permissions;
				$logged_in       = true;

				if ( !$this->Session->check('user.logout_key') ) {
					$this->Session->write('user.logout_key', substr(sha1(time()+uniqid()+gethostname()), 0, 10));
				}
			} else {
				$this->Session->destroy('user.id');
			}
		}

		// Set template stuff
		$this->set(array(
			'userid'     => $this->userid,
			'username'   => $this->username,
			'userinfo'   => $this->userinfo,
			'userperms'  => $this->userperms,
			'logged_in'  => $logged_in,
			'logout_key' => $this->Session->read('user.logout_key')
		));
	}

	protected function protect($noGuests=false) {
		if ( !$this->Config->get('enable_public', true) ) {
			$this->redirect('/users/login');
		}

		if ( $noGuests && $this->userid == 0 ) {
			throw new ForbiddenException('You have to be logged in to do that.');
		}
	}

	protected function requires($name, $key) {
		if ( !$this->Config->get($key, false) ) {
			throw new InternalErrorException($name.' support is disabled. This page requires '.$name.' support to be enabled.');
		}
	}
}
