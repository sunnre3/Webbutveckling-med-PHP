<?php

namespace model;

require_once("model/GameRules.php");
require_once("model/StickSelection.php");
require_once("model/AIPlayer.php");
require_once("model/PersistantSticks.php");

class LastStickGame {
	const StartingNumberOfSticks = 22;
	
	/**
	 * Message coming from AIPlayer
	 * @var string
	 */
	public $message = "";

	public function __construct(AIPlayer $ai) {
		$this->ai = $ai;
		$this->sticks = new PersistantSticks(GameRules::StartingNumberOfSticks);
	}

	public function playerSelectsSticks(StickSelection $playerSelection, StickGameObserver $observer) {
		$this->sticks->removeSticks($playerSelection);

		if ($this->isGameOver()) {
			$observer->playerWins();
		} else {
			$this->AIPlayerTurn($observer);
		} 
	}	

	private function AIPlayerTurn(StickGameObserver $observer) {
		$sticksLeft = $this->getNumberOfSticks();
		$selection = $this->ai->getSelection($sticksLeft);
		
		$this->sticks->removeSticks($selection);
		$observer->aiRemoved($selection);

		if ($this->isGameOver()) {
			$observer->playerLoose();
		}
	}

	/** 
	* @return boolean
	*/
	public function isGameOver() {
		return $this->sticks->getNumberOfSticks() < 2;
	}

	/** 
	* @return int
	*/
	public function getNumberOfSticks() {
		return $this->sticks->getNumberOfSticks();
	}

	public function newGame() {
		$this->sticks->newGame(GameRules::StartingNumberOfSticks);
	}
}