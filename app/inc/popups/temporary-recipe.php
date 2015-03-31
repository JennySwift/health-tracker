<!-- for when logging a recipe -->
<div class="row">

	<div ng-show="show.popups.temporary_recipe" ng-click="closePopup($event, 'temporary_recipe')" class="popup-outer">
	
		<div class="popup-inner">
	
			<h4 class="center">{{recipe.name}}</h4>
			<p class="col-sm-12">Editing your recipe here will not change the default contents of your recipe.</p>
			<div class="row margin-bottom">
				<div class="col-sm-10 col-sm-offset-1">
					<input ng-model="recipe.portion" type="text" placeholder="how many portions? 1 for the full recipe, .5 for half, etc." class="form-control">
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
					<th>quantity</th>
					<th>unit</th>
				</tr>
				<tr ng-repeat="item in recipe.temporary_contents">
					<td>{{item.food_name}}</td>
					<td><input ng-model="item.quantity" type="text"></td>
					<td>
						<select name="" id="">
							<option ng-repeat="unit in item.assoc_units" ng-selected="unit.name === item.unit_name" value="">{{unit.name}}</option>
						</select>
					</td>
					<td><i ng-click="deleteFromTemporaryRecipe(item)" class="delete-item fa fa-times"></i></td>
				</tr>
			</table>
			
			<button ng-click="show.popups.temporary_recipe = false" class="close-popup btn btn-sm">cancel</button>
			<button ng-click="insertRecipeEntry()" class="btn btn-default">enter</button>
	
		</div>
		
	</div>

</div> <!-- .row -->