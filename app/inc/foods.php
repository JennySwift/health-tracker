<div ng-if="tab === 'foods'">

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
			<input ng-keyup="insert($event.keyCode, 'recipes', displayRecipeList)" type="text" placeholder="add a new recipe" id="create-new-recipe" class="form-control">
			<hr>
		
			<div>

				<table class="table table-bordered">
					<tr>
						<th>name</th>
						<th>calories</th>
						<th></th>
					</tr>
					<tr ng-repeat="recipe in recipes" class="pointer">
						<td ng-click="displayRecipeContents(recipe.id, recipe.name)" data-recipe-id="{{recipe.id}}">{{recipe.name}}</td>
						<td>calories</td>
						<td><i ng-click="deleteItem('recipes', 'recipe', recipe.id, displayRecipes)" class="delete-item fa fa-times"></i></td>
					</tr>
				</table>
				
				<!-- <ul class="list-group">
					<li ng-repeat="recipe in recipes" ng-click="displayRecipeContents(recipe.recipe_id, recipe.recipe_name)" data-recipe-id="{{recipe.recipe_id}}" ng-click="" class="pointer list-group-item">
						{{recipe.recipe_name}}
						<i ng-click="deleteItem('recipes', 'recipe', recipe.recipe_id, displayRecipes)" class="delete-item fa fa-times"></i>
					</li>
				</ul> -->
			</div>
		</div>

	</div> <!-- .row -->

	<!-- ==========================food units popup========================== -->

	<div class="row">

		<div ng-show="show.food_info" class="popup col-sm-8">
			<h3 class="center">{{food_popup.name}}</h3>
			
			<ul class="list-group">
				<div ng-repeat="unit in food_popup.info" ng-class="{'default-unit': unit.default_unit === true}" class="list-group-item">
					<input ng-model="checked_unit" ng-click="insertOrDeleteUnitInCalories(unit.id, unit.checked)" type="checkbox" ng-checked="unit.checked === true">
					{{unit.name}}
					<button ng-if="unit.default_unit === true" class="btn btn-sm default" disabled>default</button>
					<button ng-if="unit.default_unit === false && unit.checked === true" ng-click="updateDefaultUnit(unit.id)" class="btn btn-sm make-default show-hover-item">make default</button>
					<input ng-model="unit.calories" ng-keyup="updateCalories($event.keyCode, unit.id, unit.calories)" type="text" value="{{unit.calories}}" placeholder="calories" class="calories-input">
				</div>
			</ul>
			
			<button ng-click="show.food_info = false" class="close-popup btn btn-sm">close</button>
		</div>

	</div> <!-- .row -->

	<!-- ==========================recipe popup========================== -->

	<div class="row">

		<div ng-show="recipe.name !== undefined" class="popup col-sm-8">
			<h4 class="center">{{recipe.name}}</h4>
			<div class="row margin-bottom">
				<div class="col-sm-10 col-sm-offset-1">
					<input ng-model="food.name" ng-keyup="autocomplete('food', $event.keyCode); enter($event.keyCode, 'food')" ng-blur="autocomplete.food = ''" type="text" placeholder="add food to {{recipe.name}}" id="recipe-popup-food-input" class="form-control">
					
					<div id="" class="autocomplete-dropdown">
						<div ng-repeat="food in autocomplete.food" ng-class="{'selected': $first}" data-id="{{food.food_id}}" class="autocomplete-dropdown-item">{{food.food_name}}</div>
					</div>
					
					<input ng-model="food.quantity" ng-keyup="enter($event.keyCode, 'food')" type="text" placeholder="quantity" id="recipe-popup-food-quantity" class="form-control">
					<select ng-model="unit_id" ng-keyup="enter($event.keyCode, 'food')" name="" id="" class="form-control">
						<option ng-repeat="unit in assoc_units" ng-selected="unit.default_unit === true" data-unit-id="{{unit.unit_id}}">{{unit.unit_name}}</option>
					</select>
				</div>
			</div>
		
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<table class="table table-bordered">
						<tr>
							<th>food</th>
							<th>unit</th>
							<th>quantity</th>
						</tr>
						<tr ng-repeat="item in recipe.contents">
							<td>{{item.food_name}}</td>
							<td>{{item.quantity}}</td>
							<td>{{item.unit_name}}</td>
							<td><i ng-click="deleteItem('food_recipe', 'food', item.id, displayRecipeContents)" class="delete-item fa fa-times"></i></td>
						</tr>
					</table>
				</div>
			</div>
			<button ng-click="recipe.name = undefined" class="close-popup btn btn-sm">close</button>
		</div>

	</div> <!-- .row -->

</div> <!-- foods tab -->