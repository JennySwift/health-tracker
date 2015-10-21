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
            <tr ng-repeat="item in all_foods_with_units | filter:filter.foods" data-food-id="[[item.id]]">
                <td ng-click="getFoodInfo(item)" class="pointer">[[item.name]]</td>
                <td>[[item.default_unit.name]]</td>
                <td>[[item.default_unit_calories]]</td>
                <td><i ng-click="deleteFood(item)" class="delete-item fa fa-times"></i></td>
            </tr>
        </table>
    </div>
</div>