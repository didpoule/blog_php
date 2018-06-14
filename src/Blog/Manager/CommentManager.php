<?php

namespace Blog\Manager;

use App\Orm\Database;
use App\Orm\Manager;
use Blog\Entity\Comment;
use Blog\Entity\Post;

/**
 * Class CommentManager
 * @package Blog\Manager
 */
class CommentManager extends Manager {

	/**
	 * CommentManager constructor.
	 *
	 * @param Database $database
	 * @param $entity
	 * @param $meta
	 */
	public function __construct( Database $database, $entity, $meta ) {
		parent::__construct( $database, $entity, $meta );
	}

	/**
	 * Retourne les derniers commentaires
	 *
	 * @param null $limit
	 *
	 * @return array|bool
	 */
	public function findLasts( $limit = null ) {
		return $this->fetchAll( null, null, $limit, [ 'added' => "DESC" ] );
	}

	/**
	 * Retourne les commentaires d'un Post
	 *
	 * @param array $params
	 * @param null $offset
	 * @param null $limit
	 *
	 * @return array|bool
	 */
	public function findAllByPost( $params = [], $offset = null, $limit = null ) {
		return $this->fetchAll( $params, $offset, $limit, [ "added" => "DESC" ] );
	}

	/**
	 * Retourne le nombre de commentaires
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function countComments( $params = [] ) {
		$request   = sprintf( "SELECT COUNT(*) AS count FROM comment %s", $this->where( $params ) );
		$statement = $this->pdo->prepare( $request );

		$statement->execute( $params );

		return $statement->fetch()[0];
	}

	/**
	 * @return Comment
	 */
	public function getNew() {
		return new $this->entity( $this->meta, $this->database->getManager( Post::class ) );
	}
}