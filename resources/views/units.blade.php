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

	@include('templates.header')
	
	<div ng-controller="units" class="container">

		<div class="row">

			<div class="col col-sm-6">
				<input ng-keyup="insertFoodUnit($event.keyCode)" type="text" placeholder="add a new food unit" id="create-new-food-unit" class="form-control">
				<hr>
			
				<div id="display-food-units">
					<li ng-repeat="unit in units.food" class="list-group-item">
						[[unit.name]]
						<i ng-click="deleteFoodUnit(unit.id)" class="delete-item fa fa-times"></i>
					</li>
				</div>
			</div>
			
			<div class="col col-sm-6">
				<input ng-keyup="insertExerciseUnit($event.keyCode)" type="text" placeholder="add a new exercise unit" id="create-new-exercise-unit" class="form-control">
				<hr>

				<div id="display-exercise-units">
					<li ng-repeat="unit in units.exercise" class="list-group-item">
						[[unit.name]]
						<i ng-click="deleteExerciseUnit(unit.id)" class="delete-item fa fa-times"></i>
					</li>
				</div>
			
			</div>

		</div> <!-- .row -->

	</div> <!-- units tab -->

	<?php include($footer); ?>

	@include('footer')

</body>
</html>