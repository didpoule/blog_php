<?php

namespace Blog\Services;

use App\Http\Request\Request;
use App\Orm\Entity;
use App\Orm\Manager;
use Symfony\Component\Yaml\Yaml;

class Form {

	/**
	 * @var string
	 */
	protected $action;

	/**
	 * @var array
	 */
	protected $fields = [];

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

	public function __construct( $metas ) {
		self::$metas = Yaml::parseFile( $metas );
	}

	/**
	 * @param $entity string className entity
	 */
	public function sendForm( Request $request ) {
		if ( $request->getPost( 'token' ) === $request->getToken() ) {

			$entity = $this->hydrate( $request );

			$errors = $entity->validate();

			if ( ! is_array( $errors ) ) {
				return $entity;
			}

			return $errors;
		}

		return false;
	}

	/**
	 * @param Request $request
	 */
	private function hydrate( Request $request ) {
		foreach ( self::$metas[ static::$entity ]['columns'] as $property => $params ) {

			if ( $params['required'] ) {
				if ( array_key_exists( $property, $request->getPost() ) ) {
					$datas[ $property ] = $request->getPost( $property );
				} else {
					$datas[ $property ] = null;
				}
			}

		}

		$entity = new static::$entity();

		$entity->hydrate( $datas );

		return $entity;

	}

	public function getForm() {
		$content = sprintf( "<form action='%s' method='post'>", $this->action );
		foreach ( $this->fields as $field => $params ) {
			if ( $params['type'] != 'hidden' ) {
				$content .= sprintf( "<label for='%s'>%s :</label>", $field, $params['label'] );
			}
			switch ( $params['type'] ) {
				case "text" :
					$content .= sprintf( "<input type='text' name='%s' id='%s'/>", $field, $field );
					break;
				case "textarea" :
					$content .= sprintf( "<textarea id='%s' name='%s'></textarea>", $field, $field );
					break;
				case "boolean" :
					$content .= sprintf( "<input type='checkbox' id='%s' name='%s' />", $field, $field );
					break;
				case "date" :
					$content .= sprintf( "<input type='date' id='%s' name='%s' />", $field, $field );
					break;
				case "hidden" :
					$content .= sprintf( "<input type='hidden' id='%s' name='%s' value='%s'/>", $field, $field, $params['value'] );
			}
		}
		$content .= sprintf( "<input type='submit' value='Envoyer' class='btn btn-primary'/>" );
		$content .= "</form>";

		return $content;
	}

	public function get( $formClass ) {
		if ( ! array_key_exists( $formClass, self::$forms ) ) {

			self::$forms[ $formClass ] = new $formClass();
		}

		return self::$forms[ $formClass ];
	}
}