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
	public $uses = array('User', 'Config');

	public function index() {
		$this->protect();
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

	public function plex_proxy($id1=false, $id2=false) {
		$this->protect();
		if ( !$this->Config->get('plex_enabled', false) ) {
			throw new ForbiddenException('Plex is disabled');
		}
		if ( !is_numeric($id1) || !is_numeric($id2) ) {
			throw new ForbiddenException('Invalid ID passed');
		}

		$host   = $this->Config->get('plex_host');
		$port   = $this->Config->get('plex_port');

		$url    = 'http://'.$host.':'.$port.'/photo/:/transcode?url=';
		$url   .= 'http://127.0.0.1:'.$port.'/library/metadata/'.$id1.'/thumb/'.$id2;
		$url   .= '&width=200&height=300';
		$image  = file_get_contents($url);

		$this->response->body($image);
		$this->response->type('image/jpeg');

		return $this->response;
	}
}
