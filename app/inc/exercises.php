<div ng-if="tab === 'exercises'" class="">

	<div class="row margin-bottom">

		<div class="col-sm-4 col-sm-offset-4">
			<input ng-keyup="insertExercise($event.keyCode)" type="text" placeholder="add a new exercise" id="create-new-exercise" class="form-control">
		</div>

	</div> <!-- .row -->

	<div class="row">

		<div class="col-sm-6 col-sm-offset-3">
			<div>
				<table class="table table-bordered">
					<tr>
						<th>name</th>
						<th>default unit</th>
						<th>x</th>
					</tr>
					<tr ng-repeat="exercise in exercises" class="hover">
						<td>{{exercise.name}}</td>
						<td ng-click="showDefaultExerciseUnitPopup(exercise)" class="pointer">{{exercise.default_exercise_unit_name}}</td>
						<td><i ng-click="deleteExercise(exercise.id)" class="delete-item fa fa-times"></i></td>
					</tr>
				</table>
			</div>
		</div>

	</div> <!-- .row -->

</div> <!-- exercises tab -->