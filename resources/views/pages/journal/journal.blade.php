<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	@include('templates.shared.head-links')
</head>
<body ng-controller="journal">

	@include('templates.shared.header')
	<feedback-directive></feedback-directive>
	@include('templates.shared.loading')
	
	<div class="container">

        @include('templates.shared.date-navigation')

		<div id="journal">

            <input ng-keyup="filterJournalEntries($event.keyCode)" type="text" placeholder="search entries" id="filter-journal" class="margin-bottom"/>

            @include('templates.new-sleep-entry')

            <div ng-show="filter_results.length > 0" id="filter-results-container">
                <i ng-click="clearFilterResults()" class="fa fa-close"></i>

                <div id="filter-results">
                    <div ng-repeat="entry in filter_results" ng-click="changeDate(13, entry.date)" class="hover pointer">
                        <span class="badge">[[entry.date]]</span>
                        <div ng-bind-html="entry.text"></div>
                    </div>
                </div>
            </div>


			<div class="btn-toolbar" data-role="editor-toolbar" data-target="#wysiwyg">
				<a data-edit="bold" class="fa fa-bold"></a>
			</div>
		
			<div ng-bind-html="journal_entry.text" id="journal-entry" class="wysiwyg margin-bottom"></div>
		
			<button ng-click="insertOrUpdateJournalEntry()" class="btn btn-success save">save entry</button>
		
		</div>
		
	</div>

	@include('templates.shared.footer')

</body>
</html>
