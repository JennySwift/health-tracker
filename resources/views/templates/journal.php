<div ng-controller="journal" ng-show="tab.journal" id="journal">

	<div class="btn-toolbar" data-role="editor-toolbar" data-target="#wysiwyg">
		<a data-edit="bold" class="fa fa-bold"></a>
	</div>
	<div ng-bind-html="journal_entry.text" id="journal-entry" class="wysiwyg margin-bottom"></div>

	<button ng-click="insertOrUpdateJournalEntry()">save entry</button>

</div>
