<div ng-show="tab.foods">

	<!-- ==========================recipe quick entry========================== -->

	<div class="margin-bottom">
		<ul>
			<li>start a new line for each item in your recipe</li>
			<li>there must be a space after the comma</li>
		</ul>
		<p>example format:</p>
		<p>1 large apple, red</p>
		<p>1 cup tomatoes</p>
		<div class="btn-toolbar" data-role="editor-toolbar" data-target="#wysiwyg">
			<a data-edit="bold" class="fa fa-bold"></a>
			<a data-edit="italic" class="fa fa-italic"></a>
			<a data-edit="underline" class="fa fa-underline"></a>
		</div>

		<div id="quick-recipe" class="wysiwyg"></div>

		<button ng-click="quickRecipe()"class="btn">go</button>

		<div>
			<div ng-repeat="error in errors.quick_recipe">{{error}}</div>
		</div>

		<div>
			<div ng-repeat="item in quick_recipe_contents">{{item}}</div>
		</div>

		<div>
			<div ng-repeat="step in quick_recipe_steps">{{step}}</div>
		</div>
	</div>
	
	<!-- ==========================foods========================== -->

	<div class="row">

		<div class="col col-sm-6">
			<input ng-keyup="insertFood($event.keyCode)" type="text" placeholder="add a new food" id="create-new-food" class="form-control">
			<hr>
			<div>
				<table class="table table-bordered">
					<tr>
						<th>name</th>
						<th>default</th>
						<th>calories</th>
					</tr>
					<tr ng-repeat="item in all_foods_with_units" data-food-id="{{item.food.id}}">
						<td ng-click="getFoodInfo(item.food.id, item.food.name)" class="pointer">{{item.food.name}}</td>
						<td>{{item.food.default_unit_name}}</td>
						<td>{{item.food.default_unit_calories}}</td>
						<td><i ng-click="deleteFood(item.food.id)" class="delete-item fa fa-times"></i></td>
					</tr>
				</table>
			</div>
		</div>
		
		<!-- ==========================recipes========================== -->
		
		<div class="col col-sm-6">
			<input ng-model="new_item.recipe.name" ng-keyup="insertRecipe($event.keyCode)" type="text" placeholder="add a new recipe" id="create-new-recipe" class="form-control">
			<hr>
		
			<div>

				<table class="table table-bordered">
					<tr>
						<th>name</th>
						<th>calories</th>
						<th></th>
					</tr>
					<tr ng-repeat="recipe in recipes">
						<td ng-click="showRecipePopup(recipe)" class="pointer">{{recipe.name}}</td>
						<td>calories</td>
						<td><i ng-click="deleteRecipe(recipe.id)" class="delete-item fa fa-times"></i></td>
					</tr>
				</table>
				
			</div>
		</div>

	</div> <!-- .row -->

</div> <!-- foods tab -->