<div class="margin-bottom">
    <input ng-model="filter.exercises.name" type="text" placeholder="filter exercises by name" class="form-control"/>
    <input ng-model="filter.exercises.description" type="text" placeholder="filter exercises by description" class="form-control"/>
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