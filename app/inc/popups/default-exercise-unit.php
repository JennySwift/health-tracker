<div ng-show="show.default_exercise_unit_popup" class="popup-outer">
	<h3>{{selected.exercise.name}}</h3>
	<div id="default-exercise-unit-popup" class="popup-inner">

		<h5>step</h5>
		<input ng-keyup="updateExerciseStepNumber($event.keyCode, selected.exercise.id)" type="text" placeholder="step number" id="exercise-step-number">

		<h5>default unit</h5>
		<li ng-repeat="unit in units.exercise" class="list-group-item hover pointer" ng-click="updateDefaultExerciseUnit(unit.id)">
			{{unit.name}}
		</li>

		<h5>series</h5>
		<li ng-repeat="series in exercise_series" class="list-group-item hover pointer" ng-click="updateExerciseSeries(selected.exercise.id, series.id)">
			{{series.name}}
		</li>

		<h5>default quantity</h5>
		<input ng-keyup="updateDefaultExerciseQuantity($event.keyCode)" type="number" placeholder="enter quantity" id="default-unit-quantity">

		<h5>tags</h5>
		<div id="default-exercise-unit-popup-checkboxes">
			<label ng-repeat="tag in exercise_tags">
				{{tag.name}}
				<input checklist-model="selected.exercise.tags" checklist-value="tag" type="checkbox">
			</label>
		</div>

		<button ng-click="insertTagsInExercise()">done</button>
		
	</div>

</div>