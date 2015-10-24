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
            @include('pages.entries.info');
            @include('pages.entries.entry-inputs');
		</div>
		
		<div id="entries">
			@include('pages.entries.menu-entries')
			@include('pages.entries.exercise-entries')
		</div>
	</div>

	@include('templates.footer')

</body>
</html>
