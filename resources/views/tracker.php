<!doctype html>
<html lang="en" class="" ng-app="tracker">
<head>

    <meta charset="UTF-8" name="viewport" content="initial-scale = 1">
    <title>Food App!</title>
    <?php
    	include(app_path().'/inc/config.php');
    ?>

    <link rel="stylesheet" href="tools/bootstrap.min.css">
    <link rel="stylesheet" href="tools/tooltipster.css">  
    <link rel="stylesheet" href="tools/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="" ng-controller="controller">

<?php
	include($inc . '/header.php');
?>   

<!-- ==============================.container============================== -->    
<div class="container">

	<h5>branch:refactor</h5>
	
	<?php
		include($inc . '/foods.php');
		include($inc . '/exercises.php');
		include($inc . '/date-navigation.php');
		include($inc . '/journal.php');
		include($inc . '/units.php');
		include($inc . '/popups/index.php');
		include($inc . '/entries.php');
	?>
	
</div> <!-- .container -->  

<?php include($inc . '/footer.php'); ?>
