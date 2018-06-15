<?php

namespace Blog\Entity;


use App\Orm\Entity;
use Blog\Manager\PostManager;

/**
 * Class Comment
 * @package Blog\Entity\Comment
 */
class Comment extends Entity implements \JsonSerializable {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $author;

	/**
	 * @var string
	 */
	private $content;

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
	 * @var int
	 */

	private $postId;

	/**
	 * @var Post
	 */
	private $post = null;

	/**
	 * @var PostManager
	 */
	private $postManager;

	/**
	 * Comment constructor.
	 *
	 * @param $meta
	 * @param PostManager $postManager
	 */
	public function __construct( $meta, PostManager $postManager ) {
		parent::__construct( $meta );
		$this->postManager = $postManager;
	}


	/**
	 * @return mixed
	 */
	public function getPostId() {
		return $this->postId;
	}

	/**
	 * @param mixed $postId
	 */
	public function setPostId( $postId ) {
		$this->postId = $postId;
	}


	/**
	 * @param int $id
	 */
	public function setId( int $id ) {
		$this->id = $id;
	}

	/**
	 * @param string $author
	 */
	public function setAuthor( string $author ) {
		$this->author = $author;
	}

	/**
	 * @param string $content
	 */
	public function setContent( string $content ) {
		$this->content = $content;
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
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @return \DateTime
	 */
	public function getAdded(): \DateTime {
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
	 * Formate l'entité en json
	 * @return array|mixed
	 */
	public function jsonSerialize() {
		$formatter = new \IntlDateFormatter( 'fr_FR', \IntlDateFormatter::LONG, \IntlDateFormatter::SHORT );

		$date             = "Posté le " . $formatter->format( $this->added );
		$datas            = [];
		$datas["title"]   = $this->author;
		$datas["date"]    = $date;
		$datas["content"] = $this->content;

		return $datas;
	}

	/**
	 *
	 */
	public function getPost() {
		if ( empty( $this->post ) ) {
			$this->post = $this->postManager->find( [ "id" => $this->postId ] );
		}

		return $this->post;
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function __get( $key ) {
		if ( ! is_callable( $this->$key ) ) {

			$method = "get" . ucfirst( $key );
			$this->$method();
		}

		return $this->$key;
	}


}