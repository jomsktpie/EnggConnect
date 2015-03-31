<?php 
	require_once 'DBhandle.php';
	$issue = getTopic($_GET['t_id']);
	$question = getQuestion($issue['topic_id']);
	$answers = getAnswers($question['q_id']);
	$posts = getPostsWithComments($question['q_id']);
	//echo count($posts);

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$p_ans = clean_up($_POST['answer']);
		$p_exp = clean_up($_POST['explination']);
		post($p_ans, $question['q_id'], $p_exp);
		$echos = $_GET['t_id'];
		header("Location: https://localhost/project/issue.php?t_id=$echos");
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $issue['title'];?> | Eng'g Connect</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php include "header.php"; ?>

	<div id="main-block">
		<div id="main-content">
			<div id="topic-content">
				<div id="topic-header">
					<h2><?php echo $issue['title']; ?></h2>
					<p>Posted on <?php echo $issue['date_of_post'];?> by <?php echo getUsername($issue['poster_id'])?></p>
				</div>
				<div id="topic-details">
					<p><?php echo $issue['details']?></p>
				</div>
			</div>
			<div id="topic-question">
				<h3>Question:</h3>
				<p>
					<?php
						if(!$question) echo "there is no question for this topic";
						else echo $question['question'];
					?>
				<p>
				<ul class="answers">
					<?php 
						if(isset($_SESSION['loggedin']) && !empty($answers)):
					?>
						<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?t_id=<?php echo $_GET['t_id']?>">
							<?php foreach($answers as $row):?>
								<li>
									<input type="radio" name="answer" value="<?php echo $row['answer'];?>" /><?php echo $row['answer'];?>
								</li>
							<?php endforeach;?>
							<input type="text" name="explination" placeholder="Add an explanation (optional)" />
							<input type="Submit" value="Submit" />
						</form>
					<?php endif;?>
				</ul>
			</div>
			<div id="comments-section">
				<h3>Comments:</h3>
				<ul class="comments">
					<?php foreach($posts as $row): ?>
						<li>
							<p><?php echo $row['explanation']?></p>
							<p id="author">- <?php echo $row['poster_name']?></p>
							<p id="author-info">answered <?php echo $row['answer_value']?></p>
						</li>
					<?php endforeach;?>
				</ul>
			</div>
		</div>
		<div id="aside">
			<h3>Related Topics</h3>
			<ul id="rel-topics">
				<li>
					<a href="index.php">Topic here...</a>
				</li>
				<li>
					<a href="index.php">Topic here...</a>
				</li>
				<li>
					<a href="index.php">Topic here...</a>
				</li>
			</ul>
		</div>
		<?php include 'footer.php';?>
	</div>

</body>
</html>