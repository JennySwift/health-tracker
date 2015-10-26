<h3>Ingredients</h3>

<div>
    <div>
        <table class="table table-bordered">
            <tr>
                <th>food</th>
                <th>unit</th>
                <th>quantity</th>
                <th>description</th>
                <th>x</th>
            </tr>
            <tr ng-repeat="item in recipe_popup.contents">
                <td>[[item.name]]</td>
                <td>[[item.unit_name]]</td>
                <td>[[item.quantity]]</td>
                <td>[[item.description]]</td>
                <td><i ng-click="deleteFoodFromRecipe(item.food_id)" class="delete-item fa fa-times"></i></td>
            </tr>
        </table>
    </div>
</div>