<?php

namespace App\Templating;

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

	public function __construct( $file ) {

		$this->name = 'twig';
		$this->file = $file;

		$loader = new \Twig_Loader_Filesystem( $this->file );

		$this->twig = new \Twig_Environment( $loader, [
			'cache' => false
		] );


	}

	public function __call( $name, $args ) {
		call_user_func_array( [ $this->twig, $name ], $args );
	}
}