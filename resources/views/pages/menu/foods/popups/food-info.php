<div class="row">

	<div ng-show="show.popups.food_info" ng-click="closePopup($event, 'food_info')" id="food-info-popup" class="popup-outer">
	
		<div class="popup-inner">
	
			<h3 class="center">[[food_popup.food.name]]</h3>

			<table class="table">
				<tr>
					<th>unit</th>
					<th>calories</th>
					<th>default</th>
				</tr>
				<tr ng-repeat="unit in food_popup.all_food_units" ng-class="{'default-unit': unit.id === food_popup.food.default_unit_id}">
					<td>
						<input checklist-model="food_popup.food_units" checklist-value="unit.id" ng-click="insertOrDeleteUnitInCalories(unit.id)" type="checkbox">
						[[unit.name]]
					</td>
					<td>
						<input ng-model="unit.calories" ng-keyup="updateCalories($event.keyCode, unit.id, unit.calories)" type="text" placeholder="calories" id="food-unit-calories" class="form-control">
					</td>
					<td>
						<button ng-if="unit.id === food_popup.food.default_unit_id" class="btn btn-sm default" disabled>default</button>
						<button ng-if="unit.id !== food_popup.food.default_unit_id && food_popup.food_units.indexOf(unit.id) !== -1" ng-click="updateDefaultUnit(food_popup.food.id, unit.id)" class="btn btn-sm make-default show-hover-item">make default</button>
					</td>
				</tr>
			</table>
			
			<button ng-click="show.popups.food_info = false" class="close-popup btn btn-sm">close</button>
	
		</div>
		
	</div>

</div> <!-- .row -->