<!doctype html>
<html lang="en" class="" ng-app="foodApp">
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

<body class="" ng-controller="display">

<?php
	include($inc . '/header.php');
?>   

<!-- ==============================.container============================== -->    
<div class="container">

	<?php include($inc . '/foods.php'); ?>
	<?php include($inc . '/exercises.php'); ?>
	<?php include($inc . '/units.php'); ?>
	<?php include($inc . '/popups/index.php'); ?>

	<div ng-if="tab === 'entries' || tab === 'journal'">
		<h1 class="row center">{{date.long}}</h1>
		
		<div class="row margin-bottom">

			<?php include($inc . '/date-navigation.php'); ?> 

		</div>	<!-- .row -->
	</div>

	<div ng-if="tab === 'entries'">

		<div class="row">

			<?php include($inc . '/info.php'); ?> 

			<?php include($inc . '/entry-inputs.php'); ?> 

		</div> <!-- .row -->

		<?php include($inc . '/entries.php'); ?>
		
	</div> <!-- end entries tab -->

	<div ng-if="tab === 'journal'">
		<h1>journal!</h1>
		<div ng-show="!journal_entry.edit">{{journal_entry.text}}</div>
		<textarea name="" id="" cols="30" rows="10" ng-model="journal_entry.text">write today's entry</textarea>
		<button ng-click="journal_entry.edit = true">edit</button>
		<button ng-click="insertOrUpdateJournalEntry()">save entry</button>
	</div>
      
</div> <!-- .container -->  

<?php include($inc . '/footer.php'); ?>
