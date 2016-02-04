<script id="autocomplete-template" type="x-template">

<div>
    <div class="form-group">
        <label :for="autocompleteFieldId">@{{ autocompleteField | capitalize }}</label>
        <input
                v-model="chosenOption.name"
                v-on:keyup="respondToKeyup($event.keyCode)"
                v-on:blur="showDropdown = false"
                type="text"
                :id="autocompleteFieldId"
                :name="autocompleteFieldId"
                placeholder="food"
                class="form-control"
        >
    </div>

    <div
            v-show="showDropdown"
            class="autocomplete-dropdown"
    >
        <div
                v-for="option in autocompleteOptions"
                v-bind:class="{'selected': currentIndex === $index}"
                class="autocomplete-dropdown-item">
            @{{ option.name }}
        </div>
    </div>
</div>

</script>