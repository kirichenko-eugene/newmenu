<?php 
include '../config/config.php';
require_once '../config/Dishes.php';
require_once '../config/Properties.php';
require_once '../config/Autorization.php';
require_once '../config/ResizeImage.php';

$votes = new Dishes;

$voteId = $_POST['id'];
$action = $_POST['action'];
$curVotes = $votes->getAllVotes($voteId);
$likes_content = $votes->addToLikeContent($voteId);
$updateVotes = $votes->updateVotes($curVotes, $voteId);

if($updateVotes) {
	$effectiveVote = $votes->getEffectiveVotes($voteId); ?>
	<div class="like-container">
		<div class="vote_buttons heart-container" id="vote_buttons<?=$votes->getId($dish)?>">
			<img src="<?=$site?>img/heartfull.svg" alt="">
		</div>
		<div class="votes_count" id="votes_count">
			<?php echo "<span>$effectiveVote</span>"; ?>
		</div>
	</div> 
<?php } 
else if(!$updateVotes) {
	echo "Ошибка!";
}

?>
