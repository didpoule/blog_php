<?php

namespace Blog\Entity;

use App\Orm\Entity;
use Blog\Manager\CommentManager;

/**
 * Class Post
 * @package Blog\Entity
 */
class Post extends Entity {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var \DateTime
	 */
	private $added;

	/**
	 * @var \DateTime
	 */
	private $updated;

	/**
	 * @var boolean
	 */
	private $published;

	/**
	 * @var string
	 */
	private $content;


	/**
	 * @var mixed
	 */
	private $comments = null;

	/**
	 * @var integer
	 */
	private $category;

	/**
	 * @var int
	 */
	private $number;

	/**
	 * @var CommentManager
	 */
	private $commentManager;


	public function __construct( $meta, CommentManager $commentManager = null ) {
		parent::__construct( $meta );

		$this->commentManager = $commentManager;
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
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function setContent( string $content ) {
		$this->content = $content;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ) {
		$this->title = $title;
	}

	/**
	 * @param \DateTime $added
	 */
	public function setAdded( \DateTime $added ) {
		$this->added = $added;
	}

	/**
	 * @param \DateTime $updated
	 */
	public function setUpdated( $updated ) {
		$this->updated = $updated;
	}

	/**
	 * @param bool $published
	 */
	public function setPublished( bool $published ) {
		$this->published = $published;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return \DateTime
	 */
	public function getAdded() {
		return $this->added;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdated() {
		return $this->updated;
	}

	/**
	 * @return bool
	 */
	public function getPublished() {
		return $this->published;
	}

	/**
	 * @return integer
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * @param string $category
	 */
	public function setCategory( int $category ) {
		$this->category = $category;
	}

	/**
	 * @return int
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * @param int $number
	 */
	public function setNumber( int $number ) {
		$this->number = $number;
	}

	private function addComment( $comment ) {
		$this->comments[] = $comment;
	}

	/**
	 * Lazy Loader commentaires
	 * @return mixed
	 */
	public function getComments() {
		if ( empty( $this->comments ) ) {
			$comments = $this->commentManager->findAllByPost( [
				"postId"    => $this->getId(),
				"published" => 1
			], 0, 5 );

			if ( $comments ) {
				foreach ( $comments as $comment ) {
					$this->addComment( $comment );

				}
			}
		}

		return $this->comments;
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