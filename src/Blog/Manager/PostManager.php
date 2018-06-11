<?php

namespace Blog\Manager;

use App\Orm\Database;
use App\Orm\Manager;

/**
 * Class PostManager
 * @package Blog\Manager
 */
class PostManager extends Manager {

	/**
	 * PostManager constructor.
	 *
	 * @param $entity
	 * @param $meta
	 */
	public function __construct( Database $database, $entity, $meta ) {
		parent::__construct( $database, $entity, $meta );
	}

	public function findChapters( $params = [], $limit = null ) {

		return $this->fetchAll( $params, null, $limit, [ 'number' => "ASC" ] );
	}

	public function find( $params = [] ) {
		return $this->fetch( $params );
	}

	public function getNbChapters() {
		$request   = "SELECT COUNT(*) AS count FROM post WHERE number IS NOT NULL";
		$statement = $this->pdo->prepare( $request );
		$statement->execute();

		return $statement->fetch()[0];
	}

	public function getExtract( $params = [] ) {
		$post = $this->fetch( $params );
		if ( ! $post ) {
			return false;
		}
		$extract = ( substr( $post->getContent(), 0, 500 ) );

		$text = explode( ' ', $extract );
		$text = implode( ' ', array_slice( $text, 0, - 1 ) ) . "...";


		$post->setContent( $text );

		return $post;
	}

	public function getChaptersTitles() {
		$request = "SELECT title, id FROM post";

		$statement = $this->pdo->prepare($request);

		$statement->execute();

		$results = $statement->fetchAll(\PDO::FETCH_ASSOC);

		$titles = [];
		foreach($results as $result) {
			$titles[$result["id"]] = $result['title'];
		}
		return $titles;
	}
}