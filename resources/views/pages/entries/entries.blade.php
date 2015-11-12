<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8" name="viewport" content="initial-scale = 1">
	<title>tracker</title>
	@include('templates.head-links')
</head>
<body ng-controller="entries">
	
	@include('templates.header')
	@include('pages.entries.index')

	<div class="container">

        @include('templates.date-navigation')
		
		<div id="info-entries-wrapper">
            @include('pages.entries.info')
		</div>

        <div id="entries">
            <div ng-controller="MenuEntriesController">
                @include('pages.entries.menu-entry-inputs')
                @include('pages.entries.menu-entries')
            </div>

            <div ng-controller="ExerciseEntriesController">
                @include('pages.entries.popups.exercise-entries')
                @include('pages.entries.exercise-entry-inputs')
                @include('pages.entries.exercise-entries')
            </div>
        </div>
		

	</div>

	@include('templates.footer')

</body>
</html>
