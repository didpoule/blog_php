<?php

namespace Blog\Manager;

use App\Orm\Manager;

class CategoryManager extends Manager {

	public function findByName($name) {
		return  $this->fetch(["name" => $name]);
	}

}