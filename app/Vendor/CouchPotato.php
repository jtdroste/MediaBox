<?php
/*
 * CouchPotato
 * PHP5 Interface for interfacing with CouchPotato.
 *
 * @author James Droste <james@droste.im>
 */

class CouchPotato {
	private $host = null;
	private $port = null;
	private $key  = null;

	public function __construct($key, $host='127.0.0.1', $port=5050) {
		$this->host = $host;
		$this->port = $port;
		$this->key  = $key;
	}

	public function __call($name, $args) {
		$name = str_replace('_', '.', $name);

		$url = sprintf(
				'http://%s:%s/api/%s/%s?%s',
				$this->host,
				$this->port,
				$this->key,
				$name,
				http_build_query($args)
			);
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($ch);

		if ( !empty($response) )
			$response = json_decode($response, true);

		curl_close($ch);

		return $response;
	}
}