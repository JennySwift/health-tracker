<div ng-show="show.popups.exercise_entries" ng-click="closePopup($event, 'exercise_entries')" class="popup-outer">

	<div class="popup-inner">

		<table class="table table-bordered">
			<caption class="bg-blue">exercise entries</caption>
			<tr>
				<th>exercise</th>
				<th>quantity</th>
				<th>x</th>
			</tr>

			<tr ng-repeat="entry in specific_exercise_entries" data-entry-id="[[entry.entry_id]]">
				<td>[[entry.name]]</td>
				<td>[[entry.quantity]]</td>
				<td><i ng-click="deleteExerciseEntry(entry.entry_id)" class="delete-item fa fa-times"></i></td>
			</tr>
		</table>

	</div>
	
</div>