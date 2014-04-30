<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'php-plex/Plex');

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

		try {
			$movie = $this->plex->getServer('server')->getLibrary()->getSection('Movies')->getMovie('/library/metadata/'.$id);
		} catch ( Exception $e ) {
			throw new NotFoundException('Sorry, I could not find the movie you are looking for.');
		}

		$this->set('movie', $movie);
	}

	public function art($id) {
		if ( !is_numeric($id) ) {
			throw new ForbiddenException('Invalid ID passed');
		}

		$host   = $this->Config->get('plex_host');
		$port   = $this->Config->get('plex_port');

		//$url    = 'http://'.$host.':'.$port.'/photo/:/transcode?url=';
		//$url   .= 'http://127.0.0.1:'.$port.'/library/metadata/'.$id.'/art';
		//$url   .= '&width=960&height=540';
		
		$url    = 'http://'.$host.':'.$port.'/library/metadata/'.$id.'/art';
		$image  = file_get_contents($url);

		$this->response->body($image);
		$this->response->type('image/jpeg');

		return $this->response;
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
