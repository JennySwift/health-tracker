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

        <tr ng-repeat="entry in entries.menu" data-entry-id="[[entry.entry_id]]">
            <td>[[entry.food_name]]</td>
            <td>[[entry.quantity]]</td>
            <td>[[entry.unit_name]]</td>
            <td>[[entry.calories]]</td>
            <td>
                <span ng-if="entry.recipe_name" class="badge">[[entry.recipe_name]]</span>
                <span ng-if="!entry.recipe_name">N/A</span>
            </td>
            <td>
                <i ng-if="!entry.recipe_name" ng-click="deleteFoodEntry(entry.entry_id)" class="delete-item fa fa-times"></i>
                <i ng-if="entry.recipe_name" ng-click="showDeleteFoodOrRecipeEntryPopup(entry.entry_id, entry.recipe_id)" class="delete-item fa fa-times"></i>
            </td>
        </tr>
    </table>
</div>