<div ng-show="show.popups.exercise_series_history" ng-click="closePopup($event, 'exercise_series_history')" class="popup-outer">

	<div id="series-history-popup" class="popup-inner">

        <h3>History of entries in the [[selectedSeries.name]] series</h3>

		<div class="filters">
			<input ng-model="seriesHistoryFilter" type="text" placeholder="filter by exercise">
		</div>

        <div ng-if="exercise_series_history.length === 0">No entries to show</div>

		<table ng-if="exercise_series_history.length > 0" class="table table-bordered">

			<tr>
				<th>date</th>
				<th>days ago</th>
				<th>exercise</th>
				<!-- <th>description</th> -->
				<th>step</th>
				<th>sets</th>
				<th>total</th>
			</tr>

			<tr ng-repeat="entriesForDay in exercise_series_history | filter: {exercise: {name: seriesHistoryFilter}}">
				<td>[[entriesForDay.date]]</td>
				<td>[[entriesForDay.days_ago]]</td>
				<td>[[entriesForDay.exercise.name]]</td>
				<!-- <td>[[exercise.description]]</td> -->
				<td>[[entriesForDay.exercise.step_number]]</td>
				<td>[[entriesForDay.sets]]</td>
				<td>[[entriesForDay.total]] [[entriesForDay.unit_name]]</td>
			</tr>
		</table>

	</div>
	
</div>