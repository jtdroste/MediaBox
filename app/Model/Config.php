<?php
App::uses('AppModel', 'Model');
/**
 * Config Model
 *
 */
class Config extends AppModel {
	public $name = 'Config';
	private $_c  = array();

	public function get($key, $default=null) {
		if ( isset($this->_c[$key]) ) {
			return $this->_c[$key];
		}

		$value = $this->findByName($key);

		if ( empty($value) ) {
			return $default;
		}
		$value = $this->_convert($value['Config']['value']);

		$this->_c[$key] = $value;
		return $value;
	}

	public function preload() {
		foreach ( $this->find('all') AS $config ) {
			$key = $config['Config']['name'];
			$val = $config['Config']['value'];

			$this->_c[$key] = $this->_convert($val);
		}
	}

	private function _convert($value) {
		switch ( true ) {
			case ( $value == 'true' || $value == 'false' ):
				$value = ($value == 'true') ? true : false;
			break;

			case ( is_numeric($value) ):
				$value = (int) $value;
			break;
		}

		return $value;
	}
}
