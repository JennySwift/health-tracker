<div v-show="showSpecificExerciseEntriesPopup" v-on:click="closeSpecificExerciseEntriesPopup($event)" class="popup-outer">

	<div class="popup-inner">

		<table class="table table-bordered">
			<caption class="bg-blue">Entries for @{{ selectedExercise.name }} with @{{ selectedExercise.unit.name }} on @{{ date.typed }}</caption>
			<tr>
				<th>exercise</th>
				<th>quantity</th>
				<th>x</th>
			</tr>

			<tr v-for="entry in selectedExercise.entries">
				<td>@{{ entry.exercise.name }}</td>
				<td>@{{ entry.quantity }}</td>
				<td><i v-on:click="deleteExerciseEntry(entry)" class="delete-item fa fa-times"></i></td>
			</tr>
		</table>

	</div>
	
</div>