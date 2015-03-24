<div ng-show="show.default_exercise_unit_popup" class="popup-outer">
	<h3>{{selected.exercise.name}}</h3>
	<div id="default-exercise-unit-popup" class="popup-inner">
		<li ng-repeat="unit in units.exercise" class="list-group-item hover pointer" ng-click="updateDefaultExerciseUnit(unit.id)">
			{{unit.name}}
		</li>

		<label ng-repeat="tag in exercise_tags">
			{{tag.name}}
			<input checklist-model="selected.exercise.tags" checklist-value="tag" ng-change="insertOrDeleteTagFromExercise(tag)" type="checkbox">
		</label>

		<button ng-click="show.default_exercise_unit_popup = false">done</button>
		
	</div>

</div>