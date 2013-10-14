<?php

namespace controller;

require_once("model/AIPlayer.php");
require_once("model/LastStickGame.php");
require_once("view/GameView.php");

class PlayGame {

	/**
	 * @var \model\AIPlayer
	 */
	private $ai;

	/**
	 * @var \model\LastStickGame
	 */
	private $game;

	/**
	 * @var \view\GameView
	 */
	private $view;

	/**
	 * @var string
	 */
	private $errorMessage = "";

	/**
	 * @var string
	 */
	private $message = "";


	public function __construct() {
		$this->ai = new \model\AIPlayer();
		$this->game = new \model\LastStickGame($this->ai);
		$this->view = new \view\GameView($this->game);
	}

	/**
	* @return String HTML
	*/
	public function runGame() {
		//Handle input
		if ($this->game->isGameOver()) {
			$this->doGameOver();
		} else {
			$this->playGame();
		}

		//Generate Output
		return $this->view->show($this->message, $this->errorMessage);
	}

	/**
	* Called when game is still running
	*/
	private function playGame() {
		if ($this->view->playerSelectSticks()) {
			try {
				$sticksDrawnByPlayer = $this->getNumberOfSticks();
				$this->game->playerSelectsSticks($sticksDrawnByPlayer, $this->view);

				$this->message = $this->ai->message;
			} catch(\Exception $e) {
				$this->errorMessage = "Unauthorized input";
			}
		}
	}

	private function doGameOver() {
		if ($this->view->playerStartsOver()) {
			$this->game->newGame();
		}		
	}

	/** 
	* @return \model\StickSelection
	*/
	private function getNumberOfSticks() {
		switch ($this->view->playerDrawsNumberOfSticks()) {
			case 1 : return \model\StickSelection::One(); break;
			case 2 : return \model\StickSelection::Two(); break;
			case 3 : return \model\StickSelection::Three(); break;
		}
		throw new \Exception("Invalid input");
	}
}