<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'php-plex/Plex');
App::import('Vendor', 'PlexWatch');

/**
 * Movies Controller
 *
 * @property Movie $Movie
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class MoviesController extends AppController {
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

		$this->set('movies', $this->plex->getServer('server')->getLibrary()->getSection('Movies')->getAllMovies());
	}

	public function schedule() {
		$this->requires('CouchPotato', 'couchpotato_enabled');
	}

	public function upcoming() {
		$this->requires('CouchPotato', 'couchpotato_enabled');
	}

	public function view($id=false) {
		$this->requires('PlexWatch', 'plexwatch_enabled');
		if ( !is_numeric($id) ) {
			$this->redirect('/movies');
		}

		$this->setupPlex();
		$plexWatch = new PlexWatch($this->Config->get('plexwatch_db'));

		try {
			$movie = $this->plex->getServer('server')->getLibrary()->getSection('Movies')->getMovie('/library/metadata/'.$id);
		} catch ( Exception $e ) {
			throw new NotFoundException('Sorry, I could not find the movie you are looking for.');
		}

		$this->set('media', $movie);
		$this->set('history', $plexWatch->getWatchingHistory($id));

		$this->render('/Media/view');
	}

	public function watch($id=false) {
		$this->protect(true);
		if ( !is_numeric($id) ) {
			$this->redirect('/movies');
		}
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
