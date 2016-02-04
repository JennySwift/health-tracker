<script id="menu-entries-template" type="x-template">

    <div>
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

            <tr v-for="entry in menuEntries">
                <td>@{{ entry.food.name }}</td>
                <td>@{{ entry.quantity }}</td>
                <td>@{{ entry.unit.name }}</td>
                <td>@{{ entry.calories }}</td>
                <td>
                <span
                        v-if="entry.recipe"
                        class="badge"
                >
                    @{{ entry.recipe.name }}
                </span>
                <span
                        v-if="!entry.recipe"
                >
                    N/A
                </span>
                </td>
                <td>
                    <i
                            v-if="!entry.recipe"
                            v-on:click="deleteMenuEntry(entry)"
                            class="delete-item fa fa-times">
                    </i>
                    <i
                            v-if="entry.recipe"
                            v-on:click="showDeleteFoodOrRecipeEntryPopup(entry.id, entry.recipe.id)"
                            class="delete-item fa fa-times"
                    >
                    </i>
                </td>
            </tr>
        </table>
    </div>

</script>