<?php

namespace App\Templating;

use App\Services\MessagesBag;

class Templating {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var \Twig_Environment
	 */
	private $twig;

	/**
	 * Templating constructor.
	 *
	 * @param $file
	 */
	public function __construct( $file, MessagesBag $bag ) {

		$this->name = 'twig';
		$this->file = $file;

		$loader = new \Twig_Loader_Filesystem( $this->file );

		$this->twig = new \Twig_Environment( $loader, [
			'cache' => false
		] );

		$this->twig->addExtension( new \Twig_Extensions_Extension_Intl() );
		$this->twig->addGlobal( "bag", $bag );
	}

	/**
	 * Appel du load de twig
	 *
	 * @param $name
	 * @param $args
	 *
	 * @return mixed
	 */
	public function __call( $name, $args ) {
		return call_user_func_array( [ $this->twig, $name ], $args );
	}
}