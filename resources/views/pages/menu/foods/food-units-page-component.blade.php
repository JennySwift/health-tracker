<script id="food-units-page-template" type="x-template">

    <div class="row">

        <div class="col col-sm-6 col-sm-offset-3">
            <input
                    v-on:keyup="insertFoodUnit($event.keyCode)"
                    type="text"
                    placeholder="add a new food unit"
                    id="create-new-food-unit"
                    class="form-control">

            <button
                    v-on:click="insertFoodUnit(13)"
                    class="btn btn-success">
                Add unit
            </button>

            <hr>

            <div id="display-food-units">
                <li v-for="unit in units | orderBy:'name'" class="list-group-item">
                    @{{ unit.name }}
                    <i v-on:click="deleteFoodUnit(unit)" class="delete-item fa fa-times"></i>
                </li>
            </div>
        </div>

    </div>

</script>