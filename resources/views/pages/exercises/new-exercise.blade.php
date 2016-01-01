<div>
    <button ng-click="showNewExerciseFields = !showNewExerciseFields" class="btn btn-success btn-sm">New Exercise</button>
</div>

<div ng-show="showNewExerciseFields" class="new-exercise margin-bottom">

    <div class="flex">
        <div>
            <label for="new-exercise-name">Name</label>
            <input
                    ng-model="newExercise.name"
                    ng-keyup="insertExercise($event.keyCode)"
                    type="text"
                    id="new-exercise-name"
                    name="new-exercise-name"
                    placeholder="name"
                    class="form-control"
            >
        </div>

        <div>
            <label for="new-exercise-description">Description</label>
            <input
                    ng-model="newExercise.description"
                    ng-keyup="insertExercise($event.keyCode)"
                    type="text"
                    id="new-exercise-description"
                    name="new-exercise-description"
                    placeholder="description"
                    class="form-control"
            >
        </div>

        <div class="step-number">
            <label for="new-exercise-step-number">Step Number</label>
            <input
                    ng-model="newExercise.stepNumber"
                    ng-keyup="insertExercise($event.keyCode)"
                    type="text"
                    id="new-exercise-step-number"
                    name="new-exercise-step-number"
                    placeholder="step number"
                    class="form-control"
            >
        </div>

        <div class="priority">
            <label for="new-exercise-priority">Priority</label>
            <input
                    ng-model="newExercise.priority"
                    ng-keyup="insertExercise($event.keyCode)"
                    type="text"
                    id="new-exercise-priority"
                    name="new-exercise-priority"
                    placeholder="priority"
                    class="form-control"
            >
        </div>

        <div class="default-quantity">
            <label for="new-exercise-step-number">Default Quantity</label>
            <input
                    ng-model="newExercise.defaultQuantity"
                    ng-keyup="insertExercise($event.keyCode)"
                    type="text"
                    id="new-exercise-default-quantity"
                    name="new-exercise-default-quantity"
                    placeholder="default quantity"
                    class="form-control"
            >
        </div>

        <div>
            <label for="new-exercise-target">Target</label>
            <input
                    ng-model="newExercise.target"
                    ng-keyup="insertExercise($event.keyCode)"
                    type="text"
                    id="new-exercise-target"
                    name="new-exercise-target"
                    placeholder="target"
                    class="form-control"
            >
        </div>
    </div>

    <div class="flex">
        <div>
            <label for="new-exercise-program">Program</label>
            <select
                    ng-options="program as program.name for program in programs"
                    ng-model="newExercise.program"
                    id="new-exercise-program"
                    class="form-control"
            >
            </select>
        </div>

        <div>
            <label for="new-exercise-series">Series</label>
            <select
                    ng-options="series as series.name for series in exercise_series"
                    ng-model="newExercise.series"
                    id="new-exercise-series"
                    class="form-control"
            >
            </select>
        </div>

        <div>
            <label for="new-exercise-default-unit">Default Unit</label>
            <select
                    ng-options="unit as unit.name for unit in units"
                    ng-model="newExercise.defaultUnit"
                    id="new-exercise-unit"
                    class="form-control"
            >
            </select>
        </div>
    </div>

    <div>
        <button
                ng-click="insertExercise(13)"
                class="btn btn-success"
        >
            Add exercise
        </button>
    </div>

</div>