<?php
/*
 * PlexAuth
 * PHP5 Interface for authenticating with Plex's auth server's.
 *
 * @author James Droste <james@droste.im>
 */

class PlexAuth {
	const API_SERVER     = 'https://plex.tv';
	const LOGIN_ENDPOINT = '/users/sign_in.xml';

	private $defaults    = array(
		'X-Plex-Client-Identifier' => false,
    	'X-Plex-Device'            => false,
    	'X-Plex-Device-Name'       => false,
		'X-Plex-Platform'          => 'web',
		'X-Plex-Platform-Version'  => '1.0',
    	'X-Plex-Product'           => 'PlexAuth',
		'X-Plex-Version'           => '1.0',
	);

	private $options = array();

	public function __construct($options) {
		$this->options = array_merge($this->defaults, $options);
	}

	public function login($username, $password) {
		$auth = array('Authorization' => 'Basic '.base64_encode($username.':'.$password));

		$request = $this->buildRequest(array_merge($this->options, $auth));

		$response = curl_exec($request);

		if ( !empty($response) )
			$response = $this->decode($response);

		curl_close($request);

		return $response;
	}

	private function buildRequest($headers) {
		$this->_scanHeaders($headers);
		$newheaders = array();
		foreach ( $headers AS $key => $value ) {
			$newheaders[] = $key.': '.$value;
		}

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, self::API_SERVER.self::LOGIN_ENDPOINT);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $newheaders);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		return $ch;
	}

	private function _scanHeaders($headers) {
		foreach ( $headers AS $opt => $value ) {
			if ( $value === false ) {
				throw new RuntimeException('PlexAuth fatal error: Option '.$opt.' must be set!');
			}
		}
	}

	private function decode($xml) {
		$simple = simplexml_load_string($xml);
		$info = array();
		$want = array('email', 'username');

		foreach ( $simple->attributes() AS $k => $v ) {
			if ( !in_array($k, $want) ) continue;

			$info[$k] = $v;
		}

		return $info;
	}
}