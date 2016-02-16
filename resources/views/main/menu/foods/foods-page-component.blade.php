<script id="foods-page-template" type="x-template">

<div>
    <food-popup></food-popup>

    <div id="foods">

        <div class="flex">

            <div>

                <div class="form-group">
                    <label for="new-food-name">New Food</label>
                    <input
                        v-model="newFood.name"
                        v-on:keyup.13="insertFood()"
                        type="text"
                        id="new-food-name"
                        name="new-food-name"
                        placeholder="name"
                        class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="foods-filter">Filter foods</label>
                    <input
                        v-model="foodsFilter"
                        type="text"
                        id="foods-filter"
                        name="foods-filter"
                        placeholder="filter foods"
                        class="form-control"
                    >
                </div>

                <hr>
                <div>
                    <table class="table table-bordered">
                        <tr>
                            <th>name</th>
                            <th>default</th>
                            <th>calories</th>
                            <th></th>
                        </tr>
                        <tr v-for="food in foods | filterBy foodsFilter in 'name'">
                            <td v-on:click="getFoodInfo(food)" class="pointer">@{{ food.name }}</td>
                            <td><span v-if="food.defaultUnit">@{{ food.defaultUnit.data.name }}</span></td>
                            <td>@{{ food.defaultCalories }}</td>
                            <td><i v-on:click="deleteFood(food)" class="delete-item fa fa-times"></i></td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

</script>