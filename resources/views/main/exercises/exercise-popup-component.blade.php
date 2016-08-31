<script id="exercise-popup-template" type="x-template">

	<div>
		<popup
		    :show-popup.sync="showPopup"
		    id="exercise-popup"
		    :redirect-to="redirectTo"
		    :update="updateExercise"
		    :destroy="deleteExercise"
		>
		    <div slot="content">
				<h3 class="center">@{{  selectedExercise.name }}</h3>

				<div class="flex">

					<div>
						<h5 class="center">name</h5>
						<input
								v-model="selectedExercise.name"
								type="text"
								placeholder="name"
								class="form-control">
					</div>

					<div>
						<h5 class="center">description</h5>
						<input
								v-model="selectedExercise.description"
								type="text"
								placeholder="description"
								class="form-control">
					</div>

					<div class="step">
						<h5 class="center">step</h5>
						<input
								v-model="selectedExercise.stepNumber"
								type="text"
								placeholder="step number"
								class="form-control">
					</div>

					<div class="priority">
						<h5 class="center">priority</h5>
						<input
								v-model=" selectedExercise.priority"
								type="text"
								placeholder="priority"
								class="form-control">
					</div>

					<div>
						<h5 class="center">target</h5>
						<input
								v-model="selectedExercise.target"
								type="text"
								placeholder="target"
								class="form-control">
					</div>

					<div class="default-quantity">
						<h5 class="center tooltipster" title="This figure will be used, along with the default unit, when using the feature to quickly log a set of your exercise">default quantity</h5>

						<input
								v-model=" selectedExercise.defaultQuantity"
								type="text"
								placeholder="enter quantity"
								class="form-control">
					</div>

					<div>
						<h5 class="center">Stretch</h5>
						<input
								v-model="selectedExercise.stretch"
								type="checkbox"
								class="form-control">
					</div>

				</div>

				<div class="form-group">
					<label for="selected-exercise-frequency">Frequency</label>
					<input
							v-model="selectedExercise.frequency"
							type="text"
							id="selected-exercise-frequency"
							name="selected-exercise-frequency"
							placeholder="frequency"
							class="form-control"
					>
				</div>

				<div class="flex">

					<div>
						<h5 class="center">series</h5>

						<li
								v-for="series in exerciseSeries"
								class="list-group-item hover pointer"
								v-bind:class="{'selected': series.id ===  selectedExercise.series.id}"
								v-on:click=" selectedExercise.series.id = series.id">
							@{{ series.name }}
						</li>

					</div>

					<div>
						<h5 class="center">program</h5>

						<li
								v-for="program in programs"
								class="list-group-item hover pointer"
								v-bind:class="{'selected': program.id ===  selectedExercise.program.id}"
								v-on:click=" selectedExercise.program.id = program.id">
							@{{ program.name }}
						</li>

					</div>

					<div>
						<h5 class="center">default unit</h5>
						<li
								v-for="unit in units"
								class="list-group-item hover pointer"
								v-bind:class="{'selected': unit.id ===  selectedExercise.defaultUnit.data.id}"
								v-on:click=" selectedExercise.defaultUnit.data.id = unit.id">
							@{{ unit.name }}
						</li>
					</div>

				</div>
		    </div>
		</popup>
	</div>

</script>

