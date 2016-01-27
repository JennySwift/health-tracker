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
            <tr v-for="item in selectedRecipe.ingredients">
                <td>@{{ item.name }}</td>
                <td>@{{ item.unit_name }}</td>
                <td>@{{ item.quantity }}</td>
                <td>@{{ item.description }}</td>
                <td><i v-on:click="deleteFoodFromRecipe(item.food_id)" class="delete-item fa fa-times"></i></td>
            </tr>
        </table>
    </div>
</div>