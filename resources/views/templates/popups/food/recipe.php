<!-- ==========================recipe popup========================== -->

<div ng-show="show.popups.recipe" ng-click="closePopup($event, 'recipe')" class="popup-outer">

	<div class="popup-inner">

		<h1 class="center">[[recipe_popup.recipe.name]]</h1>
		<hr>

		<h3>Add ingredient</h3>

		<div class="margin-bottom">
			<div>
				<input ng-model="recipe_popup.food.name" ng-keyup="autocompleteFood($event.keyCode); insertOrAutocompleteFoodEntry($event.keyCode)" ng-blur="show.autocomplete_options.foods = false" type="text" placeholder="add food to [[recipe_popup.recipe.name]]" id="recipe-popup-food-input" class="form-control">
				
				<div ng-show="show.autocomplete_options.foods" class="autocomplete-dropdown">
					<div ng-repeat="food in autocomplete_options.foods" ng-class="{'selected': food.selected}" class="autocomplete-dropdown-item">[[food.name]]</div>
				</div>
				
				<input ng-model="recipe_popup.food.quantity" ng-keyup="insertOrAutocompleteFoodEntry($event.keyCode)" type="text" placeholder="quantity" id="recipe-popup-food-quantity" class="form-control">
				<select ng-model="unit_id" ng-keyup="insertOrAutocompleteFoodEntry($event.keyCode)" name="" id="" class="form-control">
					<option ng-repeat="unit in selected.food.assoc_units" ng-selected="unit.default_unit === true">[[unit.name]]</option>
				</select>

				<input ng-model="recipe_popup.food.description" ng-keyup="insertOrAutocompleteFoodEntry($event.keyCode)" type="text" placeholder="description" class="form-control">
			</div>
		</div>

		<hr>

		<h3>Ingredients</h3>
			
		<div>
			<div>
				<table class="table table-bordered">
					<tr>
						<th>food</th>
						<th>unit</th>
						<th>quantity</th>
						<th>description</th>
					</tr>
					<tr ng-repeat="item in recipe_popup.contents">
						<td>[[item.name]]</td>
						<td>[[item.unit_name]]</td>
						<td>[[item.quantity]]</td>
						<td>[[item.description]]</td>
						<td><i ng-click="deleteFoodFromRecipe(item.id)" class="delete-item fa fa-times"></i></td>
					</tr>
				</table>
			</div>
		</div>

		<hr>

		<h3>Steps</h3>
		<h5>Use the checkboxes while you make your recipe.</h5>
		
		<div>

			<div ng-show="recipe_popup.steps.length > 0">
				<button ng-click="editRecipeMethod()">edit method</button>
				<table id="steps" class="table table-bordered">
					<tr ng-repeat="step in recipe_popup.steps">
						<td>
							<div class="vertical-center">
								<input type="checkbox">
							</div>
						</td>
						<td>[[step.text]]</td>
					</tr>
				</table>
			</div>

			<!-- add method -->
			<div ng-show="recipe_popup.steps.length < 1">enter the method for this recipe
				<div id="recipe-method" class="wysiwyg"></div>
				<button ng-click="insertRecipeMethod()">add method</button>
			</div>

			<!-- edit method -->
			<div ng-show="edit.recipe_method">enter the method for this recipe
				<div id="edit-recipe-method" class="wysiwyg"></div>
				<button ng-click="updateRecipeMethod()">save changes</button>
			</div>

		</div>

		<hr>

		<h3 class="center">Tags</h3>

		<div>

			<ul class="list-group">
				<li ng-repeat="tag in recipe_tags" class="list-group-item">
					<span>[[tag.name]]</span>
					<input checklist-model="recipe_popup.tags" checklist-value="tag.id" ng-click="recipe_popup.notification = 'Tags need saving.'" type="checkbox">
				</li>
			</ul>

			<button ng-click="insertTagsIntoRecipe()">save tags</button>
			<div>[[recipe_popup.notification]]</div>

		</div>

	</div>
	<!-- <div class="popup-footer">
		<button ng-click="show.popups.recipe = false" class="close-popup btn btn-sm">close</button>
	</div> -->
	

</div>