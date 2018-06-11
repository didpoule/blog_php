<?php

namespace Blog\Services;

use App\Http\Request\Request;
use Symfony\Component\Yaml\Yaml;

class Form {

	/**
	 * @var string
	 */
	protected static $action;

	/**
	 * @var array
	 */
	protected static $fields = [];

	/**
	 * @var array
	 */
	private static $forms = [];

	/**
	 * @var string
	 */
	protected static $entity;

	/**
	 * @var array
	 */
	private static $metas;

	/**
	 * @var string
	 */
	protected static $name;

	/**
	 * @var Request
	 */
	protected static $request;


	public function __construct( $metas, Request $request ) {
		self::$metas   = Yaml::parseFile( $metas );
		self::$request = $request;

	}

	/**
	 * @param $entity string className entity
	 */
	public function sendForm( $entity = null ) {
		if ( self::$request->getPost( 'token' ) === self::$request->getToken() ) {

			$entity = $this->hydrate( $entity );

			$errors = $entity->validate();

			if ( ! is_array( $errors ) ) {

				return $entity;
			}

			return $errors;
		}

		return false;
	}

	/**
	 * @param Request $this ->request
	 */
	private function hydrate( $entity = null ) {
		foreach ( self::$metas[ static::$entity ]['columns'] as $property => $params ) {

			if ( array_key_exists( $property, self::$request->getPost() ) ) {

				$datas[ $property ] = self::$request->getPost( $property );
			} else {
				if ( $params['type'] === "boolean" ) {
					$datas[ $property ] = false;
				} else {
					$datas[ $property ] = null;

				}
			}

		}
		if ( ! isset( $entity ) ) {
			$entity = new static::$entity();
		}

		$entity->hydrate( $datas );

		return $entity;

	}

	public function getForm() {
		$content = sprintf( "<form action='%s' method='post' id='%s-form'>", static::$action, static::$name );
		foreach ( static::$fields as $field => $params ) {
			$content .= "<div class='group-form'>";
			if ( $params['type'] != 'hidden' && isset( $params['label'] ) ) {
				$content .= sprintf( "<label for='%s'>%s :</label>", $field, $params['label'] );
			}
			switch ( $params['type'] ) {
				case "text" :
					$content .= sprintf( "<input type='text' name='%s' id='%s' value=\"%s\"/>", $field, $field,
						isset ( $params['value'] ) ? $params['value'] : null );
					break;
				case "password" :
					$content .= sprintf( "<input type='password' name='%s' id='%s' />", $field, $field );
					break;
				case "textarea" :
					$content .= sprintf( "<textarea id='%s' name='%s'>%s</textarea>", $field, $field,
						isset ( $params['value'] ) ? $params['value'] : null );
					break;
				case "boolean" :
					$content .= sprintf( "<input type='checkbox' id='%s' name='%s' %s />", $field, $field,
						( ( $params['value'] === false ) ? null : 'checked' ) );
					break;
				case "date" :
					$content .= sprintf( "<input type='date' id='%s' name='%s' />", $field, $field );
					break;
				case "number" :
					$content .= sprintf( "<input type='number' id='%s' name='%s'  value=\"%s\"/>", $field, $field,
						isset ( $params['value'] ) ? $params['value'] : null );
					break;
				case "hidden" :
					$content .= sprintf( "<input type='hidden' id='%s' name='%s' value=\"%s\" />", $field, $field, $params['value'] );
					break;
			}
			$content .= "</div>";
			$content .= sprintf( "<input type='hidden' id='token' name='token' value='%s' />", self::$request->getToken() );
		}
		$content .= sprintf( "<input type='submit' value='Envoyer' class='btn btn-primary'/>" );
		$content .= "</form>";

		return $content;
	}

	public function get( $formClass, $args = [] ) {
		if ( ! array_key_exists( $formClass, self::$forms ) ) {

			$reflect = new \ReflectionClass( $formClass );

			self::$forms[ $formClass ] = $reflect->newInstanceArgs( $args );
		}


		return self::$forms[ $formClass ];
	}
}