<?php

namespace Blog\Services;

use App\Http\Request\Request;

/**
 * Class ReadChapters
 * @package Blog\Services
 */
class ReadChapters {

	/**
	 * @var int
	 */
	private $current;

	/**
	 * @var int
	 */
	private $qt;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var string
	 */
	private $state;

	/**
	 * @param Request $request
	 * @param $qt
	 */
	public function init( Request $request, int $qt ) {

		$this->request = $request;
		$this->qt      = $qt;
		$this->current = $this->getCookie();

		if ( $this->request->getPost( "next" ) ) {
			$this->state = $this->nextChapter() ? "next" : null;
		}

		if ( $this->request->getPost( "previous" ) ) {
			$this->state = $this->previousChapter() ? "previous" : null;
		}

		if ( $this->current === false ) {
			$this->current = 1;
			$this->updateCookie();
		}

	}

	/**
	 * @return int
	 */
	public function getCurrent() {
		return $this->current;
	}

	/**
	 * @param int $current
	 */
	public function setCurrent( int $current ): void {
		$this->current = $current;
	}

	/**
	 * Revient au chapitre précédent
	 */
	public function previousChapter() {
		if ( $this->current - 1 < 1 ) {
			$this->current = 1;
		} else {
			$this->current --;
			$this->updateCookie();

			return true;
		}

		return false;
	}

	/**
	 * Passe au chapitre suivant
	 */
	public function nextChapter() {
		if ( $this->current < $this->qt ) {
			$this->current ++;
			$this->updateCookie();

			return true;
		} else {
			$this->current = $this->qt;
		}

		return false;
	}

	/**
	 * Met à jour le cookie
	 */
	private function updateCookie() {
		$this->request->setCookie( "current", $this->current, 30 );
	}

	/**
	 * Récupère le cookie
	 * @return mixed
	 */
	public function getCookie() {
		return $this->request->getcookie( "current" );
	}

	/**
	 *
	 * @return string
	 */
	public function getState() {
		return $this->state;
	}
}