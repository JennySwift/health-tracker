<div ng-show="show.popups.exercise" ng-click="closePopup($event, 'exercise')" class="popup-outer">

	<div id="exercise-popup" class="popup-inner">

		<h3 class="center">[[exercise_popup.name]]</h3>

		<div class="flex">
			<div>
				<h5 class="center">step</h5>
				<input
                    ng-model="exercise_popup.stepNumber"
                    type="text"
                    placeholder="step number"
                    class="form-control">
			</div>

			<div>
				<h5 class="center">series</h5>

				<li
                    ng-repeat="series in exercise_series"
                    class="list-group-item hover pointer"
                    ng-class="{'selected': series.id === exercise_popup.series.id}"
                    ng-click="exercise_popup.series.id = series.id">
					[[series.name]]
				</li>

			</div>
			
			<div>
				<h5 class="center">default unit</h5>
				<li
                    ng-repeat="unit in units"
                    class="list-group-item hover pointer"
                    ng-class="{'selected': unit.id === exercise_popup.defaultUnit.id}"
                    ng-click="exercise_popup.defaultUnit.id = unit.id">
					[[unit.name]]
				</li>
			</div>
			
			<div>
				<h5 class="center tooltipster" title="This figure will be used, along with the default unit, when using the feature to quickly log a set of your exercise">default quantity</h5>

                <input
                    ng-model="exercise_popup.defaultQuantity"
                    type="text"
                    placeholder="enter quantity"
                    class="form-control">
			</div>

			<div>
				<h5 class="center">tags</h5>
				
				<ul class="list-group">
					<li
                        ng-repeat="tag in exercise_tags"
                        class="list-group-item">
						<span>[[tag.name]]</span>

						<input
                            checklist-model="exercise_popup.tag_ids"
                            checklist-value="tag.id"
                            type="checkbox">
					</li>
				</ul>
			</div>
			
		</div>

		<button ng-click="updateExercise()">Save</button>

	</div>
	
</div>