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
						<input checklist-model="food_popup.food_units" checklist-value="unit.id" ng-click="insertOrDeleteUnitInCalories(unit.id, unit.checked)" type="checkbox">
						[[unit.name]]
					</td>
					<td>
						<input ng-value="unit.calories" ng-keyup="updateCalories($event.keyCode, unit.id)" type="text" placeholder="calories" id="food-unit-calories">
					</td>
					<td ng-if="unit.id === food_popup.food.default_unit_id">
						<button class="btn btn-sm default" disabled>default</button>
					</td>
					<td ng-if="unit.id !== food_popup.food.default_unit_id && food_popup.food_units.indexOf(unit.id) !== -1">
						<button  ng-click="updateDefaultUnit(food_popup.food.id, unit.id)" class="btn btn-sm make-default show-hover-item">make default</button>
					</td>
				</tr>
			</table>
			
			<button ng-click="show.food_info = false" class="close-popup btn btn-sm">close</button>
	
		</div>
		
	</div>

</div> <!-- .row -->