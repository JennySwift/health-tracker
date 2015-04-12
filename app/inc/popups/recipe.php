<!-- ==========================recipe popup========================== -->

<div ng-show="show.popups.recipe" ng-click="closePopup($event, 'recipe')" class="popup-outer">

	<div class="popup-inner">

		<h4 class="center">{{recipe.name}}</h4>
		<div class="margin-bottom">
			<div>
				<input ng-model="recipe_popup.food.name" ng-keyup="autocompleteFood($event.keyCode); insertOrAutocompleteFoodEntry($event.keyCode)" ng-blur="show.autocomplete_options.foods = false" type="text" placeholder="add food to {{recipe.name}}" id="recipe-popup-food-input" class="form-control">
				
				<div ng-show="show.autocomplete_options.foods" class="autocomplete-dropdown">
					<div ng-repeat="food in autocomplete_options.foods" ng-class="{'selected': food.selected}" class="autocomplete-dropdown-item">{{food.name}}</div>
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
		
				<table ng-show="recipe.steps.length > 0" id="steps" class="table table-bordered">
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

				<div ng-show="recipe.steps.length < 1">enter the method for this recipe
					<div id="recipe-method" class="wysiwyg"></div>
					<button ng-click="insertRecipeMethod()">add method</button>
				</div>
		
			</div>
		</div>

		<div>
			<h3 class="center">tags</h3>

			<ul>
				<li ng-repeat="tag in recipe_tags">
					<span>{{tag.name}}</span>
					<input checklist-model="selected.recipe.tags" checklist-value="tag" type="checkbox">
				</li>
			</ul>

			<button ng-click="insertTagsIntoRecipe()">save tags</button>

		</div>

	</div>
	<!-- <div class="popup-footer">
		<button ng-click="show.popups.recipe = false" class="close-popup btn btn-sm">close</button>
	</div> -->
	

</div>