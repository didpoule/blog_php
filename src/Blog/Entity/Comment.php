<?php

namespace Blog\Entity;


use App\Orm\Entity;

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
	private $post;

	/**
	 * @return mixed
	 */
	public function getPost() {
		return $this->post;
	}

	/**
	 * @param mixed $post
	 */
	public function setPost( $post ): void {
		$this->post = $post;
	}


	/**
	 * @param int $id
	 */
	public function setId( int $id ): void {
		$this->id = $id;
	}

	/**
	 * @param string $author
	 */
	public function setAuthor( string $author ): void {
		$this->author = $author;
	}

	/**
	 * @param string $content
	 */
	public function setContent( string $content ): void {
		$this->content = $content;
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

	public function jsonSerialize() {
		$formatter = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE);

		$date  =  "Posté le " . $formatter->format($this->added);
		$datas            = [];
		$datas["title"]   = $this->author;
		$datas["date"]    = $date;
		$datas["content"] = $this->content;

		return $datas;
	}

}