<div ng-show="show.popups.exercise_series_history" ng-click="closePopup($event, 'exercise_series_history')" class="popup-outer">

	<div class="popup-inner">

		<table class="table table-bordered">

			<tr>
				<th>date</th>
				<th>exercise</th>
				<th>total</th>
				<th></th>
			</tr>

			<tr ng-repeat="exercise in exercise_series_history">
				<td>{{exercise.date}}</td>
				<td>{{exercise.exercise_name}}</td>
				<td>{{exercise.quantity}} {{exercise.unit_name}}</td>
			</tr>
		</table>

	</div>
	
</div>