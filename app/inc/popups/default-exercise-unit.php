<div ng-show="show.popups.exercise" ng-click="closePopup($event, 'exercise')" class="popup-outer">

	<div id="default-exercise-unit-popup" class="popup-inner">

		<h3 class="center">{{selected.exercise.name}}</h3>

		<div class="flex">
			<div>
				<h5 class="center">step</h5>
				<input ng-keyup="updateExerciseStepNumber($event.keyCode, selected.exercise.id)" type="text" placeholder="step number" id="exercise-step-number">
			</div>

			<div>
				<h5 class="center">series</h5>
				<li ng-repeat="series in exercise_series" class="list-group-item hover pointer" ng-click="updateExerciseSeries(selected.exercise.id, series.id)">
					{{series.name}}
				</li>
			</div>
			
			<div>
				<h5 class="center">default unit</h5>
				<li ng-repeat="unit in units.exercise" class="list-group-item hover pointer" ng-click="updateDefaultExerciseUnit(unit.id)">
					{{unit.name}}
				</li>
			</div>
			
			<div>
				<h5 class="center">default quantity</h5>
				<input ng-keyup="updateDefaultExerciseQuantity($event.keyCode)" type="number" placeholder="enter quantity" id="default-unit-quantity">
			</div>
			
		</div>

		<h5 class="center">tags</h5>

		<div id="default-exercise-unit-popup-checkboxes" class="flex">
			<label ng-repeat="tag in exercise_tags">
				{{tag.name}}
				<input checklist-model="selected.exercise.tags" checklist-value="tag" type="checkbox">
			</label>
		</div>

		<button ng-click="insertTagsInExercise()">done</button>

	</div>
	
</div>