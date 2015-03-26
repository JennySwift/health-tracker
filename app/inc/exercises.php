<div ng-if="tab === 'exercises'" id="exercises">

	<div>
		<div class="margin-bottom">
			<input ng-keyup="insertExercise($event.keyCode)" type="text" placeholder="add a new exercise" id="create-new-exercise" class="form-control">
		</div>
		
		<div>
			<div>
				<table class="table table-bordered">
					<tr>
						<th>name</th>
						<th>default unit</th>
						<th>default quantity</th>
						<th>tags</th>
						<th>x</th>
					</tr>
					<tr ng-repeat="exercise in exercises" class="hover">
						<td>{{exercise.name}}</td>
						<td ng-click="showDefaultExerciseUnitPopup(exercise)" class="pointer">{{exercise.default_exercise_unit_name}}</td>
						<td>{{exercise.default_quantity}}</td>
						<td>
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

</div> <!-- exercises tab -->