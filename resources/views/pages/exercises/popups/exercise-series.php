<div ng-show="show.popups.exercise_series" ng-click="closePopup($event, 'exercise_series')" class="popup-outer">

	<div class="popup-inner">

		<h5>Check the workouts which should include the [[exercise_series_popup.name]] series</h5>

		<div ng-repeat="workout in workouts">
			[[workout.name]]
			<input checklist-model="exercise_series_popup.workout_ids" checklist-value="workout.id" type="checkbox">
		</div>

		<button ng-click="deleteAndInsertSeriesIntoWorkouts()" class="btn btn-success save">save</button>
		
	</div>
	
</div>