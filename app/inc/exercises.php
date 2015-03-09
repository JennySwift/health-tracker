<div ng-if="tab === 'exercises'" class="">

	<div class="row margin-bottom">

		<div class="col-sm-4 col-sm-offset-4">
			<input ng-keyup="insertExercise($event.keyCode)" type="text" placeholder="add a new exercise" id="create-new-exercise" class="form-control">
		</div>

	</div> <!-- .row -->

	<div class="row">

		<div class="col-sm-6 col-sm-offset-3">
			<div>
				<ul class="list-group">
					<li ng-repeat="exercise in exercises" class="list-group-item">
						{{exercise.name}}
						<i ng-click="deleteExercise(exercise.id)" class="delete-item fa fa-times"></i>
					</li>
				</ul>
			</div>
		</div>

	</div> <!-- .row -->

</div> <!-- exercises tab -->