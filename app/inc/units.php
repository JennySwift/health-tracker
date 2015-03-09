<div ng-if="tab === 'units'">

	<div class="row">

		<div class="col col-sm-6">
			<input ng-keyup="insert($event.keyCode, 'food_units', displayUnitList)" type="text" placeholder="add a new food unit" id="create-new-food-unit" class="form-control">
			<hr>
		
			<div id="display-food-units">
				<li ng-repeat="unit in units.food" class="list-group-item">
					{{unit.name}}
					<i ng-click="deleteItem('food_units', 'unit', unit.id, displayUnitList)" class="delete-item fa fa-times"></i>
				</li>
			</div>
		</div>
		
		<div class="col col-sm-6">
			<input type="text" placeholder="add a new exercise unit" class="form-control">
			<hr>
		
		</div>

	</div> <!-- .row -->

</div> <!-- units tab -->