<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	<?php
		include(base_path().'/resources/views/templates/config.php');
		include($head_links);
	?>
</head>
<body>

	<?php
		include($header);
	?>
	
	<div class="container">
		<div ng-controller="journal" id="journal">
		
			<?php include($templates . '/date-navigation.php'); ?>
		
			<div class="btn-toolbar" data-role="editor-toolbar" data-target="#wysiwyg">
				<a data-edit="bold" class="fa fa-bold"></a>
			</div>
			<div ng-bind-html="journal_entry.text" id="journal-entry" class="wysiwyg margin-bottom"></div>
		
			<button ng-click="insertOrUpdateJournalEntry()">save entry</button>
		
		</div>
	</div>

	<?php include($footer); ?>

</body>
</html>
