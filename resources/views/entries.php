<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8" name="viewport" content="initial-scale = 1">
	<title>tracker</title>
	<?php
		include(base_path().'/resources/views/templates/config.php');
		include($head_links);
	?>
</head>
<body>
	
<?php
	include($header);
	include($templates . '/popups/entry/index.php');
?> 

<!-- ==============================.container============================== -->
<div class="container">

	<h5>branch:refactor</h5>

	<div ng-controller="entries">

		<?php include($templates . '/date-navigation.php'); ?>
		
		<div>

			<div id="info-entries-wrapper">
				<?php
					include($templates . '/info.php');
					include($templates . '/entry-inputs.php');
				?> 
			</div>
			

			<div id="entries">
				<!-- display food entries -->
				<div>
					<table class="table table-bordered">
						<caption class="bg-blue">food entries</caption>
						<tr><th>food</th><th>quantity</th><th>unit</th><th>calories</th><th>recipe</th></tr>
						<tr ng-repeat="entry in entries.menu" data-entry-id="{{entry.entry_id}}">
							<td>{{entry.food_name}}</td>
							<td>{{entry.quantity}}</td>
							<td>{{entry.unit_name}}</td>
							<td>{{entry.calories}}</td>
							<td>
								<span ng-if="entry.recipe_name" class="badge">{{entry.recipe_name}}</span>
								<span ng-if="!entry.recipe_name">N/A</span>
							</td>
							<td>
								<i ng-if="!entry.recipe_name" ng-click="deleteFoodEntry(entry.entry_id)" class="delete-item fa fa-times"></i>
								<i ng-if="entry.recipe_name" ng-click="showDeleteFoodOrRecipeEntryPopup(entry.entry_id, entry.recipe_id)" class="delete-item fa fa-times"></i>
							</td>
						</tr>
					</table>
				</div>
				<!-- display exercise entries -->
				<div>
					<table class="table table-bordered">
						<caption class="bg-blue">exercise entries</caption>
						<tr>
							<th>exercise</th>
							<th>description</th>
							<th>sets</th>
							<th>total</th>
							<th></th>
						</tr>

						<tr ng-repeat="entry in entries.exercise" data-entry-id="{{entry.entry_id}}">
							<td>{{entry.name}}</td>
							<td>{{entry.description}}</td>
							<td ng-click="getSpecificExerciseEntries(entry.exercise_id, entry.unit_id)" class="pointer">{{entry.sets}}</td>
							<td>{{entry.total}} {{entry.unit_name}}</td>
							<td><button ng-if="entry.unit_id === entry.default_exercise_unit_id" ng-click="insertExerciseSet(entry.exercise_id)" class="btn-xs">add set</button></td>
						</tr>
					</table>
				</div>
						
			</div>

		</div>

	</div> <!-- end entries tab -->
	
</div> <!-- .container -->  

<?php include($footer); ?>

</body>
</html>
