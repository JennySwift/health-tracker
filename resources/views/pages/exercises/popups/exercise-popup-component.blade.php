<script id="exercise-popup-template" type="x-template">

	<div v-show="showPopup" v-on:click="closePopup($event)" class="popup-outer">
		
		<div id="exercise-popup" class="popup-inner">

			{{--<pre>@{{$data.exercises | json}}</pre>--}}
			{{--<pre>@{{$data.selectedExercise | json}}</pre>--}}

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

			</div>

			<div class="flex">

				<div>
					<h5 class="center">series</h5>

					<li
							v-for="series in exercise_series"
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
							v-bind:class="{'selected': unit.id ===  selectedExercise.defaultUnit.id}"
							v-on:click=" selectedExercise.defaultUnit.id = unit.id">
						@{{ unit.name }}
					</li>
				</div>

				<div>
					<h5 class="center">tags</h5>

					<ul class="list-group">
						<li
								v-for="tag in exercise_tags"
								class="list-group-item">
							<span>@{{ tag.name }}</span>

							<input
									checklist-model=" selectedExercise.tag_ids"
									checklist-value="tag.id"
									type="checkbox">
						</li>
					</ul>
				</div>

			</div>

			<div class="buttons">
				<button v-on:click="updateExercise()" class="btn btn-success save">Save</button>
				<button v-on:click="deleteExercise()" class="btn btn-danger save">Delete</button>
			</div>

		</div>

	</div>

</script>
