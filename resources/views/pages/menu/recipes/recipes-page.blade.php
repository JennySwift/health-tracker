<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
    <meta charset="UTF-8">
    <title>tracker</title>
    @include('templates.shared.head-links')
</head>
<body>

@include('templates.shared.header')

<div ng-controller="RecipesController" class="container">

    @include('pages.menu.recipes.popups.recipe-popup.recipe')

    <div id="foods">

        <div ng-controller="QuickRecipeController" id="quick-recipe-container">
            @include('pages.menu.recipes.popups.similar-names')
            @include('pages.menu.recipes.quick-recipe')
        </div>

        <hr>

        <div class="flex">

            @include('pages.menu.recipes.recipes')
            @include('pages.menu.recipes.recipe-tags')

        </div>
    </div>

</div>

@include('templates.shared.footer')

</body>
</html>