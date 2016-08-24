<script id="entries-for-specific-exercise-and-date-and-unit-popup-template" type="x-template">

	<div v-if="entries[0] && entries[0].exercise">
		<popup
		    :show-popup.sync="showPopup"
		    id="entries-for-specific-exercise-and-date-and-unit-popup"
		    :redirect-to="redirectTo"
		>
		    <div slot="content">
				<table class="table table-bordered">
					<caption class="bg-blue">Entries for @{{ entries[0].exercise.data.name }} with @{{ entries[0].unit.name }} on @{{ date.typed }}</caption>
					<tr>
						<th>exercise</th>
						<th>quantity</th>
						<th>created at</th>
						<th>x</th>
					</tr>

					<tr
						v-for="entry in entries"
					>
						<td>@{{ entry.exercise.data.name }}</td>
						<td>
							<input
								v-model="entry.quantity"
								v-on:keyup.13="updateExerciseEntry(entry)"
							>
						</td>
						<td>@{{ entry.createdAt }}</td>
						<td><i v-on:click="deleteExerciseEntry(entry)" class="delete-item fa fa-times"></i></td>
					</tr>
				</table>
		    </div>
		</popup>

	</div>

</script>