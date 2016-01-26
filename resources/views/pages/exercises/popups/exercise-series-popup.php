<div ng-show="show.popups.exercise_series" ng-click="closePopup($event, 'exercise_series')" class="popup-outer">

	<div class="popup-inner">

        <h3 class="popup-title">[[exercise_series_popup.name]] series</h3>

        <label for="seriesName">Name your series</label>
        <input ng-model="exercise_series_popup.name" type="text" name="seriesName" placeholder="name"/>

		<div>
		    <label for="exericise-series-priority"></label>
		    <input ng-model="exercise_series_popup.priority" type="text" id="exercise-series-priority" name="exercise-series-priority" placeholder="priority" class="form-control"/>
		</div>

		<div class="buttons">
			<button
				ng-click="deleteExerciseSeries(exercise_series_popup)"
				class="btn btn-danger"
			>
				Delete
			</button>

			<button ng-click="updateSeries()" class="btn btn-success save">Save</button>
		</div>
		
	</div>
	
</div>