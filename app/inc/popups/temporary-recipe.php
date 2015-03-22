<!-- for when logging a recipe -->
<div class="row">

	<div ng-show="recipe.temporary_contents.length > 0" class="popup col-sm-8">
		<h4 class="center">{{recipe.name}}</h4>
		<p class="col-sm-12">Editing your recipe here will not change the default contents of your recipe.</p>
		<div class="row margin-bottom">
			<div class="col-sm-10 col-sm-offset-1">
				<input ng-model="recipe.portion" type="text" placeholder="enter percentage of recipe" class="form-control">
				<input ng-model="food.name" ng-keyup="autocomplete('food', $event.keyCode); enter($event.keyCode, 'food', 'temporary_recipe')" ng-blur="autocomplete.food = ''" type="text" placeholder="add food to {{recipe.name}}" id="temporary-recipe-popup-food-input" class="form-control">
				
				<div id="" class="autocomplete-dropdown">
					<div ng-repeat="food in autocomplete.food" ng-class="{'selected': $first}" data-id="{{food.food_id}}" class="autocomplete-dropdown-item">{{food.food_name}}</div>
				</div>
				
				<input ng-model="food.quantity" ng-keyup="enter($event.keyCode, 'food', 'temporary_recipe')" type="text" placeholder="quantity" id="temporary-recipe-popup-food-quantity" class="form-control">
				<select ng-model="unit_id" ng-keyup="enter($event.keyCode, 'food', 'temporary_recipe')" name="" id="temporary-recipe-popup-unit-select" class="form-control">
					<option ng-repeat="unit in assoc_units" ng-selected="unit.default_unit === true" data-unit-id="{{unit.unit_id}}">{{unit.unit_name}}</option>
				</select>
			</div>
		</div>
		<table class="table table-bordered">
			<tr>
				<th>food</th>
				<th>unit</th>
				<th>quantity</th>
			</tr>
			<tr ng-repeat="item in recipe.temporary_contents">
				<td>{{item.food_name}}</td>
				<td>
					<select name="" id="">
						<option ng-repeat="unit in item.assoc_units" ng-selected="unit.unit_name === item.unit_name" value="">{{unit.unit_name}}</option>
					</select>
				</td>
				<td><input ng-model="item.quantity" type="text"></td>
				<td><i ng-click="deleteFromTemporaryRecipe(item)" class="delete-item fa fa-times"></i></td>
			</tr>
		</table>
		
		<button ng-click="recipe.temporary_contents.length = 0" class="close-popup btn btn-sm">cancel</button>
		<button ng-click="addMenuEntry()" class="btn btn-default col-sm-12">enter</button>
	</div>

</div> <!-- .row -->