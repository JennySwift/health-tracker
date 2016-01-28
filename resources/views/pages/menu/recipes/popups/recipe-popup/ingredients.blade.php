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
            <tr v-for="ingredient in selectedRecipe.ingredients">
                <td>@{{ ingredient.name }}</td>
                <td>@{{ ingredient.unit_name }}</td>
                <td>@{{ ingredient.quantity }}</td>
                <td>@{{ ingredient.description }}</td>
                <td><i v-on:click="deleteIngredientFromRecipe(ingredient)" class="delete-item fa fa-times"></i></td>
            </tr>
        </table>
    </div>
</div>