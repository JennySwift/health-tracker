<div ng-controller="MenuEntriesController">
    <table class="table table-bordered">
        <caption>food entries</caption>

        <tr>
            <th>food</th>
            <th>quantity</th>
            <th>unit</th>
            <th>calories</th>
            <th>recipe</th>
            <th></th>
        </tr>

        <tr ng-repeat="entry in menuEntries">
            <td>[[entry.food.name]]</td>
            <td>[[entry.quantity]]</td>
            <td>[[entry.unit.name]]</td>
            <td>[[entry.calories]]</td>
            <td>
                <span ng-if="entry.recipe" class="badge">[[entry.recipe.name]]</span>
                <span ng-if="!entry.recipe">N/A</span>
            </td>
            <td>
                <i ng-if="!entry.recipe" ng-click="deleteMenuEntry(entry)" class="delete-item fa fa-times"></i>
                <i ng-if="entry.recipe" ng-click="showDeleteFoodOrRecipeEntryPopup(entry.id, entry.recipe.id)" class="delete-item fa fa-times"></i>
            </td>
        </tr>
    </table>
</div>