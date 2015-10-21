<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	@include('templates.head-links')
</head>
<body>

    @include('templates.header')
	
	<div ng-controller="FoodsController" class="container">

		@include('pages.foods.index')

		<div id="foods">

			<div class="flex">

                @include('pages.foods.foods')

			</div>
		</div>

	</div>

	@include('templates.footer')

</body>
</html>