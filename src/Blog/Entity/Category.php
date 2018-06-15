<?php

namespace Blog\Entity;

use App\Orm\Entity;
use Blog\Manager\PostManager;

class Category extends Entity {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var array
	 */
	private $posts = null;

	/**
	 * @var PostManager
	 */
	private $postManager;

	/**
	 * Category constructor.
	 *
	 * @param $meta
	 * @param PostManager|null $postManager
	 */
	public function __construct( $meta, PostManager $postManager = null ) {
		parent::__construct( $meta );
		$this->postManager = $postManager;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId( int $id ) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ) {
		$this->name = $name;
	}

	/**
	 * Lazy Loader posts
	 */
	public function getPosts() {
		if ( empty( $this->posts ) ) {
			$this->posts = $this->postManager->fetchAll( [ "category" => $this->id ] );
		}

		return $this->posts;
	}

	/**
	 * @param $keys
	 *
	 * @return mixed
	 */
	public
	function __get(
		$key
	) {
		if ( ! is_callable( $this->$key ) ) {

			$method = "get" . ucfirst( $key );
			$this->$method();
		}

		return $this->$key;
	}
}