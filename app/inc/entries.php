<div class="row">
	<!-- display food entries -->
	<div class="col-xs-12 col-sm-6">
		<table class="table table-bordered">
			<caption class="bg-blue">entries for the day</caption>
			<tr><th>food</th><th>quantity</th><th>unit</th><th>calories</th><th>recipe</th></tr>
			<tr ng-repeat="entry in food_entries" data-entry-id="{{entry.entry_id}}">
				<td>{{entry.food_name}}</td>
				<td>{{entry.quantity}}</td>
				<td>{{entry.unit_name}}</td>
				<td>{{entry.calories}}</td>
				<td>
					<span ng-if="entry.recipe_name" class="badge">{{entry.recipe_name}}</span>
					<span ng-if="!entry.recipe_name">N/A</span>
				</td>
				<td>
					<i ng-click="deleteFoodEntry(entry.entry_id)" class="delete-item fa fa-times"></i>
				</td>
			</tr>
		</table>
	</div>
	<!-- display exercise entries -->
	<div class="col-xs-12 col-sm-6">
		<table class="table table-bordered">
			<caption class="bg-blue">entries for the day</caption>
			<tr><th>exercise</th><th>quantity</th></tr>

			<tr ng-repeat="entry in exercise_entries" data-entry-id="{{entry.entry_id}}">
				<td>{{entry.name}}</td>
				<td>{{entry.quantity}}</td>
				<td><i class="delete-item fa fa-times"></i></td>
			</tr>
		</table>
	</div>
			
</div> <!-- .row -->
