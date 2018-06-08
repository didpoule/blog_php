<?php

namespace Blog\Controller;

use App\Controller\Controller;
use Blog\Entity\Category;
use Blog\Entity\Post;

class HomeController extends Controller {

	public function homeAction() {
		$manager = $this->database->getManager( Category::class );

		$editoCat = $manager->findByName( 'edito' );
		$synopsisCat = $manager->findByName( 'synopsis' );

		$manager = $this->database->getManager( Post::class );

		// Récupération de l'edito
		if ( $editoCat ) {
			$edito = $manager->fetch( [ "category" => $editoCat->getId() ] );
		}


		// Récupération du chapitre en cours
		if ( $this->request->getCookie( 'current' ) ) {
			$chapter = $manager->getExtract( [ "number" => $this->request->getCookie( "current" ) ] );
		}

		// Récupération du synopsis
		if ( !isset($chapter) ) {
			$synopsis = $manager->fetch( [ "category" => $synopsisCat->getId() ] );
		}

		$featured = ( isset( $synopsis ) ) ? $synopsis : $chapter;

		return $this->render( 'home/home.html.twig', [
			'edito'    => $edito,
			'featured' => $featured

		] );
	}
}