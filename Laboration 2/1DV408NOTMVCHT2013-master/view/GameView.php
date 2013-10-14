<?php

namespace view;

require_once("model/GameRules.php");
require_once("model/StickGameObserver.php");

class GameView implements \model\StickGameObserver {
	/** 
	* @var integer
	*/
	private $numberOfSticksAIDrewLastTime = 0;

	/** 
	* @var boolean
	*/
	private $playerWon = false;

	public function playerWins() {
		$this->playerWon = true;
	}
	public function playerLoose() {
		$this->playerWon = false;
	}

	/**
	 * Sets the number of sticks the AI player did
	 * @param  modelStickSelection $sticks 
	 */
	public function aiRemoved(\model\StickSelection $sticks) {
		$this->numberOfSticksAIDrewLastTime = $sticks->getAmount();
	}

	/**
	 * @param modelLastStickGame $game 
	 */
	public function __construct(\model\LastStickGame $game) {
		$this->game = $game;
	}

	

	/** 
	* @return String HTML
	*/
	public function show($message, $errorMessage) {
		if ($this->game->isGameOver()) {

			return 	"<h1>" . $errorMessage . "</h1>" .
					"<p>" . $message . "</p>" .
					$this->showSticks() . 
					$this->showWinner() . 
					$this->startOver();
		} else {
			return 	"<h1>" . $errorMessage . "</h1>" .
					"<p>" . $message . "</p>" .
					$this->showSticks() . 
					$this->showSelection();
		}
	}



	/** 
	* @return boolean
	*/
	public function playerSelectSticks() {
		return isset($_GET["draw"]);
	}

	/** 
	* @return boolean
	*/
	public function playerStartsOver() {
		return isset($_GET["startOver"]);
	}

	/**
	 * @return string
	 */
	public function playerDrawsNumberOfSticks() {
		return $_GET["draw"];
	}

	/** 
	* @return String HTML
	*/
	private function showSticks() {
		$numSticks = $this->game->getNumberOfSticks();
		$aiDrew = $this->numberOfSticksAIDrewLastTime;

		$opponentsMove = "";
		if ($aiDrew > 0) {
			$opponentsMove = "Your opponent drew $aiDrew stick". ($aiDrew > 1 ? "s" : "");
		}
		//Make a visualisation of the sticks 
		$sticks = "";
		for ($i = 0; $i < $numSticks; $i++) {
			$sticks .= "I"; //Sticks remaining
		}
		for (; $i < $aiDrew + $numSticks; $i++) {
			$sticks .= "."; //Sticks taken by opponent
		}
		for (; $i < \model\GameRules::StartingNumberOfSticks; $i++) {
			$sticks .= "_"; //old sticks
		}
		return "<p>There is $numSticks stick" . ($numSticks > 1 ? "s" : "") ." left</p>
				<p style='font-family: \"Courier New\", Courier, monospace'>$sticks</p>
				<p>$opponentsMove</p>";
	}

	/** 
	* @return String HTML
	*/
	private function showSelection() {
		
		$numSticks = $this->game->getNumberOfSticks();

		$ret = "<h2>Select number of sticks</h2>
				<p>The player who draws the last stick looses</p>";
		$ret .= "<ol>";
		for ($i = 1; $i <= 3 && $i < $numSticks; $i++ ) {

			$ret .= "<li><a href='?draw=$i'>Draw $i stick". ($i > 1 ? "s" : ""). "</a></li>";
		}
		$ret .= "<ol>";

		return $ret;
	}

	/** 
	* @return String HTML
	*/
	private function showWinner() {
		if ($this->playerWon) {
			return "<h2>Congratulations</h2>
					<p>You force the opponent to draw the last stick!</p>";
		} else {
			return "<h2>Epic FAIL!</h2>
					<p>You have to draw the last stick</p>";
		}
	}

	/** 
	* @return String HTML
	*/
	private function startOver() {

		return "<a href='?startOver'>Start new game</a>";
		
	}
}