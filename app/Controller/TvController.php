<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'php-plex/Plex');
App::import('Vendor', 'PlexWatch');
//App::import('Vendor', 'SickBeard');

/**
 * TV Controller
 *
 * @property TV $TV
 * @property SessionComponent $Session
 */
class TvController extends AppController {
	public $components = array('Session');
	public $uses       = array('Config');

	private $plex;

	public function beforeFilter() {
		parent::beforeFilter();

		$this->protect();

		$this->requires('Plex', 'plex_enabled');
	}

	public function index() {
		$this->setupPlex();

		$showSection = $this->plex->getServer('server')->getLibrary()->getSection('TV Shows');

		$showsAll = $showSection->getAllShows();
		$shows    = array();
		
		foreach ( $showsAll AS $i => $show ) {
			$shows[$i] = array(
				'show'    => $show,
				'seasons' => $show->getSeasons(),
			);
		}

		$this->set('title', 'MediaBox - TV Show List');
		$this->set('shows', $shows);
	}

	public function schedule() {
		$this->requires('SickBeard', 'sickbeard_enabled');
	}

	public function view($show=false, $season=false, $episode=false) {
		$this->requires('PlexWatch', 'plexwatch_enabled');
		if ( !is_numeric($show) ) {
			$this->redirect('/tv');
		}

		$this->setupPlex();

		try {
			switch ( true ) {
				case ($episode != false):
					if ( !is_numeric($season) || !is_numeric($episode) ) {
						$this->redirect('/tv');
					}

					$view_type = 'media_history';
					$show = '/library/metadata/'.$show;
					$season = '/library/metadata/'.$season;
					$episode = '/library/metadata/'.$episode;

					// Media information
					$media = $this->plex->getServer('server')->getLibrary()->getSection('TV Shows')->getShow($show)->getSeason($season)->getEpisode($episode);
					$this->set('thumb_link', $media->getGrandparentRatingKey().'/'.$media->getParentRatingKey());

					// plexWatch
					$plexWatch = new PlexWatch($this->Config->get('plexwatch_db'));
					$this->set('history', $plexWatch->getWatchingHistory($episode));
				break;

				case ($season != false):
					if ( !is_numeric($season) ) {
						$this->redirect('/tv');
					}

					$view_type = 'episode_list';
					$showid = $show;
					$show = '/library/metadata/'.$show;
					$season = '/library/metadata/'.$season;

					// Media Information
					$media = $this->plex->getServer('server')->getLibrary()->getSection('TV Shows')->getShow($show);
					$this->set('season', $media->getSeason($season));
					$this->set('thumb_link', $showid);
				break;

				case ($show != false):
					$view_type = 'season_list';
					$show = '/library/metadata/'.$show;

					// Media Information
					$media = $this->plex->getServer('server')->getLibrary()->getSection('TV Shows')->getShow($show);
				break;
			}
		} catch ( Exception $e ) {
			throw new NotFoundException('Sorry, I could not find the TV Show you are looking for. ('.$e->getMessage().')');
		}

		$this->set('title', 'MediaBox - TV Shows - '.$media->getTitle());
		$this->set('media', $media);
		$this->set('view_type', $view_type);

		$this->render('/Media/view');
	}

	public function watch($id=false) {
		$this->protect(true);
		if ( !is_numeric($id) ) {
			$this->redirect('/tv');
		}

		$this->setupPlex();

		$servers = $this->plex->getServer('server')->getClients(Plex_Server::ENDPOINT_SERVER);
		$server  = current($servers);

		$url = sprintf(
				'http://plex.tv/web/?fragment=!/server/%s/details/%slibrary%smetadata%s%s', 
				$server->getMachineIdentifier(), 
				'%252F',
				'%252F',
				'%252F',
				$id
			);

		$this->redirect($url);
	}

	private function setupPlex() {
		$config = array('server' => array(
			'address' => $this->Config->get('plex_host'),
			'port'    => $this->Config->get('plex_port')
		));

		$this->plex = new Plex();
		$this->plex->registerServers($config);
	}
}
