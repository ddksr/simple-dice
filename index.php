<?php

function did($name) {
	return 'dice_' . md5($name);
}
function set_dice($name, $value) {
	setcookie(did($name), $value, time()+60*60*24*30);
}

function get_dice($name) {
	$name = did($name);
	return empty($_COOKIE[$name]) ? null : $_COOKIE[$name];
}

$label = empty($_GET["dilemma"]) ? null : $_GET["dilemma"];

$value = get_dice($label);
$msg = "";

$url = "index.php?roll&dilemma=" . urlencode($label);

$options = array();

if (isset($_GET['opts'])) {
	$options = explode(",", $_GET['opts']);
	if (sizeof($options) < 6) {
		$options = array();
	} else {
		$url .= "&opts=" . urlencode($_GET['opts']);
	}
}

if ($label && isset($_GET["roll"])) {
	if ($value) {
		$msg = "Dice was already rolled. You got <strong>$value</strong>!";
		if ($options) {
			$msg .= " Options were: " . implode(", ", $options);
		}
	} else {
		$value = rand(1, 6);
		if ($options) {
			$value = $options[$value - 1];
		}
		$msg = "You rolled the dice! You got <strong>$value</strong>! Options were: " . implode(", ", $options);
		set_dice($label, $value);
	}
}

?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Simple dice</title>
</head>

<body>
	<h1>Simple dice: <?php echo $label ? $label : "Sorry, there is no dilemma ... "; ?></h1>
	<?php if ($msg): ?>
	<div class="msg"><?php echo $msg; ?></div>
	<?php else: ?>
	<div class="roll"><a href="<?php echo $url; ?>">Roll the dice!</a></div>
	<?php endif; ?>
	<div class="new-dillema">
		<h2>Start a new dillema</h2>
		<form action="index.php?roll">
			<fieldset>
				<div class="field">
					<label>Dillema: </label><input type="text" name="dilemma" value="" />
				</div>
				<div class="field">
					<label>Options (comma seperated, have to be six!): </label><input type="text" name="opts" value="<?php echo $options ? implode(',',$options) : '' ?> " />
				</div>
				<div class="field">
					<input type="submit" value="Create new dilemma" />
				</div>
			</fieldset>
		</form>
	</div>
</body>

</html>
