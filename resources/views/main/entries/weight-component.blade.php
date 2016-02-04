<script id="weight-template" type="x-template">

<div>
    <li
            v-show="edit_weight !== true"
            v-on:click="editWeight()"
            class="list-group-item pointer"
    >
        <span>Today's weight: </span>
			<span
                    v-if="weight"
                    class="badge"
            >
				@{{ weight.weight | roundNumber 'one' }}
			</span>
    </li>

    <li v-show="editWeight === true" class="list-group-item">
        <input
                v-on:keyup="insertOrUpdateWeight($event.keyCode)"
                type="number"
                placeholder="enter your weight"
                id="weight"
        >
    </li>
</div>

</script>