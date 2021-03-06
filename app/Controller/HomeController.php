<?php
App::uses('AppController', 'Controller');

App::import('Vendor', 'php-plex/Plex');
App::import('Vendor', 'PlexWatch');

/**
 * Home Controller
 *
 * @property Home $Home
 * @property SessionComponent $Session
 */
class HomeController extends AppController {
	public $components = array('Session');
	public $uses       = array('User', 'Config');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->protect();
	}

	public function index() {
		$this->set('title', 'MediaBox - Home');

		if ( !$this->Config->get('mediabox_setup', false) ) {
			$this->set('setup_mediabox_warning', true);
		}

		if ( $this->Config->get('plex_enabled', false) ) {
			$host   = $this->Config->get('plex_host');
			$port   = $this->Config->get('plex_port');
			$config = array('server' => array(
				'address' => $host,
				'port'    => $port
			));

			$plex = new Plex();
			$plex->registerServers($config);

			$this->set('latest_media', $plex->getServer('server')->getLibrary()->getRecentlyAddedItems());
		}

		if ( $this->Config->get('plexwatch_enabled', false) && isset($plex) ) {
			$plexWatch = new PlexWatch($this->Config->get('plexwatch_db'));
			$recentW   = $plexWatch->recentlyWatched(10);

			foreach ( $recentW AS $i => $recent ) {
				if ( $recent['type'] == 'movie' ) {
					$section = $plex->getServer('server')->getLibrary()->getSection('Movies');
					$item    = $section->getMovie($recent['ratingKey']);
				} else {
					$section = $plex->getServer('server')->getLibrary()->getSection('TV Shows');
					$item    = $section->getEpisode($recent['ratingKey']);
				}

				$recentW[$i]['item'] = $item;
			}
			$this->set('recently_watched', $recentW);
		}
	}

	public function plex_proxy($type=false, $id=false) {
		$this->requires('Plex', 'plex_enabled');
		
		if ( !is_numeric($id) || $type == false ) {
			throw new ForbiddenException('Invalid ID passed');
		}

		$host = $this->Config->get('plex_host');
		$port = $this->Config->get('plex_port');

		$url  = '/library/metadata/'.$id.'/'.$type;

		if ( $type == 'thumb' ) {
			$url = '/photo/:/transcode?url=http://127.0.0.1:'.$port.$url.'&width=200&height=300';
		}

		$url  = 'http://'.$host.':'.$port.$url;
		$image  = file_get_contents($url);

		$this->response->body($image);
		$this->response->type('image/jpeg');

		return $this->response;
	}
}
