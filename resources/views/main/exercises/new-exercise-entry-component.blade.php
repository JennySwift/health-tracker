<script id="new-exercise-entry-template" type="x-template">

    <div class="margin-bottom">

        <autocomplete
                :insert-item-function="insertEntry"
                url="/api/exercises"
                autocomplete-field="exercise"
                id-to-focus-after-autocomplete="new-exercise-entry-quantity"
                autocomplete-field-id="new-exercise-entry-exercise"
        >
        </autocomplete>

        <div class="form-group">
            <label for="new-ingredient-quantity">Quantity</label>
            <input
                    v-model="newEntry.quantity"
                    v-on:keyup.13="insertEntry()"
                    type="text"
                    id="new-exercise-entry-quantity"
                    name="new-exercise-entry-quantity"
                    placeholder="quantity"
                    class="form-control"
            >
        </div>

        <div class="form-group">
            <label for="new-ingredient-unit-name">Unit</label>

            <select
                    v-model="newEntry.unit"
                    v-on:keyup.13="insertEntry()"
                    id="new-exercise-entry-unit"
                    class="form-control"
            >
                <option
                        v-for="unit in units"
                        v-bind:value="unit"
                >
                    @{{ unit.name }}
                </option>
            </select>
        </div>

    </div>

</script>