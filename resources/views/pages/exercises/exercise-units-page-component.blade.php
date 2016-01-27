<script id="exercise-units-page-template" type="x-template">
    <div id="exercise-units-page">
        <div class="new-exercise-unit-container">

            <div class="form-group">
                <label for="new-exercise-unit-name">Name</label>
                <input
                    v-model="newUnit.name"
                    v-on:keyup.13="insertUnit()"
                    type="text"
                    id="new-exercise-unit-name"
                    name="new-exercise-unit-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <button
                        v-on:click="insertUnit()"
                        class="btn btn-success"
                >
                    Add unit
                </button>
            </div>

        </div>

        <div class="exercise-units-container">
            <div class="exercise-units">
                <li v-for="unit in units | orderBy: 'name'" class="list-group-item">
                    @{{ unit.name }}
                    <i v-on:click="deleteUnit(unit)" class="delete-item fa fa-times"></i>
                </li>
            </div>
        </div>

    </div>

</script>