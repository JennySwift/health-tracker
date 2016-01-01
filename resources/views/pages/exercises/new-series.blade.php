<div>
    <button ng-click="showNewSeriesFields = !showNewSeriesFields" class="btn btn-success btn-sm">New Series</button>
</div>

<div ng-show="showNewSeriesFields" class="margin-bottom">
    <input ng-model="newSeries.name" ng-keyup="insertExerciseSeries($event.keyCode)" type="text" placeholder="Add a new series"  id="exercise-series" class="form-control">
</div>