<div ng-show="tab.foods" id="foods-tab">

	<?php include('quick-recipe.php'); ?>
	
	<!-- ==========================foods========================== -->

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
					<tr ng-repeat="item in all_foods_with_units | filter:filter.foods" data-food-id="{{item.food.id}}">
						<td ng-click="getFoodInfo(item.food.id, item.food.name)" class="pointer">{{item.food.name}}</td>
						<td>{{item.food.default_unit_name}}</td>
						<td>{{item.food.default_unit_calories}}</td>
						<td><i ng-click="deleteFood(item.food.id)" class="delete-item fa fa-times"></i></td>
					</tr>
				</table>
			</div>
		</div>
		
		<!-- ==========================recipes========================== -->
		
		<div>
			<input ng-model="new_item.recipe.name" ng-keyup="insertRecipe($event.keyCode)" type="text" placeholder="add a new recipe" id="create-new-recipe" class="form-control">
			<input ng-model="filter.recipes" type="text" placeholder="filter recipes" class="form-control">
			<hr>
		
			<div>

				<table class="table table-bordered">
					<tr>
						<th>name</th>
						<th>calories</th>
						<th></th>
					</tr>
					<tr ng-repeat="recipe in recipes | filter:filter.recipes">
						<td ng-click="showRecipePopup(recipe)" class="pointer">{{recipe.name}}</td>
						<td>calories</td>
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
						<th></th>
					</tr>
					<tr ng-repeat="tag in recipe_tags | filter:filter.recipe_tags">
						<td>{{tag.name}}</td>
						<td><i ng-click="deleteRecipeTag(tag.id)" class="delete-item fa fa-times"></i></td>
					</tr>
				</table>
				
			</div>
		</div>

	</div>

</div> <!-- foods tab -->