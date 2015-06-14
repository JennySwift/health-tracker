<div ng-show="show.popups.exercise" ng-click="closePopup($event, 'exercise')" class="popup-outer">

	<div id="exercise-popup" class="popup-inner">

		<h3 class="center">[[exercise_popup.exercise.name]]</h3>

		<div class="flex">
			<div>
				<h5 class="center">step</h5>
				<input ng-keyup="updateExerciseStepNumber($event.keyCode, selected.exercise)" type="text" placeholder="step number" id="exercise-step-number" class="form-control">
			</div>

			<div>
				<h5 class="center">series</h5>
				<li ng-repeat="series in exercise_series" class="list-group-item hover pointer" ng-click="updateExerciseSeries(selected.exercise.id, series.id)">
					[[series.name]]
				</li>
			</div>
			
			<div>
				<h5 class="center">default unit</h5>
				<li ng-repeat="unit in units" class="list-group-item hover pointer" ng-click="updateDefaultExerciseUnit(unit.id)">
					[[unit.name]]
				</li>
			</div>
			
			<div>
				<h5 class="center tooltipster" title="This figure will be used, along with the default unit, when using the feature to quickly log a set of your exercise">default quantity</h5>
				<input ng-keyup="updateDefaultExerciseQuantity($event.keyCode)" type="number" placeholder="enter quantity" id="default-unit-quantity" class="form-control">
			</div>

			<div>
				<h5 class="center">tags</h5>
				
				<ul class="list-group">
					<li ng-repeat="tag in exercise_popup.all_exercise_tags" class="list-group-item">
						<span>[[tag.name]]</span>
						<input checklist-model="exercise_popup.tags" checklist-value="tag.id" type="checkbox">
					</li>
				</ul>
			</div>
			
		</div>

		<button ng-click="insertTagsInExercise()">done</button>

	</div>
	
</div>