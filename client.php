<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Project 3 Jeopardy Client</title>
		<link href="clientStyles.css" rel="stylesheet" type="text/css">
		<link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=ABeeZee&amp;effect=shadow-multiple">	
	</head>
	<body>
	
		<div id="container">
			<div id="navigation">
				<a href="http://vision.main.ad.rit.edu/~cdt5799/431/Project3/login.php" class='link'>Login</a>
				<a href="http://vision.main.ad.rit.edu/~cdt5799/431/Project3/admin.php" class='link'>Admin</a>
				<a href="http://vision.main.ad.rit.edu/~cdt5799/431/Project3/showallquestions.php" class='link'>ALL The Questions</a>
				<a href="http://vision.main.ad.rit.edu/~cdt5799/431/Project3/getround.php" class='link'>XML</a>
			</div> 
			
			<h1 class="font-effect-shadow-multiple">Jeopardy</h1>
					
			<?php
				$html="";
				
				session_start();
				if (array_key_exists('submit_name',$_POST) && array_key_exists('username',$_POST) && strlen($_POST['username']) > 0){
					//$username=trim(htmlspecialchars($_POST['username']));
					$_SESSION['username'] = trim(htmlspecialchars($_POST['username']));
					
					$html .= "<div id='nameOutput'>";
					$html .= 'Hello, ' . $_SESSION['username'] . '!';
					$html .= "    <a href ='clientLogout.php'>Not you?</a>";
					$html .= "</div>";	
				}else{
					$html .= "<div id='nameInput'>";
					$html .= "<form id='nameForm' name='nameForm' method='POST' action='client.php'>";
					$html .= "<label for='username'>What's your name?</label>";
					$html .= "<input type='text' name='username' id='username' /><input type='submit' name='submit_name' value='Submit'/>";
					$html .= "</form>";
					$html .= "</div>";	
				}
				
				$html .= "<div id='changeRound'>";
				$html .= "<form id='roundForm' name='roundForm' action='client.php' method='POST'>";
				$html .= "<label for='roundSelect'>Select Round: </label>";
				$html .= "<select id='roundID' name='roundID'>";
				$html .= "<option value='0'>Select Round</option>";
				$html .= "<option value='1'>Round 1</option>";
				$html .= "<option value='2'>Round 2</option>";
				$html .= "</select>";
				$html .= "<input type='submit' name='submit_round' value='Submit'/>";
				$html .= "</form>";
				$html .= "</div>";
				if(array_key_exists("submit_round", $_POST) && array_key_exists("roundID", $_POST)){
					if($_POST['roundID'] == 1){
						$round = 'http://vision.main.ad.rit.edu/~cdt5799/431/Project3/getround.php?roundID=1';
						$roundNumber= "You selected round 1!";
					}
					else if($_POST['roundID'] == 2){
						$round = 'http://vision.main.ad.rit.edu/~cdt5799/431/Project3/getround.php?roundID=2';
						$roundNumber = "You selected round 2!";
					} 
				}
				else{
					$round = 'http://vision.main.ad.rit.edu/~cdt5799/431/Project3/getround.php?roundID=1';
					$roundNumber ="You selected round 1!";
				}
				
			
				$dom = new DomDocument();
				$dom->load($round);
				$root = $dom->documentElement;
				$roundID = $root->getAttribute('roundID');
				$roundName = $root->getAttribute('roundName');
				$roundTitle = $root->getAttribute('roundTitle');
				
				$all_questions = $dom->getElementsByTagName('question');
				$length = $all_questions->length;
				
				// group questions by category - the value was already sorted for us by the web service
				$grid = array();
				foreach ($all_questions as $question){
					$categoryTitle = $question->getAttribute('categoryTitle');
					$grid[$categoryTitle][]= $question;
				}
				
		
				// start <table>
				$html .= "\n<table>\n";
				
				$html .= "\t<tr>\n\t\t<td id='roundNumber' colspan='5'>$roundNumber</td>\n\t</tr>\n";

				// 1st row - title of round
				$html .= "\t<tr>\n\t\t<td id='roundTitle' colspan='5'>Round: $roundTitle</td>\n\t</tr>\n";
				
				// 2nd row - category names
				$html .= "\t<tr id='categoryNames'>\n";
				foreach ($grid as $categoryKey=>$categoryArray){
					$html .= "\t\t<td>$categoryKey</td>\n";
				}
				$html .= "\t</tr>\n";
				
				// 3rd - 8th rows - questions
				// $i is row number
				for($i = 0; $i < 5; $i++){
					$html .= "\t<tr>\n\t";
					foreach ($grid as $categoryKey=>$categoryArray){
						$question = $categoryArray[$i];
						$categoryName = $question->getAttribute('categoryName');
						$questionID = $question->getAttribute('ID');
						$value = trim($question->getAttribute('value'));
						$categoryID = $question->getAttribute('categoryID');
						
						$categoryTitle = $question->getAttribute('categoryTitle');
						$q = trim($question->getElementsByTagName('q')->item(0)->nodeValue);
						$a = $question->getElementsByTagName('a')->item(0)->nodeValue;
				
						$html .= "\t<td class='question'><p class='q'>$q</p><p class='v'>$value</p><p class='a'>$a</p></td>\n\t";
					}
					$html .= "</tr>\n";
				}
				
				// 9th row - final question
				$finalQuestion = $dom->getElementsByTagName('final_question')->item(0)->getElementsByTagName("q")->item(0)->nodeValue;
				$finalAnswer = $dom->getElementsByTagName('final_question')->item(0)->getElementsByTagName("a")->item(0)->nodeValue;
				$html .= "\t<tr>\n\t\t<td colspan='5' class='finalquestion'><p class='q'>Final Question: $finalQuestion</p><p class='a'>$finalAnswer</p></td>\n\t</tr>\n";
				
				// end <table>
				$html .= "</table>\n";
				
				echo $html;
			?>
		</div>
		<div id="footer">
				<p>&copy;Design by Chelsea Triebwasser</p>
		</div>
	</body>
</html>
