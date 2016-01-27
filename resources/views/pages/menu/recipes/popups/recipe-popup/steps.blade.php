<h3>Steps</h3>
<h5>Use the checkboxes while you make your recipe.</h5>

<div v-if="selectedRecipe.steps">
    <!-- steps-only show if they exist -->
    <div v-show="selectedRecipe.steps.length > 0" class="flex">

        <table id="steps-table" class="table table-bordered">
            <tr v-for="step in selectedRecipe.steps">
                <td>
                    <div class="vertical-center">
                        <input type="checkbox">
                    </div>
                </td>
                <td>@{{ step.text }}</td>
            </tr>
        </table>

        <button v-on:click="toggleEditMethod()" class="margin-bottom">edit method</button>

    </div>

    <!-- add method -->
    <div v-show="selectedRecipe.steps.length < 1" class="flex">
        <h1>Enter the method for this recipe</h1>
        <div id="recipe-method" class="wysiwyg"></div>
        <button v-on:click="insertRecipeMethod()">add method</button>
    </div>

    <!-- edit method -->
    <div v-show="editingMethod" class="transition flex">enter the method for this recipe

        <div class="wysiwyg-container">
            <div id="edit-recipe-method" class="wysiwyg margin-bottom"></div>
        </div>

    </div>

</div>