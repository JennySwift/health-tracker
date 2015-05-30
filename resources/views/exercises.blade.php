<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	<?php
		include(base_path().'/resources/views/templates/config.php');
		include($head_links);
	?>
</head>
<body>

	<?php
		include($header);
	?> 
	
	<div ng-controller="exercises" id="exercises">

		<?php include($templates . '/popups/exercise/index.php'); ?>

		<!-- exercises -->

		<div>
			<div class="margin-bottom">
				<h2 class="center">Exercises</h2>
				<input ng-keyup="insertExercise($event.keyCode)" type="text" placeholder="Add a new exercise" id="create-new-exercise" class="form-control">
				<input ng-keyup="insertExercise($event.keyCode)" type="text" placeholder="description" id="exercise-description" class="form-control">
			</div>
			
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
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.name]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.description]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.step_number]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.series_name]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.default_quantity]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.default_unit_name]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">
						<span ng-repeat="tag in exercise.tags" class="badge">[[tag.name]]</span>
					</td>
					<td><i ng-click="deleteExercise(exercise.id)" class="delete-item fa fa-times"></i></td>
				</tr>
			</table>
		</div>

		<!-- exercise tags -->

		<div>
			<div class="margin-bottom">
				<h2 class="center">Tags</h2>
				<input ng-keyup="insertExerciseTag($event.keyCode)" type="text" placeholder="Add a new tag"  id="create-exercise-tag" class="form-control">
			</div>
			
			<table class="table table-bordered">
				<tr>
					<th>tag</th>
					<th>x</th>
				</tr>
				<tr ng-repeat="tag in exercise_tags">
					<td>[[tag.name]]</td>
					<td><i ng-click="deleteExerciseTag(tag.id)" class="delete-item fa fa-times"></i></td>
				</tr>
			</table>
		</div>

		<!-- exercise series -->
		
		<div>
			<div class="margin-bottom">
				<h2 class="center">Series</h2>
				<input ng-keyup="insertExerciseSeries($event.keyCode)" type="text" placeholder="Add a new series"  id="exercise-series" class="form-control">
			</div>
			
			<table class="table table-bordered">
				<tr>
					<th>series</th>
					<th>history</th>
					<th>workout</th>
					<th>edit</th>
					<th>x</th>
				</tr>
				<tr ng-repeat="series in exercise_series">
					<td>[[series.name]]</td>
					<td><button ng-click="getExerciseSeriesHistory(series.id)">show</button></td>
					<td><span ng-repeat="workout in series.workouts">[[workout.name]]</span></td>
					<td><button ng-click="showExerciseSeriesPopup(series)" class="btn-xs">edit</button></td>
					<td><i ng-click="deleteExerciseSeries(series.id)" class="delete-item fa fa-times"></i></td>
				</tr>
			</table>
		</div>

		<!-- workouts -->
		<div id="workouts">
			<h2 class="center">Workouts</h2>
			<input ng-keyup="insertWorkout($event.keyCode)" type="text" placeholder="Add a new workout" id="workout" class="form-control">
			<div class="flex">
				<div ng-repeat="workout in workouts">
					<h3 class="center">[[workout.name]]</h3>
					<ul class="list-group">
						<li ng-repeat="series in workout.contents" class="list-group-item">[[series.name]]</li>
					</ul>
				</div>
			</div>
		</div>

	</div>

	<?php
		include($footer);
		// include(base_path() . '/resources/views/footer.php');

	?>

	@include('footer')

</body>
</html>