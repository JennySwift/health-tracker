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
<body ng-controller="journal">

	<?php
		// include($header);
	?>

	@include('templates.header')
	
	<div class="container">

		<?php include($templates . '/date-navigation.php'); ?>

		<div id="journal">

            <input ng-keyup="filterJournalEntries($event.keyCode)" type="text" placeholder="search entries" id="filter-journal" class="margin-bottom"/>

            <div id="filter-results">
                <div ng-repeat="entry in filter_results" ng-click="changeDate(13, entry.date)" class="hover pointer">
                    <span class="badge">[[entry.date]]</span>
                    [[entry.text]]
                </div>
            </div>

			<div class="btn-toolbar" data-role="editor-toolbar" data-target="#wysiwyg">
				<a data-edit="bold" class="fa fa-bold"></a>
			</div>
		
			<div ng-bind-html="journal_entry.text" id="journal-entry" class="wysiwyg margin-bottom"></div>
		
			<button ng-click="insertOrUpdateJournalEntry()" class="btn btn-success">save entry</button>
		
		</div>
		
	</div>

	<?php include($footer); ?>

	@include('footer')

</body>
</html>
