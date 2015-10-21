<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	@include('templates.head-links')
</head>
<body>

    @include('templates.header')
	
	<div ng-controller="foods" class="container">

		@include('pages.foods.index')

		<div id="foods">

			<div id="quick-recipe-container">
				@include('pages.foods.quick-recipe')
			</div>

			<hr>	

			<div class="flex">

                @include('pages.foods.recipes')
                @include('pages.foods.recipe-tags')

			</div>
		</div>

	</div>

	@include('templates.footer')

</body>
</html>