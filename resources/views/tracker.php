<!doctype html>
<html lang="en" class="" ng-app="tracker">
<head>

    <meta charset="UTF-8" name="viewport" content="initial-scale = 1">
    <title>Food App!</title>
    <?php
    	include(base_path().'/resources/views/templates/config.php');
    ?>

    <link rel="stylesheet" href="tools/bootstrap.min.css">
    <link rel="stylesheet" href="tools/tooltipster.css">  
    <link rel="stylesheet" href="tools/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="" ng-controller="controller">

<?php
	include($templates . '/header.php');
?>   

<!-- ==============================.container============================== -->    
<div class="container">

	<h5>branch:refactor</h5>
	
	<?php
		include($templates . '/foods.php');
		include($templates . '/exercises.php');
		include($templates . '/journal.php');
		include($templates . '/units.php');
		include($templates . '/popups/index.php');
		include($templates . '/entries.php');
	?>
	
</div> <!-- .container -->  

<?php include($templates . '/footer.php'); ?>
