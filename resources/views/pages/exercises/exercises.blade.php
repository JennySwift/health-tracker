<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
    <meta charset="UTF-8">
    <title>tracker</title>
	@include('templates.shared.head-links')
</head>
<body>

	@include('templates.shared.header')
	
	<div ng-controller="exercises" id="exercises" class="container">

		@include('pages.exercises.popups.exercise-popup-component')
		@include('pages.exercises.popups.exercise-series-history-popup-component')
		@include('pages.exercises.popups.exercise-series-popup-component')

		<div>

            @include('pages.exercises.exercise-filters')
            @include('pages.exercises.new-exercise')

			<table class="table table-bordered">
				<tr>
					<th>name</th>
					<th>description</th>
					<th>step</th>
					<th>series</th>
					<th>default quantity</th>
					<th>default unit</th>
					<th>target</th>
					<th>priority</th>
					<th>program</th>
					<th>x</th>
				</tr>
				<tr ng-repeat="exercise in exercises | filter:{name: filter.exercises.name, description: filter.exercises.description, priority: filter.exercises.priority, series: {name: filter.exercises.series}}" class="hover">
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.name]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.description]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.stepNumber]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.series.name]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.defaultQuantity]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.defaultUnit.name]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.target]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.priority]]</td>
					<td ng-click="showExercisePopup(exercise)" class="pointer">[[exercise.program.name]]</td>
					<td><i ng-click="deleteExercise(exercise)" class="delete-item fa fa-times"></i></td>
				</tr>
			</table>
        </div>

	</div>

	@include('templates.shared.footer')

</body>
</html>