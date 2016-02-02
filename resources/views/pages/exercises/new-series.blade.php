
<div v-show="showNewSeriesFields" class="margin-bottom">
    <div>
        <input
            v-model="newSeries.name"
            v-on:keyup="insertExerciseSeries($event.keyCode)"
            type="text"
            placeholder="Add a new series" 
            id="exercise-series"
            class="form-control"
        >
    </div>

    <div>
        <button
                v-on:click="showNewSeriesFields = false"
                class="btn btn-default"
        >
            Close
        </button>
        <button
                v-on:click="insertExerciseSeries(13)"
                class="btn btn-success"
        >
            Add series
        </button>
    </div>

</div>