<div ng-show="show.popups.exercise_series" ng-click="closePopup($event, 'exercise_series')" class="popup-outer">

	<div class="popup-inner">

		<h5>[[exercise_series_popup.series.name]]</h5>

		<div ng-repeat="workout in workouts">
			[[workout.name]]
			<input checklist-model="exercise_series_popup.workouts" checklist-value="workout.id" type="checkbox">
		</div>

		<button ng-click="deleteAndInsertSeriesIntoWorkouts()">save</button>
		
	</div>
	
</div>