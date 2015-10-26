<h3>Steps</h3>
<h5>Use the checkboxes while you make your recipe.</h5>

<div>
    <!-- steps-only show if they exist -->
    <div ng-show="recipe_popup.recipe.steps.length > 0" class="flex">

        <table id="steps-table" class="table table-bordered">
            <tr ng-repeat="step in recipe_popup.recipe.steps">
                <td>
                    <div class="vertical-center">
                        <input type="checkbox">
                    </div>
                </td>
                <td>[[step.text]]</td>
            </tr>
        </table>

        <button ng-click="toggleEditMethod()" class="margin-bottom">edit method</button>

    </div>

    <!-- add method -->
    <div ng-show="recipe_popup.recipe.steps.length < 1" class="flex">
        <h1>Enter the method for this recipe</h1>
        <div id="recipe-method" class="wysiwyg"></div>
        <button ng-click="insertRecipeMethod()">add method</button>
    </div>

    <!-- edit method -->
    <div ng-show="recipe_popup.edit_method" class="transition flex">enter the method for this recipe

        <div class="wysiwyg-container">
            <div id="edit-recipe-method" class="wysiwyg margin-bottom"></div>
            <button ng-click="updateRecipeMethod()">save changes</button>
        </div>

    </div>

</div>