<script id="weight-template" type="x-template">

<div>
    <li
        v-show="!editingWeight"
        v-on:click="showNewWeightOrEditWeightFields()"
        class="list-group-item pointer"
    >
        <span>Today's weight: </span>
			<span
                v-if="weight.weight"
                class="badge"
            >
				@{{ weight.weight | roundNumber 1' }}
			</span>
            <span
                v-if="!weight.weight"
                class="badge"
            >
				-
			</span>
    </li>

    <li
        v-show="addingNewWeight === true"
        class="list-group-item"
    >
        <div class="form-group">
            <label for="new-weight-weight">Weight</label>
            <input
                v-model="newWeight.weight"
                v-on:keyup.13="insertWeight()"
                type="text"
                id="new-weight-weight"
                name="new-weight-weight"
                placeholder="weight"
                class="form-control"
            >
        </div>

        <div>
            <button v-on:click="addingNewWeight = false" class="btn btn-xs btn-default">Cancel</button>
            <button v-on:click="insertWeight()" class="btn btn-xs btn-success">Save</button>
        </div>

    </li>

    <li
        v-show="editingWeight === true"
        class="list-group-item"
    >
        <div class="form-group">
            <label for="edit-weight-weight">Weight</label>
            <input
                    v-model="weight.weight"
                    v-on:keyup.13="updateWeight()"
                    type="text"
                    id="edit-weight-weight"
                    name="edit-weight-weight"
                    placeholder="weight"
                    class="form-control"
            >
        </div>

        <div>
            <button v-on:click="editingWeight = false" class="btn btn-xs btn-default">Cancel</button>
            <button v-on:click="updateWeight()" class="btn btn-xs btn-success">Save</button>
        </div>

    </li>
</div>

</script>