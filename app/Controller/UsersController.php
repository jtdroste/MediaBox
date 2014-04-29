<?php
App::uses('AppController', 'Controller');
App::uses('PlexAuth', 'Vendor');

/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class UsersController extends AppController {
	public $components = array('Session');
	public $uses       = array('User', 'UserIp');

	public function index() {
		$this->protect(true);
	}

	public function login() {
		if ( $this->userid != 0 ) {
			$this->redirect('/');
		}

		// Login attempt
		if ( $this->request->is('post') ) {
			$username = isset($this->request->data['username']) ? $this->request->data['username'] : null;
			$password = isset($this->request->data['password']) ? $this->request->data['password'] : null;

			if ( !empty($username) && !empty($password) ) {
				// Determine what auth we will be using
				$user = $this->User->findByUsername($username);

				if ( !empty($user) ) {
					$plexAuth = empty($user['User']['password']);

					if ( $plexAuth ) {
						$PlexAuth = new PlexAuth(array(
							'X-Plex-Client-Identifier' => uniqid('mediabox-'),
							'X-Plex-Device'            => 'mediabox',
							'X-Plex-Device-Name'       => 'MediaBox Server Auth',
							'X-Plex-Product'           => 'MediaBox/Auth (PlexAuth)'
						));
						$userinfo = $PlexAuth->login($username, $password);

						if ( $userinfo !== false && $userinfo['username'] == $username ) {
							$this->Session->write('user.id', $user['User']['id']);
							$this->Session->setFlash('Successful login! Welcome '.$userinfo['username'].' !');
							$this->_logIP($user['User']['id']);

							$this->redirect('/');
						}
					} else {
						$pass = Security::hash($password, 'blowfish', $user['User']['password']);

						if ( $user['User']['password'] === $pass ) {
							switch ( true ) {
								case (!empty($user['User']['name'])): $name = $user['User']['name']; break;
								case (!empty($user['User']['public_name'])): $name = $user['User']['public_name']; break;
								default: $name = $user['User']['username']; break;
							}

							$this->Session->write('user.id', $user['User']['id']);
							$this->Session->setFlash('Successful login! Welcome '.$name.' !');
							$this->_logIP($user['User']['id']);

							$this->redirect('/');
						}
					}
				}

				$this->set('failed_login', true);
			}

			$this->set('username', $username);
		}

		$this->set('title', 'MediaBox - Login');
	}

	public function logout($key=false) {
		$this->protect(true);
		
		if ( $key != false && $key === $this->Session->read('user.logout_key') ) {
			$this->Session->destroy('user.id');
			$this->Session->destroy('user.logout_key');
			$this->Session->setFlash('Goodbye!');
		}

		$this->redirect('/');
	}

	private function _logIP($id) {
		$this->UserIp->create();
		$this->UserIp->save(array('UserIp' => array(
			'user_id' => $id,
			'time'    => time(),
			'ip'      => $this->request->clientIp(),
		)));
	}
}
