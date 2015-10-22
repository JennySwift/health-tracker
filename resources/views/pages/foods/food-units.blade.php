<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
    <meta charset="UTF-8">
    <title>tracker</title>
    @include('templates.head-links')
</head>
<body>

@include('templates.header')

<div ng-controller="FoodUnitsController" class="container">

    <div class="row">

        <div class="col col-sm-6 col-sm-offset-3">
            <input ng-keyup="insertFoodUnit($event.keyCode)" type="text" placeholder="add a new food unit" id="create-new-food-unit" class="form-control">
            <hr>

            <div id="display-food-units">
                <li ng-repeat="unit in units" class="list-group-item">
                    [[unit.name]]
                    <i ng-click="deleteFoodUnit(unit.id)" class="delete-item fa fa-times"></i>
                </li>
            </div>
        </div>

    </div>

</div>

@include('templates.footer')

</body>
</html>