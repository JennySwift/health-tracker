<script id="series-popup-template" type="x-template">

	<div v-show="showPopup" v-on:click="closePopup($event)" class="popup-outer">

		<div class="popup-inner">

			<h3 class="popup-title">@{{ selectedSeries.name }} series</h3>

			<label for="seriesName">Name your series</label>
			<input v-model="selectedSeries.name" type="text" name="seriesName" placeholder="name"/>

			<div>
				<label for="exericise-series-priority"></label>
				<input
						v-model="selectedSeries.priority"
						type="text"
						id="exercise-series-priority"
						name="exercise-series-priority"
						placeholder="priority"
						class="form-control"
				/>
			</div>

			<div class="buttons">
				<button
						v-on:click="deleteExerciseSeries(selectedSeries)"
						class="btn btn-danger"
				>
					Delete
				</button>

				<button v-on:click="updateSeries()" class="btn btn-success save">Save</button>
			</div>

		</div>

	</div>

</script>