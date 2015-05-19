<div ng-show="show.popups.exercise_series_history" ng-click="closePopup($event, 'exercise_series_history')" class="popup-outer">

	<div class="popup-inner">

		<table class="table table-bordered">

			<tr>
				<th>date</th>
				<th>days ago</th>
				<th>exercise</th>
				<!-- <th>description</th> -->
				<th>step</th>
				<th>sets</th>
				<th>total</th>
			</tr>

			<tr ng-repeat="exercise in exercise_series_history">
				<td>{{exercise.date}}</td>
				<td>{{exercise.days_ago}}</td>
				<td>{{exercise.name}}</td>
				<!-- <td>{{exercise.description}}</td> -->
				<td>{{exercise.step_number}}</td>
				<td>{{exercise.sets}}</td>
				<td>{{exercise.total}} {{exercise.unit_name}}</td>
			</tr>
		</table>

	</div>
	
</div>