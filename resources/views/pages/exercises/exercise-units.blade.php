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

    <div class="col col-sm-6 col-sm-offset-3">
        <input ng-keyup="insertExerciseUnit($event.keyCode)" type="text" placeholder="add a new exercise unit" id="create-new-exercise-unit" class="form-control">
        <hr>

        <div id="display-exercise-units">
            <li ng-repeat="unit in units" class="list-group-item">
                [[unit.name]]
                <i ng-click="deleteExerciseUnit(unit.id)" class="delete-item fa fa-times"></i>
            </li>
        </div>

    </div>

</div>

@include('templates.footer')

</body>
</html>