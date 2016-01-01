<div id="exercise-filters" class="margin-bottom">
    <h5>Filters</h5>
    <div class="flex">
        <input ng-model="filter.exercises.name" type="text" placeholder="name" class="form-control"/>
        <input ng-model="filter.exercises.description" type="text" placeholder="description" class="form-control"/>
        <input ng-model="filter.exercises.series" type="text" placeholder="series" class="form-control"/>

        <div class="form-group">
            <select
                ng-options="series as series for series in series"
                ng-model="filter.exercises.series"
                id="filter-exercises-series"
                class="form-control">
            </select>
        </div>


        <input ng-model="filter.exercises.priority" type="text" placeholder="priority" class="form-control"/>
    </div>
</div>