<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'php-plex/Plex');
App::import('Vendor', 'PlexWatch');
App::import('Vendor', 'CouchPotato');

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

		$this->set('title', 'MediaBox - Movie List');
		$this->set('movies', $this->plex->getServer('server')->getLibrary()->getSection('Movies')->getAllMovies());
	}

	public function upcoming() {
		$this->requires('CouchPotato', 'couchpotato_enabled');
		$host = $this->Config->get('couchpotato_host');
		$port = $this->Config->get('couchpotato_port');
		$key  = $this->Config->get('couchpotato_apikey');

		$couchpotato = new CouchPotato($key, $host, $port);

		$soonArr = $couchpotato->dashboard_soon();
		$soon = array();

		foreach ( $soonArr['movies'] AS $data ) {
			$library = $data['library']['info'];
			$title   = $library['titles'][0];
			$plot    = $data['library']['plot'];
			$rating  = $library['mpaa'];
			$runtime = $library['runtime'];
			$release = isset($library['release_date']['dvd']) ? $library['release_date']['dvd'] : 0;
			$poster  = $library['images']['poster_original'][0];
			$rated   = isset($library['rating']['imdb'][0]) ? $library['rating']['imdb'][0] : -1;
			$imdb    = $library['imdb'];

			if ( $release <= 0 ) continue; // Incorrect data was fetched

			$soon[] = array(
				'title'   => $title,
				'plot'    => $plot,
				'rating'  => $rating,
				'runtime' => $runtime,
				'release' => $release,
				'poster'  => $poster,
				'rated'   => $rated,
				'imdb'    => $imdb,
			);
		}

		$sort = function($a, $b) {
			return $a['release'] - $b['release'];
		};
		usort($soon, $sort);

		$this->set('title', 'MediaBox - Movies - Coming Soon');
		$this->set('soon', $soon);
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

		$this->set('title', 'MediaBox - Movie - '.$movie->getTitle());
		$this->set('media', $movie);
		$this->set('view_type', 'history');
		$this->set('history', $plexWatch->getWatchingHistory($id));

		$this->render('/Media/view');
	}

	public function watch($id=false) {
		$this->protect(true);
		if ( !is_numeric($id) ) {
			$this->redirect('/movies');
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
