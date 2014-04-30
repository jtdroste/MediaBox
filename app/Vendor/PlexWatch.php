<?php
/*
 * PlexWatch
 * PHP5 Interface for interfacing with PlexWatch.
 *
 * @author James Droste <james@droste.im>
 */

class PlexWatch {
	private $db;

	public function __construct($db) {
		if ( !file_exists($db) ) {
			throw new RuntimeException('PlexWatch database file does not exist! Verify '.$db.' exists!');
		}
		if ( !class_exists('SQLite3') ) {
			throw new RuntimeException('PlexWatch requires the php extension "php-sqlite3" to be installed!');
		}

		$this->db = new SQLite3($db, SQLITE3_OPEN_READONLY);
	}

	public function recentlyWatched($limit=10) {
		$return = array();

		// Query shamelessly borrowed form plexWatch/Web - thank you! 
		$query = $this->db->query("
			SELECT 
				title, user, platform, time, stopped, ip_address, ratingKey, parentRatingKey, grandparentRatingKey, season, 
				xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date 
			FROM 
				processed 
			WHERE 
				stopped IS NULL 
			UNION ALL 
				SELECT 
					title, user, platform, time, stopped, ip_address, ratingKey, parentRatingKey, grandparentRatingKey, season, 
					xml, paused_counter, strftime('%Y%m%d', datetime(time, 'unixepoch', 'localtime')) as date 
				FROM 
					grouped 
			ORDER BY 
				time DESC 
			LIMIT ".(int) $limit);

		while ($row = $query->fetchArray()) {
			if ( empty($row['season']) ) {
				$row['type'] = 'movie';
			} else {
				$row['type'] = 'tv';
			}
			unset($row['season']);

			$return[] = $row;
		}

		return $return;
	}

	public function getWatchingHistory($id) {
		$query = $this->db->prepare('SELECT * FROM grouped WHERE ratingKey = :id ORDER BY time DESC');
		$query->bindValue(':id', $id, SQLITE3_INTEGER);

		$result = $query->execute();
		$return = array();

		while ( $row = $result->fetchArray() ) {
			$return[] = $row;
		}

		return $return;
	}
}