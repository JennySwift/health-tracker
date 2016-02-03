<div id="exercise-filters" class="margin-bottom">
    <h5>Filters</h5>
    
    <div class="flex">
        <input
            v-model="filterByName"
            type="text"
            placeholder="name"
            class="form-control"
        />
        <input
            v-model="filterByDescription"
            type="text"
            placeholder="description"
            class="form-control"
        />
        {{--<input--}}
            {{--v-model="filter.exercises.series"--}}
            {{--type="text"--}}
            {{--placeholder="series"--}}
            {{--class="form-control"--}}
        {{--/>--}}

        <div class="form-group">
            <label for="filter-by-series">Filter by series</label>

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

        <input
            v-model="filterByPriority"
            type="text"
            placeholder="priority"
            class="form-control"
        />

    </div>
</div>