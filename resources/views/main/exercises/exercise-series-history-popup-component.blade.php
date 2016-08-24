<script id="series-history-popup-template" type="x-template">

	<div>
		<popup
		    :show-popup.sync="showPopup"
		    id="series-history-popup"
		    :redirect-to="redirectTo"
		>
		    <div slot="content">
				<h3>History of entries in the @{{ selectedSeries.name }} series</h3>

				<div class="filters">
					<input
							v-model="filterByExercise"
							type="text"
							placeholder="filter by exercise"
					>
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

					<tr v-for="entriesForDay in exerciseSeriesHistory | exerciseFilter">
						<td>@{{ entriesForDay.date }}</td>
						<td>@{{ entriesForDay.daysAgo }}</td>
						<td>@{{ entriesForDay.exercise.data.name }}</td>
					<!-- <td>@{{ exercise.data.description }}</td> -->
						<td>@{{ entriesForDay.exercise.data.stepNumber }}</td>
						<td>@{{ entriesForDay.sets }}</td>
						<td>@{{ entriesForDay.total }} @{{ entriesForDay.unit.name }}</td>
					</tr>
				</table>
		    </div>
		</popup>

	</div>

</script>

