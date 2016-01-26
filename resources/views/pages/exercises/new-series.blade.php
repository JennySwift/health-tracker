
<div ng-show="showNewSeriesFields" class="margin-bottom">
    <div>
        <input ng-model="newSeries.name" ng-keyup="insertExerciseSeries($event.keyCode)" type="text" placeholder="Add a new series"  id="exercise-series" class="form-control">
    </div>

    <div>
        <button
                ng-click="showNewSeriesFields = false"
                class="btn btn-default"
        >
            Close
        </button>
        <button
                ng-click="insertExerciseSeries(13)"
                class="btn btn-success"
        >
            Add series
        </button>
    </div>

</div>