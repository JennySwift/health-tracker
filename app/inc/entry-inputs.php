<!-- food entry -->
<div class="col-xs-6 col-sm-4 margin-bottom">
	<div>	
		<input ng-model="menu_item.name" ng-keyup="autocomplete('menu', $event.keyCode); enter($event.keyCode, 'menu')" ng-blur="autocomplete.menu = ''" type="text" placeholder="food" id="food" class="form-control">
		
		<div>
			<div ng-repeat="item in autocomplete.menu" ng-class="{'selected': $first}" data-id="{{item.id}}" data-type="{{item.type}}" class="autocomplete-dropdown-item">{{item.name}}</div>
		</div>

		<div>
			<input ng-model="food.quantity" ng-keyup="enter($event.keyCode, 'menu')" type="text" placeholder="quantity" id="food-quantity" class="form-control">
			<select ng-keyup="enter($event.keyCode, 'menu')" name="" id="food-unit" class="form-control">
				<option ng-repeat="unit in assoc_units" ng-selected="unit.default_unit === true" data-unit-id="{{unit.unit_id}}">{{unit.unit_name}}</option>
			</select>
		</div>
	</div>
</div>

<!-- exercise entry -->
<div class="col-xs-6 col-sm-4 margin-bottom">
	<div>
		<input ng-keyup="enter($event.keyCode, 'exercise')" type="text" placeholder="exercise" id="exercise" class="form-control">
		<div id="exercise-autocomplete" class="autocomplete-dropdown"></div>
		<input ng-keyup="enter($event.keyCode, 'exercise')" type="text" placeholder="quantity" class="form-control">
	</div>
</div>