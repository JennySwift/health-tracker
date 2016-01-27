<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	@include('templates.shared.head-links')
</head>
<body>

    @include('templates.shared.header')
	<feedback-directive></feedback-directive>
	@include('templates.shared.loading')
	
	<div ng-controller="FoodsController" class="container">

		@include('pages.menu.foods.popups/food-info')

		<div id="foods">

			<div class="flex">

                @include('pages.menu.foods.foods')

			</div>
		</div>

	</div>

	@include('templates.shared.footer')

</body>
</html>