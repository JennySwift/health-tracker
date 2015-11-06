<div class="entry-inputs">
    <div>

        <input
            ng-model="newEntry.name"
            ng-keyup="autocompleteExercise($event.keyCode); insertOrAutocompleteExerciseEntry($event.keyCode, 'exercise')"
            ng-blur="showAutocompleteOptions.exercises = false"
            type="text"
            placeholder="exercise"
            id="exercise"
            class="form-control">

        <div ng-show="showAutocompleteOptions.exercises">
            <div
                ng-repeat="item in autocomplete_options.exercises"
                ng-class="{'selected': item.selected}"
                ng-mousedown="finishExerciseAutocomplete($scope.autocomplete_options.exercises, item)"
                class="autocomplete-dropdown-item pointer">
                [[item.name]] ([[item.description]])
            </div>
        </div>

        <input ng-model="newEntry.quantity" ng-keyup="insertOrAutocompleteExerciseEntry($event.keyCode, 'exercise')" type="text" id="exercise-quantity" placeholder="quantity" class="form-control">

        <select
            ng-model="selectedExercise.unit_id"
            ng-keyup="insertOrAutocompleteExerciseEntry($event.keyCode, 'exercise')"
            id="exercise-unit"
            class="form-control">
            <option
                ng-repeat="unit in exerciseUnits"
                ng-selected="unit.id === selectedExercise.default_unit_id"
                value="[[unit.id]]"
                id="exercise-unit">
                [[unit.name]]
            </option>
        </select>
    </div>
</div>