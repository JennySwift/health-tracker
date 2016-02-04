<script id="food-units-page-template" type="x-template">

    <div class="row">

        <div class="col col-sm-6 col-sm-offset-3">
            <div class="form-group">
                <label for="new-food-unit-name">Name</label>
                <input
                    v-model="newUnit.name"
                    v-on:keyup.13="insertUnit()"
                    type="text"
                    id="new-food-unit-name"
                    name="new-food-unit-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>

            <button
                v-on:click="insertUnit()"
                class="btn btn-success"
            >
                Add unit
            </button>

            <hr>

            <div id="display-food-units">
                <li
                    v-for="unit in units
                        | orderBy 'name'"
                    class="list-group-item"
                >
                    @{{ unit.name }}
                    <i v-on:click="deleteUnit(unit)" class="delete-item fa fa-times"></i>
                </li>
            </div>
        </div>

    </div>

</script>