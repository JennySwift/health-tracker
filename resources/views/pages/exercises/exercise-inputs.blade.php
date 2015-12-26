<div id="exercise-filters" class="margin-bottom">
    <h5>Filters</h5>
    <div class="flex">
        <input ng-model="filter.exercises.name" type="text" placeholder="name" class="form-control"/>
        <input ng-model="filter.exercises.description" type="text" placeholder="description" class="form-control"/>
        <input ng-model="filter.exercises.series" type="text" placeholder="series" class="form-control"/>
        <input ng-model="filter.exercises.priority" type="text" placeholder="priority" class="form-control"/>
    </div>
</div>

<div class="margin-bottom">
    <h2 class="center">Exercises</h2>
    <input
        ng-keyup="insertExercise($event.keyCode)"
        type="text"
        placeholder="Add a new exercise"
        id="create-new-exercise"
        class="form-control">

    <input
        ng-keyup="insertExercise($event.keyCode)"
        type="text"
        placeholder="description"
        id="exercise-description"
        class="form-control">

    <button
        ng-click="insertExercise(13)"
        class="btn btn-success">
        Add exercise
    </button>

</div>