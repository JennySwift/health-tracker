<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	<?php
		include base_path().'/resources/views/templates/config.php';
		include($head_links);
	?>
</head>
<body>
	
	<div ng-controller="foods" class="container">

		@include('templates.header')
		
		<?php
			// include($header);
			include($templates . '/popups/food/index.php');
		?>
		
		<!-- ==========================foods========================== -->

		<div id="foods">

			<div id="quick-recipe-container">
				<?php include($templates . '/quick-recipe.php'); ?>
			</div>

			<hr>	

			<div class="flex">
				<div>
					<input ng-keyup="insertFood($event.keyCode)" type="text" placeholder="add a new food" id="create-new-food" class="form-control">
					<input ng-model="filter.foods" type="text" placeholder="filter foods" class="form-control">
					<hr>
					<div>
						<table class="table table-bordered">
							<tr>
								<th>name</th>
								<th>default</th>
								<th>calories</th>
							</tr>
							<tr ng-repeat="item in all_foods_with_units | filter:filter.foods" data-food-id="[[item.id]]">
								<td ng-click="getFoodInfo(item.id, item.name)" class="pointer">[[item.name]]</td>
								<td>[[item.default_unit.name]]</td>
								<td>[[item.default_unit_calories]]</td>
								<td><i ng-click="deleteFood(item)" class="delete-item fa fa-times"></i></td>
							</tr>
						</table>
					</div>
				</div>
				
				<!-- ==========================recipes========================== -->
				
				<div>
					<input ng-model="new_item.recipe.name" ng-keyup="insertRecipe($event.keyCode)" type="text" placeholder="add a new recipe" id="create-new-recipe" class="form-control">
					<input ng-model="filter.recipes.name" ng-keyup="filterRecipes()" type="text" placeholder="filter recipes by name" id="filter-recipes" class="form-control">
					<hr>
				
					<div>
				
						<table class="table table-bordered">
							<tr>
								<th>name</th>
								<th>calories</th>
								<th>tags</th>
								<th></th>
							</tr>
							<tr ng-repeat="recipe in recipes.filtered">
								<td ng-click="showRecipePopup(recipe)" class="pointer">[[recipe.name]]</td>
								<td>calories</td>
								<td>
									<span ng-repeat="tag in recipe.tags" class="badge">[[tag.name]]</span>
								</td>
								<td><i ng-click="deleteRecipe(recipe.id)" class="delete-item fa fa-times"></i></td>
							</tr>
						</table>
						
					</div>
				</div>
				
				<!-- ==========================recipe tags========================== -->
				
				<div>
					<input ng-model="new_item.recipe_tag" ng-keyup="insertRecipeTag($event.keyCode)" type="text" placeholder="add a new recipe tag" id="create-new-recipe-tag" class="form-control">
					<input ng-model="filter.recipe_tags" type="text" placeholder="filter recipe tags" class="form-control">
					<hr>
				
					<div>
				
						<table class="table table-bordered">
							<tr>
								<th>name</th>
								<th class="tooltipster" title="check to filter recipes by the tag">filter</th>
								<th></th>
							</tr>
							<tr ng-repeat="tag in recipe_tags | filter:filter.recipe_tags">
								<td>[[tag.name]]</td>
								<td>
									<input checklist-model="filter.recipes.tag_ids" checklist-value="tag.id" type="checkbox">
								</td>
								<td><i ng-click="deleteRecipeTag(tag.id)" class="delete-item fa fa-times"></i></td>
							</tr>
						</table>
						
					</div>
				</div>
			</div>
		</div>

	</div> <!-- foods tab -->

	<?php include($footer); ?>

	@include('footer')

</body>
</html>