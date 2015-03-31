<div class="row">

	<div ng-show="show.popups.food_info" ng-click="closePopup($event, 'food_info')" class="popup-outer">
	
		<div class="popup-inner">
	
			<h3 class="center">{{food_popup.name}}</h3>
			
			<ul class="list-group">
				<div ng-repeat="unit in food_popup.info" ng-class="{'default-unit': unit.default_unit === true}" class="list-group-item">
					<input ng-model="checked_unit" ng-click="insertOrDeleteUnitInCalories(unit.id, unit.checked)" type="checkbox" ng-checked="unit.checked === true">
					{{unit.name}}
					<button ng-if="unit.default_unit === true" class="btn btn-sm default" disabled>default</button>
					<button ng-if="unit.default_unit === false && unit.checked === true" ng-click="updateDefaultUnit(unit.id)" class="btn btn-sm make-default show-hover-item">make default</button>
					<input ng-model="unit.calories" ng-keyup="updateCalories($event.keyCode, unit.id, unit.calories)" type="text" value="{{unit.calories}}" placeholder="calories" class="calories-input">
				</div>
			</ul>
			
			<button ng-click="show.food_info = false" class="close-popup btn btn-sm">close</button>
	
		</div>
		
	</div>

</div> <!-- .row -->