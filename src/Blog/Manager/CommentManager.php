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

	public function __construct( Database $database, $entity, $meta ) {
		parent::__construct( $database, $entity, $meta );
	}

	public function findLasts( $limit = null ) {
		return $this->fetchAll( null, null, $limit, [ 'added' => "DESC" ] );
	}

	public function findAllByPost( $params = [], $offset = null, $limit = null ) {
		return $this->fetchAll( $params, $offset, $limit, [ "added" => "DESC" ] );
	}

	public function countComments( $params = [] ) {
		$request   = sprintf( "SELECT COUNT(*) AS count FROM comment %s", $this->where( $params ) );
		$statement = $this->pdo->prepare( $request );

		$statement->execute($params);

		return $statement->fetch()[0];
	}

	/**
	 * @return Comment
	 */
	public function getNew() {
		return new $this->entity($this->meta, $this->database->getManager(Post::class));
	}
}