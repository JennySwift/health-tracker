<!-- ==========================recipe popup========================== -->

<div class="row">

	<div ng-show="show.popups.recipe" class="popup col-sm-8">
		<h4 class="center">{{recipe.name}}</h4>
		<div class="row margin-bottom">
			<div class="col-sm-10 col-sm-offset-1">
				<input ng-model="recipe_popup.food.name" ng-keyup="autocompleteFood($event.keyCode, recipe_popup.food.name); insertOrAutocompleteFoodEntry($event.keyCode)" ng-blur="show.autocomplete.food = false" type="text" placeholder="add food to {{recipe.name}}" id="recipe-popup-food-input" class="form-control">
				
				<div ng-show="show.autocomplete.food" class="autocomplete-dropdown">
					<div ng-repeat="food in autocomplete.food" ng-class="{'selected': food.selected}" class="autocomplete-dropdown-item">{{food.name}}</div>
				</div>
				
				<input ng-model="recipe_popup.food.quantity" ng-keyup="insertOrAutocompleteFoodEntry($event.keyCode)" type="text" placeholder="quantity" id="recipe-popup-food-quantity" class="form-control">
				<select ng-model="unit_id" ng-keyup="insertOrAutocompleteFoodEntry($event.keyCode)" name="" id="" class="form-control">
					<option ng-repeat="unit in selected.food.assoc_units" ng-selected="unit.default_unit === true">{{unit.name}}</option>
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
		<button ng-click="show.popups.recipe = false" class="close-popup btn btn-sm">close</button>
	</div>

</div> <!-- .row -->