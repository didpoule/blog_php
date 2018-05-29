<?php

namespace Blog\Entity\Comment;


use App\Orm\Entity;
use Blog\Manager\CommentManager;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Comment
 * @package Blog\Entity\Comment
 */
class Comment extends Entity {

	/**
	 * @var array
	 */
	private static $meta;

	/**
	 * @var string
	 */
	private static $name;

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

	public static function getName() {
		if ( is_null( self::$name ) ) {
			self::$name = "comment";
		}

		return self::$name;
	}

	/**
	 * @return array
	 */
	public static function getMeta() {
		if (is_null(self::$meta)) {
			$file = Yaml::parseFile(__DIR__ . '/../entities.yml');
			self::$meta = $file['comment'];
		}
		return self::$meta;
	}

	public static function getManager() {
		return CommentManager::class;
	}

	/**
	 * @param array $meta
	 */
	public static function setMeta( array $meta ): void {
		self::$meta = $meta;
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
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getAuthor(): string {
		return $this->author;
	}

	/**
	 * @return string
	 */
	public function getContent(): string {
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
	public function getUpdated(): \DateTime {
		return $this->updated;
	}

	/**
	 * @return bool
	 */
	public function isPublished(): bool {
		return $this->published;
	}


}