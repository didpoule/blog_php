<?php

namespace Blog\Entity;

use App\Orm\Entity;

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
	 * @var array
	 */
	private $comments;

	/**
	 * @var integer
	 */
	private $category;

	/**
	 * @var int
	 */
	private $number;

	/**
	 * @param int $id
	 */
	public function setId( int $id ): void {
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
	public function setContent( string $content ): void {
		$this->content = $content;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ): void {
		$this->title = $title;
	}

	/**
	 * @param \DateTime $added
	 */
	public function setAdded( \DateTime $added ): void {
		$this->added = $added;
	}

	/**
	 * @param \DateTime $updated
	 */
	public function setUpdated( $updated ): void {
		$this->updated = $updated;
	}

	/**
	 * @param bool $published
	 */
	public function setPublished( bool $published ): void {
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
	 * @return array
	 */
	public function getComments() {
		return $this->comments;
	}

	/**
	 * @param array $comments
	 */
	public function setComments( array $comments ): void {
		$this->comments = $comments;
	}

	/**
	 * @param Comment $comment
	 */
	public function addComment( Comment $comment ) {
		$this->comments[] = $comment;
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
	public function setCategory( int $category ): void {
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
	public function setNumber( int $number ): void {
		$this->number = $number;
	}

}