<div>
    <input ng-model="new_item.recipe.name" ng-keyup="insertRecipe($event.keyCode)" type="text" placeholder="add a new recipe" id="create-new-recipe" class="form-control">
    <input ng-model="filter.recipes.name" ng-keyup="filterRecipes()" type="text" placeholder="filter recipes by name" id="filter-recipes" class="form-control">
    <hr>

    <div>

        <table class="table table-bordered">
            <tr>
                <th>name</th>
                <th>calories</th>
                <th>tags</th>
                <th></th>
            </tr>
            <tr ng-repeat="recipe in recipes.filtered | orderBy: 'name'">
                <td ng-click="showRecipePopup(recipe)" class="pointer">[[recipe.name]]</td>
                <td>calories</td>
                <td>
                    <span ng-repeat="tag in recipe.tags" class="badge">[[tag.name]]</span>
                </td>
                <td><i ng-click="deleteRecipe(recipe)" class="delete-item fa fa-times"></i></td>
            </tr>
        </table>

    </div>
</div>