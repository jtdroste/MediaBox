<?php 
App::uses('ClassRegistry', 'Utility');

class AppSchema extends CakeSchema {

	/*
	 * Setting cacheSources to false was needed, otherwise this whole thing would fail. Boohoo.
	 */
	public function before($event = array()) {
		ConnectionManager::getDataSource('default')->cacheSources = false;

		return true;
	}

	/*
	 * Fill in some nice default data
	 */
	public function after($event = array()) {
		if (isset($event['create'])) {
			switch ($event['create']) {
				case 'configs':
					// SickBeard Config
					$this->_create('Config', array('name' => 'sickbeard_enabled', 'value' => 'false', 'description' => 'Enable SickBeard Support?'));
					$this->_create('Config', array('name' => 'sickbeard_host', 'value' => '127.0.0.1', 'description' => 'What is SickBeard\'s host? (Usually 127.0.0.1)'));
					$this->_create('Config', array('name' => 'sickbeard_port', 'value' => '8081', 'description' => 'What port is SickBeard running on? (Usually 8081)'));
					$this->_create('Config', array('name' => 'sickbeard_apikey', 'value' => 'false', 'description' => 'What is SickBeard\'s API Key? You can find it in the settings.'));

					// CouchPotato Config
					$this->_create('Config', array('name' => 'couchpotato_enabled', 'value' => 'false', 'description' => 'Enable CouchPotato Support?'));
					$this->_create('Config', array('name' => 'couchpotato_host', 'value' => '127.0.0.1', 'description' => 'What is CouchPotato\'s host? (Usually 127.0.0.1)'));
					$this->_create('Config', array('name' => 'couchpotato_port', 'value' => '5050', 'description' => 'What port is CouchPotato running on? (Usually 5050)'));
					$this->_create('Config', array('name' => 'couchpotato_apikey', 'value' => 'false', 'description' => 'What is CouchPotato\'s API Key? You can find it in the settings.'));

					// Plex Config
					$this->_create('Config', array('name' => 'plex_enabled', 'value' => 'false', 'description' => 'Enable Plex Support?'));
					$this->_create('Config', array('name' => 'plex_host', 'value' => '127.0.0.1', 'description' => 'What is Plex\'s host?'));
					$this->_create('Config', array('name' => 'plex_port', 'value' => '32400', 'description' => 'What port is Plex running on? (Usually 32400)'));

					// plexWatch Config
					$this->_create('Config', array('name' => 'plexwatch_enabled', 'value' => 'false', 'description' => 'Enable PlexWatch Support?'));
					$this->_create('Config', array('name' => 'plexwatch_db', 'value' => '/opt/plexWatch/plexWatch.db', 'description' => 'Where is the PlexWatch Database? (Usually /opt/plexWatch/plexWatch.db)'));

					// MediaBox Config
					$this->_create('Config', array('name' => 'mediabox_setup', 'value' => 'false', 'description' => 'Ignore this ;-)'));
					$this->_create('Config', array('name' => 'enable_public', 'value' => 'true', 'description' => 'Enable public viewing of the MediaBox? You will still need to log in to request media. (Default: Yes)'));
					$this->_create('Config', array('name' => 'enable_stats', 'value' => 'true', 'description' => 'Enable stats for logged in users? (Default: Yes)'));
					$this->_create('Config', array('name' => 'enable_public_stats', 'value' => 'true', 'description' => 'Enable public stats? Note enable_public must be enabled for this to work. (Default: Yes)'));
					$this->_create('Config', array('name' => 'cache_images', 'value' => 'true', 'description' => 'Cache images from Plex? (Default: Yes)'));
					$this->_create('Config', array('name' => 'requests_enabled', 'value' => 'true', 'description' => 'Enable the Requests system? (Default: Yes)'));
				break;

				case 'users':
					$this->_create('User', array(
						'username' => 'admin',
						'password' => 'admin'
					));
				break;

				case 'permissions':
					$this->_create('Permission', array(
						'name' => 'Administrator'
					));
					$this->_create('Permission', array(
						'name' => 'Super User'
					));
					$this->_create('Permission', array(
						'name' => 'User'
					));
				break;

				case 'user_permission':
					ConnectionManager::getDataSource('default')->query('INSERT INTO user_permission(user_id,permission_id) VALUES(1,1)');
				break;

				case 'users_ips':
					$this->_create('UserIP', array(
						'user_id' => 1,
						'time'    => time(),
						'ip'      => '127.0.0.1',
					));
				break;
			}
		}
	}

	/*
	 * Helper method to make it easier to insert data. Yay no copy & pasting!
	 */
	private function _create($tbl, $data) {
		$table = ClassRegistry::init($tbl);

		$table->create();
		$table->save(array($tbl => $data));
	}

	public $configs = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'text', 'null' => false),
		'value' => array('type' => 'text', 'null' => true),
		'description' => array('type' => 'text', 'null' => true),
		'indexes' => array(
			
		),
		'tableParameters' => array()
	);

	public $permissions = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'text', 'null' => false),
		'indexes' => array(
			
		),
		'tableParameters' => array()
	);

	public $requests = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'text', 'null' => false),
		'media_id' => array('type' => 'integer', 'null' => false),
		'season' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'integer', 'null' => false),
		'type' => array('type' => 'integer', 'null' => false),
		'author' => array('type' => 'integer', 'null' => false),
		'ip' => array('type' => 'text', 'null' => false),
		'approved' => array('type' => 'integer', 'null' => false),
		'indexes' => array(
			
		),
		'tableParameters' => array()
	);

	public $server_histories = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'time' => array('type' => 'integer', 'null' => true),
		'disk' => array('type' => 'text', 'null' => true),
		'uptime' => array('type' => 'integer', 'null' => true),
		'movie_count' => array('type' => 'integer', 'null' => true),
		'tv_count' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			
		),
		'tableParameters' => array()
	);

	public $user_permission = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false),
		'permission_id' => array('type' => 'integer', 'null' => false),
		'indexes' => array(
			
		),
		'tableParameters' => array()
	);

	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'username' => array('type' => 'text', 'null' => false),
		'password' => array('type' => 'text', 'null' => true),
		'email' => array('type' => 'text', 'null' => true),
		'last_seen' => array('type' => 'integer', 'null' => true),
		'privacy_level' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'text', 'null' => true),
		'public_name' => array('type' => 'text', 'null' => true),
		'indexes' => array(
			
		),
		'tableParameters' => array()
	);

	public $users_ips = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false),
		'time' => array('type' => 'integer', 'null' => false),
		'ip' => array('type' => 'text', 'null' => false),
		'indexes' => array(
			
		),
		'tableParameters' => array()
	);

}
