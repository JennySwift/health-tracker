<script id="recipes-template" type="x-template">

    <div>

        <div class="form-group">
            <label for="new-recipe-name">Name</label>
            <input
                v-model="newRecipe.name"
                v-on:keyup.13="insertRecipe()"
                type="text"
                id="new-recipe-name"
                name="new-recipe-name"
                placeholder="name"
                class="form-control"
            >
        </div>

        <div class="form-group">
            <label for="recipes-filter">Filter</label>
            <input
                v-model="recipesNameFilter"
                type="text"
                id="recipes-filter"
                name="recipes-filter"
                placeholder="filter"
                class="form-control"
            >
        </div>

        <hr>

        <div>

            <table class="table table-bordered">
                <tr>
                    <th>name</th>
                    <th>calories</th>
                    <th>tags</th>
                    <th></th>
                </tr>
                <tr
                    v-for="recipe in recipes
                        | recipesFilter
                        | orderBy 'name'"
                >
                    <td v-on:click="showRecipePopup(recipe)" class="pointer">@{{ recipe.name }}</td>
                    <td>calories</td>
                    <td>
                        <span v-for="tag in recipe.tags.data" class="badge">@{{ tag.name }}</span>
                    </td>
                    <td><i v-on:click="deleteRecipe(recipe)" class="delete-item fa fa-times"></i></td>
                </tr>
            </table>

        </div>
    </div>

</script>