<?php
	namespace polyushina402\hangman\Controller;
	use function polyushina402\hangman\View\showGame;

	function startGame()
	{
		echo "Game started".PHP_EOL;	
		showGame();
		
	}
