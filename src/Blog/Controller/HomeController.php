<?php

namespace Blog\Controller;

use App\Controller\Controller;
use Blog\Entity\Category;
use Blog\Entity\Post;

class HomeController extends Controller {

	public function homeAction() {
		$manager = $this->database->getManager(Category::class);

		$editoCat = $manager->findByName('edito');
		$synopsisCat = $manager->findByName('synopsis');

		$manager = $this->database->getManager(Post::class);

		if ($editoCat) {
			$edito = $manager->fetch(["category" => $editoCat->getId()]);
		}

		if ($synopsisCat) {
			$synopsis = $manager->fetch(["category" => $synopsisCat->getId()]);
		}

		return $this->render( 'home/home.html.twig', [
			'edito' => $edito,
			'synopsis' => $synopsis

		] );
	}
}