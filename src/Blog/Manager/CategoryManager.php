<?php

namespace Blog\Manager;

use App\Orm\Manager;
use Blog\Entity\Category;
use Blog\Entity\Post;

class CategoryManager extends Manager {

	public function findByName($name) {
		return  $this->fetch(["name" => $name]);
	}

	/**
	 * @return Category
	 */
	public function getNew() {
		return new $this->entity($this->meta, $this->database->getManager(Post::class));
	}
}