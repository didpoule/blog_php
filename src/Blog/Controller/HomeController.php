<?php

namespace Blog\Controller;

use App\Controller\Controller;
use Blog\Entity\Category;
use Blog\Entity\Post;

class HomeController extends Controller {

	public function homeAction() {
		$manager = $this->database->getManager( Category::class );
		/**
		 * $editoCat = $manager->findByName( 'edito' );
		 * $synopsisCat = $manager->findByName( 'synopsis' );
		 *
		 * $manager = $this->database->getManager( Post::class );
		 **/


		$edito    = $manager->findByName( 'edito' );
		$synopsis = $manager->findByName( 'synopsis' );


		// Récupération du chapitre en cours
		if ( $this->request->getCookie( 'current' ) ) {
			$chapter = $this->database->getManager( Post::class )->getExtract( [ "number" => $this->request->getCookie( "current" ) ] );
		}

		$featured = ( isset( $synopsis ) ) ? $synopsis->posts[0] : $chapter;

		return $this->render( 'home/home.html.twig', [
			'edito'    => $edito->posts[0],
			'featured' => $featured
		] );
	}
}