<script id="entries-for-specific-exercise-and-date-and-unit-popup-template" type="x-template">

	<div v-show="showPopup" v-on:click="closePopup($event)" class="popup-outer">

		<div v-if="entries[0] && entries[0].exercise" class="popup-inner">

			<table class="table table-bordered">
				<caption class="bg-blue">Entries for @{{ entries[0].exercise.data.name }} with @{{ entries[0].unit.name }} on @{{ date.typed }}</caption>
				<tr>
					<th>exercise</th>
					<th>quantity</th>
					<th>x</th>
				</tr>

				<tr v-for="entry in entries">
					<td>@{{ entry.exercise.data.name }}</td>
					<td>@{{ entry.quantity }}</td>
					<td><i v-on:click="deleteExerciseEntry(entry)" class="delete-item fa fa-times"></i></td>
				</tr>
			</table>

		</div>

	</div>

</script>