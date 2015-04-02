<div ng-if="tab.exercises" id="exercises">

	<div>
		<div class="margin-bottom">
			<input ng-keyup="insertExercise($event.keyCode)" type="text" placeholder="new exercise name" id="create-new-exercise" class="form-control">
			<input ng-keyup="insertExercise($event.keyCode)" type="text" placeholder="description" id="exercise-description" class="form-control">
		</div>
		
		<div>
			<div>
				<table class="table table-bordered">
					<tr>
						<th>name</th>
						<th>description</th>
						<th>step</th>
						<th>series</th>
						<th>default quantity</th>
						<th>default unit</th>
						<th>tags</th>
						<th>x</th>
					</tr>
					<tr ng-repeat="exercise in exercises" class="hover">
						<td ng-click="showExercisePopup(exercise)" class="pointer">{{exercise.name}}</td>
						<td ng-click="showExercisePopup(exercise)" class="pointer">{{exercise.description}}</td>
						<td ng-click="showExercisePopup(exercise)" class="pointer">{{exercise.step_number}}</td>
						<td ng-click="showExercisePopup(exercise)" class="pointer">{{exercise.series_name}}</td>
						<td ng-click="showExercisePopup(exercise)" class="pointer">{{exercise.default_quantity}}</td>
						<td ng-click="showExercisePopup(exercise)" class="pointer">{{exercise.default_exercise_unit_name}}</td>
						<td ng-click="showExercisePopup(exercise)" class="pointer">
							<span ng-repeat="tag in exercise.tags" class="badge">{{tag.name}}</span>
						</td>
						<td><i ng-click="deleteExercise(exercise.id)" class="delete-item fa fa-times"></i></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<!-- exercise tags -->
	<div>

		<div class="margin-bottom">
			<input ng-keyup="insertExerciseTag($event.keyCode)" type="text" placeholder="add tag"  id="create-exercise-tag" class="form-control">
		</div>

		<div>
			<table class="table table-bordered">
				<tr>
					<th>tag</th>
					<th>x</th>
				</tr>
				<tr ng-repeat="tag in exercise_tags">
					<td>{{tag.name}}</td>
					<td><i ng-click="deleteExerciseTag(tag.id)" class="delete-item fa fa-times"></i></td>
				</tr>
			</table>
		</div>
	</div>

	<!-- exercise series -->
	<div>

		<div class="margin-bottom">
			<input ng-keyup="insertExerciseSeries($event.keyCode)" type="text" placeholder="add a series"  id="exercise-series" class="form-control">
		</div>

		<div>
			<table class="table table-bordered">
				<tr>
					<th>series</th>
					<th>x</th>
				</tr>
				<tr ng-repeat="series in exercise_series">
					<td>{{series.name}}</td>
					<td><i ng-click="deleteExerciseSeries(series.id)" class="delete-item fa fa-times"></i></td>
				</tr>
			</table>
		</div>
	</div>

</div> <!-- exercises tab -->