<?php

namespace Blog\Manager;

use App\Orm\Manager;
use Blog\Entity\Category;
use Blog\Entity\Post;

class CategoryManager extends Manager {

	/**
	 * Récupère une catégorie par son nom
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
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