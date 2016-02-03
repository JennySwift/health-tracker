<div id="exercise-filters" class="margin-bottom">
    <h5>Filters</h5>
    
    <div class="flex">

        <div class="form-group">
            <label for="filter-by-name">Name</label>
            <input
                v-model="filterByName"
                type="text"
                id="filter-by-name"
                name="filter-by-name"
                placeholder="name"
                class="form-control"
            >
        </div>

        <div class="form-group">
            <label for="filter-by-description">Description</label>
            <input
                v-model="filterByDescription"
                type="text"
                id="filter-by-description"
                name="filter-by-description"
                placeholder="description"
                class="form-control"
            >
        </div>

        <div class="form-group">
            <label for="filter-by-series">Series</label>

            <select
                v-model="filterBySeries"
                id="filter-by-series"
                class="form-control"
            >
                <option
                    v-for="series in series"
                    v-bind:value="series"
                >
                    @{{ series }}
                </option>
            </select>
        </div>

        <div class="form-group">
            <label for="filter-by-priority">Priority</label>
            <input
                v-model="filterByPriority"
                type="text"
                id="filter-by-priority"
                name="filter-by-priority"
                placeholder="priority"
                class="form-control"
            >
        </div>

    </div>
</div>