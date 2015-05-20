<div ng-show="show.popups.exercise_series" ng-click="closePopup($event, 'exercise_series')" class="popup-outer">

	<div class="popup-inner">

		<h5>[[selected.exercise_series.name]]</h5>

		<div ng-repeat="workout in workouts">
			[[workout.name]]
			<input checklist-model="selected.exercise_series.workouts" checklist-value="workout" type="checkbox">
		</div>

		<button ng-click="deleteAndInsertSeriesIntoWorkouts()">save</button>
		

	</div>
	
</div>