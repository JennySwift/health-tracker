<!-- for when logging a recipe -->
<div class="row">

	<div
		ng-show="show.popups.temporary_recipe"
		ng-click="closePopup($event, 'temporary_recipe')"
		class="popup-outer"
	>
		<div class="popup-inner">
	
			<h4 class="center">[[temporary_recipe_popup.recipe.name]]</h4>
			<p class="col-sm-12">Editing your recipe here will not change the default contents of your recipe.</p>
			<div class="row margin-bottom">
				<div class="col-sm-10 col-sm-offset-1">

					<input
						ng-model="recipe.portion"
						type="text"
						placeholder="how many portions? 1 for the full recipe, .5 for half, etc."
						class="form-control"
					>
					
					<input
						ng-model="temporary_recipe_popup.food.name"
						ng-keyup="autocompleteTemporaryRecipeFood($event.keyCode); insertOrAutocompleteTemporaryRecipeFood($event.keyCode)"
						ng-blur="show.autocomplete_options.temporary_recipe_foods = false"
						type="text" placeholder="add food to recipe"
						id="temporary-recipe-food-input"
						class="form-control"
					>
					
					<div
						ng-show="showAutocompleteOptions.temporary_recipe_foods"
						class="autocomplete-dropdown"
					>
						<div
							ng-repeat="food in autocomplete_options.temporary_recipe_foods"
							ng-class="{'selected': food.selected}"
							class="autocomplete-dropdown-item"
						>
							[[food.name]]
						</div>
					</div>

					<input
						ng-model="temporary_recipe_popup.quantity"
						ng-keyup="insertOrAutocompleteTemporaryRecipeFood($event.keyCode)"
						type="text"
						placeholder="quantity"
						id="temporary-recipe-popup-food-quantity"
						class="form-control"
					>
					<select
						ng-model="temporary_recipe_popup.unit_id"
						ng-keyup="insertOrAutocompleteTemporaryRecipeFood($event.keyCode)"
						id="temporary-recipe-popup-unit"
						class="form-control"
					>
						<option
							ng-repeat="unit in selected.food.units"
							ng-selected="unit.id === selected.food.default_unit.id"
							ng-value="unit.id"
						>
							[[unit.name]]
						</option>
					</select>
				</div>
			</div>

			<table class="table table-bordered">
				<tr>
					<th>food</th>
					<th>quantity</th>
					<th>unit</th>
				</tr>
				<tr ng-repeat="item in temporary_recipe_popup.contents">
					<td>[[item.name]]</td>
					<td>
						<input ng-model="item.quantity" type="text">
					</td>
					<td>
						<select ng-model="item.unit_id" name="" id="">
							<option
								ng-repeat="unit in item.units"
								ng-selected="unit.name === item.unit_name"
								ng-value="unit.id"
							>
								[[unit.name]]
							</option>
						</select>
					</td>
					<td>
						<i ng-click="deleteFromTemporaryRecipe(item)" class="delete-item fa fa-times"></i>
					</td>
				</tr>
			</table>
			
			<button ng-click="show.popups.temporary_recipe = false" class="close-popup btn btn-sm">cancel</button>
			<button ng-click="insertRecipeEntry()" class="btn btn-default">enter</button>
	
		</div>
		
	</div>

</div> <!-- .row -->