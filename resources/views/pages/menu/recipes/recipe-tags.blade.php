<div ng-controller="RecipeTagsController">

    <input
        ng-model="new_item.recipe_tag"
        ng-keyup="insertRecipeTag($event.keyCode)"
        type="text"
        placeholder="add a new recipe tag"
        id="create-new-recipe-tag"
        class="form-control">

    <input
        ng-model="filter.recipe_tags"
        type="text"
        placeholder="filter recipe tags"
        class="form-control">

    <hr>

    <div>

        <table class="table table-bordered">
            <tr>
                <th>name</th>
                <th class="tooltipster" title="check to filter recipes by the tag">filter</th>
                <th></th>
            </tr>
            <tr ng-repeat="tag in tags | filter:filter.recipe_tags | orderBy: 'name'">
                <td>[[tag.name]]</td>

                <td>
                    <input
                        checklist-model="filter.recipes.tag_ids"
                        checklist-value="tag.id"
                        type="checkbox">

                </td>

                <td><i ng-click="deleteRecipeTag(tag)" class="delete-item fa fa-times"></i></td>
            </tr>
        </table>

    </div>
</div>