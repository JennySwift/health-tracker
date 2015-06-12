<div ng-show="show.popups.exercise_entries" ng-click="closePopup($event, 'exercise_entries')" class="popup-outer">

	<div class="popup-inner">

		<table class="table table-bordered">
			<caption class="bg-blue">Entries for [[exercise_entries_popup.exercise.name]] with [[exercise_entries_popup.unit.name]] on [[date.typed]]</caption>
			<tr>
				<th>exercise</th>
				<th>quantity</th>
				<th>x</th>
			</tr>

			<tr ng-repeat="entry in exercise_entries_popup.entries">
				<td>[[entry.exercise.name]]</td>
				<td>[[entry.quantity]]</td>
				<td><i ng-click="deleteExerciseEntry(entry.id)" class="delete-item fa fa-times"></i></td>
			</tr>
		</table>

	</div>
	
</div>