<?php

namespace Blog\Entity\Post;

use App\Orm\Entity;
use Blog\Manager\PostManager;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Post
 * @package Blog\Entity
 */
class Post extends Entity {

	/**
	 * @var array
	 */
	private static $meta;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $slug;

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
	 * @return array
	 */
	public static function getMeta() {
		if ( is_null( self::$meta ) ) {
			$file       = Yaml::parseFile( __DIR__ .'/../entities.yml' );
			self::$meta = $file['post'];
		}

		return self::$meta;
	}

	/**
	 * @return string
	 */
	public static function getManager() {
		return PostManager::class;
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
	 * @return string
	 */
	public function getContent(): string {
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
	 * @param string $slug
	 */
	public function setSlug( string $slug ): void {
		$this->slug = $slug;
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
	public function setUpdated( \DateTime $updated ): void {
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
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getSlug(): string {
		return $this->slug;
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