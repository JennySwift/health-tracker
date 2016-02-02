<div v-show="showExerciseSeriesHistoryPopup"  v-click="closePopup($event, 'exerciseSeriesHistory')" class="popup-outer">

	<div id="series-history-popup" class="popup-inner">

        <h3>History of entries in the @{{ selectedSeries.name }} series</h3>

		<div class="filters">
			<input v-model="seriesHistoryFilter" type="text" placeholder="filter by exercise">
		</div>

        <div v-if="exerciseSeriesHistory.length === 0">No entries to show</div>

		<table v-if="exerciseSeriesHistory.length > 0" class="table table-bordered">

			<tr>
				<th>date</th>
				<th>days ago</th>
				<th>exercise</th>
				<!-- <th>description</th> -->
				<th>step</th>
				<th>sets</th>
				<th>total</th>
			</tr>

			<tr v-for="entriesForDay in exerciseSeriesHistory | filter: {exercise: {name: seriesHistoryFilter}}">
				<td>@{{ entriesForDay.date }}</td>
				<td>@{{ entriesForDay.days_ago }}</td>
				<td>@{{ entriesForDay.exercise.name }}</td>
				<!-- <td>@{{ exercise.description }}</td> -->
				<td>@{{ entriesForDay.exercise.step_number }}</td>
				<td>@{{ entriesForDay.sets }}</td>
				<td>@{{ entriesForDay.total }} @{{ entriesForDay.unit_name }}</td>
			</tr>
		</table>

	</div>
	
</div>