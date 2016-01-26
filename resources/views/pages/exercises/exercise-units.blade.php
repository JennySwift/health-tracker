<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
    <meta charset="UTF-8">
    <title>tracker</title>
    @include('templates.head-links')
</head>
<body>

@include('templates.header')

<div ng-controller="ExerciseUnitsController" class="container">

    <div id="exercise-units-page">
        <div class="new-exercise-unit-container">
            <div class="new-exercise-unit">
                <input
                        ng-keyup="insertExerciseUnit($event.keyCode)"
                        type="text"
                        placeholder="New exercise unit name"
                        id="create-new-exercise-unit"
                        class="form-control">

                <div>
                    <button
                            ng-click="insertExerciseUnit(13)"
                            class="btn btn-success">
                        Add unit
                    </button>
                </div>
            </div>
        </div>

        <div class="exercise-units-container">
            <div class="exercise-units">
                <li ng-repeat="unit in units | orderBy: 'name'" class="list-group-item">
                    [[unit.name]]
                    <i ng-click="deleteExerciseUnit(unit)" class="delete-item fa fa-times"></i>
                </li>
            </div>
        </div>

    </div>

</div>

@include('templates.footer')

</body>
</html>