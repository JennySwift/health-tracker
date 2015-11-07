
<div ng-show="show.popups.recipe" ng-click="closePopup($event, 'recipe')" class="popup-outer">

	<div id="recipe-popup" class="popup-inner">

		<h1 class="center">[[recipe_popup.recipe.name]]</h1>

        @include('pages.menu.recipes.popups.recipe-popup.add-ingredient')
        @include('pages.menu.recipes.popups.recipe-popup.ingredients')
        @include('pages.menu.recipes.popups.recipe-popup.steps')
        @include('pages.menu.recipes.popups.recipe-popup.tags')

	</div>

</div>