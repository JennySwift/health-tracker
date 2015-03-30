<div ng-show="show.popups.similar_names" ng-click="closePopup($event, 'similar_names')" class="popup-outer">

	<div class="popup-inner">

		<p>The following foods you entered in your recipe do not exist in your foods, but similar foods were found.</p>
		<p>Check the existing food to use that in your recipe, or check the specified food to create a new food with the specified name.</p>

		<table class="table table-bordered">

			<tr>
				<th>specified food</th>
				<th>existing food</th>
			</tr>

			<tr ng-repeat="item in quick_recipe.similar_names.foods">
				<td>
					{{item.specified_food.name}}
					<div class="vertical-center">
						<input ng-model="item.checked" ng-value="item.specified_food.name" type="radio">
					</div>
				</td>
				<td>
					{{item.existing_food.name}}
					<div class="vertical-center">
						<input ng-model="item.checked" ng-value="item.existing_food.name" type="radio">
					</div>
				</td>
			</tr>

		</table>

		<table class="table table-bordered">

			<th>specified unit</th>
			<th>existing unit</th>

			<tr ng-repeat="item in quick_recipe.similar_names.units">
				<td>
					{{item.specified_unit.name}}
					<div class="vertical-center">
						<input ng-model="item.checked" ng-value="item.specified_unit.name" type="radio">
					</div>
				</td>
				<td>
					{{item.existing_unit.name}}
					<div class="vertical-center">
						<input ng-model="item.checked" ng-value="item.existing_unit.name" type="radio">
					</div>
				</td>
			</tr>
		</table>

		<div>
			<button ng-click="show.popups.similar_names = false">cancel</button>
			<button ng-click="quickRecipeFinish()">go</button>
		</div>

	</div>
</div>