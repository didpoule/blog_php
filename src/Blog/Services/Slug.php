<?php

namespace Blog\Services;

/**
 * Class Slug
 * @package Blog\Services
 */
class Slug {

	/**
	 * Remplace les espaces par des tirets
	 *
	 * @param $string
	 */
	public function slugify( $string ) {
		return sprintf( "%s", str_replace( ' ', '-', $string ) );
	}

	/**
	 * Retourne vraie si la chaîne ne contient pas d'espaces
	 *
	 * @param $string
	 */
	public function isSlug( $string ) {
		return (!strstr( $string, " " ) && strlen($string) > 1 );
	}
}