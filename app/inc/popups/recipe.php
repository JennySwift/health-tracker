<!-- ==========================recipe popup========================== -->

<div ng-show="show.popups.recipe" ng-click="closePopup($event, 'recipe')" class="popup-outer">

	<div class="popup-inner">

		<h4 class="center">{{recipe.name}}</h4>
		<div class="margin-bottom">
			<div>
				<input ng-model="recipe_popup.food.name" ng-keyup="autocompleteFood($event.keyCode, recipe_popup.food.name); insertOrAutocompleteFoodEntry($event.keyCode)" ng-blur="show.autocomplete.food = false" type="text" placeholder="add food to {{recipe.name}}" id="recipe-popup-food-input" class="form-control">
				
				<div ng-show="show.autocomplete.food" class="autocomplete-dropdown">
					<div ng-repeat="food in autocomplete.food" ng-class="{'selected': food.selected}" class="autocomplete-dropdown-item">{{food.name}}</div>
				</div>
				
				<input ng-model="recipe_popup.food.quantity" ng-keyup="insertOrAutocompleteFoodEntry($event.keyCode)" type="text" placeholder="quantity" id="recipe-popup-food-quantity" class="form-control">
				<select ng-model="unit_id" ng-keyup="insertOrAutocompleteFoodEntry($event.keyCode)" name="" id="" class="form-control">
					<option ng-repeat="unit in selected.food.assoc_units" ng-selected="unit.default_unit === true">{{unit.name}}</option>
				</select>

				<input ng-model="recipe_popup.food.description" ng-keyup="insertOrAutocompleteFoodEntry($event.keyCode)" type="text" placeholder="description" class="form-control">
			</div>
		</div>
			
		<div>
			<div>
				<table class="table table-bordered">
					<tr>
						<th>food</th>
						<th>unit</th>
						<th>quantity</th>
						<th>description</th>
					</tr>
					<tr ng-repeat="item in recipe.contents">
						<td>{{item.food_name}}</td>
						<td>{{item.unit_name}}</td>
						<td>{{item.quantity}}</td>
						<td>{{item.description}}</td>
						<td><i ng-click="deleteFoodFromRecipe(item.id)" class="delete-item fa fa-times"></i></td>
					</tr>
				</table>
			</div>
		</div>
		
		<div>
			<div>
		
				<table id="steps" class="table table-bordered">
					<caption>Method <br><small>Use the checkboxes while you make your recipe.</small></caption>
					<tr ng-repeat="step in recipe.steps">
						<td>
							<div class="vertical-center">
								<input type="checkbox">
							</div>
						</td>
						<td>{{step.text}}</td>
					</tr>
				</table>
		
			</div>
		</div>
	</div>
	<!-- <div class="popup-footer">
		<button ng-click="show.popups.recipe = false" class="close-popup btn btn-sm">close</button>
	</div> -->
	

</div>