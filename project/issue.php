<?php 
	require_once 'DBhandle.php';
	$issue = getTopic($_GET['t_id']);
	//$question = getQuestion($issue['topic_id']);

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$p_ans = clean_up($_POST['answer']);
		if(!empty($p_ans)){
			$p_exp = clean_up($_POST['explination']);
			post($p_ans, $question['q_id'], $p_exp);
			$echos = $_GET['t_id'];
			header("Location: https://localhost/project/issue.php?t_id=$echos");
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $issue['title'];?> | Eng'g Connect</title>
</head>
<body>
	<?php include "header.php"; ?>

	<div id="main-block">
		<div id="main-content">
			<div id="topic-content">
				<div id="topic-header">
					<h2><?php echo $issue['title']; ?></h2>
					<p>Posted on <?php echo $issue['date_of_post'];?> by <?php echo getUser($issue['poster_id'])['username']?></p>
				</div>
				<div id="topic-details">
					<p><?php echo $issue['details']?></p>
				</div>
			</div>
			<div id="topic-question">
				<h3>Question:</h3>
				<p>
					<?php
						$questions = getQuestions($issue['topic_id']);
						//$q_row = getQuestion($issue['topic_id']);
						//if(empty($q_row)) echo "there is no question for this topic";
						if(!empty($questions)):
							foreach ($questions as $q_row):
								echo $q_row['question'];
					?>
				</p>
				<ul class="answers">
					<?php 
							$answers = getAnswers($q_row['q_id']);
								if(!empty($answers)):
					?>
						<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?t_id=<?php echo $_GET['t_id']?>">
						<?php 
								$flag = isset($_SESSION['loggedin']) && !hasUserAnswered($q_row['q_id']);
								foreach($answers as $row):
						?>
							<?php echo $row['vote_count']?>
							<?php if($flag):?><input type="radio" name="answer" value="<?php echo $row['a_id'];?>"><?php endif;?>
							<?php echo $row['answer'];?> </br>
						<?php 	endforeach;?>
						<?php if($flag):?><input type="text" name="explination" placeholder="you may add an explanation"><?php endif;?>
						<?php if($flag):?><input type="Submit" value="Submit"><?php endif;?>
						</form>
					<?php 
								endif;
							endforeach;
						else: echo "there is no question for this topic";
						endif;
					?>
				</ul>
			</div>
			<div id="comments-section">
				<h3>Comments:</h3>
				<ul class="comments">
					<?php 
						foreach ($questions as $q_row):
							$posts = getPostsWithComments($q_row['q_id']);
							foreach($posts as $row): 
					?>
						<li>
							<p><?php echo $row['explanation']?></p>
							<p id="author">- <?php echo $row['poster_name']?></p>
							<p id="author-info">answered <?php echo $row['answer_value']?></p>
						</li>
					<?php
							endforeach;
						endforeach;
					?>
				</ul>
			</div>
		</div>
		<div id="aside">
			<h3>Related Topics</h3>
			<ul id="rel-topics">
				<?php
					$topics = getManyTopics(6);
					foreach($topics as $row):
				?>
				<li>
					<a href="issue.php?t_id=<?php echo $row['topic_id']?>"><?php echo $row['title']?></a>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
		<?php include 'footer.php';?>
	</div>

</body>
</html>