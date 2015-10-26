<div>
    <input ng-keyup="insertFood($event.keyCode)" type="text" placeholder="add a new food" id="create-new-food" class="form-control">
    <input ng-model="filter.foods" type="text" placeholder="filter foods" class="form-control">
    <hr>
    <div>
        <table class="table table-bordered">
            <tr>
                <th>name</th>
                <th>default</th>
                <th>calories</th>
                <th></th>
            </tr>
            <tr ng-repeat="food in foods | filter:filter.foods | orderBy:'name'">
                <td ng-click="getFoodInfo(food)" class="pointer">[[food.name]]</td>
                <td>[[food.defaultUnit.name]]</td>
                <td>[[food.defaultCalories]]</td>
                <td><i ng-click="deleteFood(food)" class="delete-item fa fa-times"></i></td>
            </tr>
        </table>
    </div>
</div>