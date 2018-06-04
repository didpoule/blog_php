<?php

namespace Blog\Services;

class Form {

	protected $action;

	protected $fields = [];

	private $name;

	private static $metas;

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
}