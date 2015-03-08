<!doctype html>
<html lang="en" class="" ng-app="foodApp">
<head>

    <meta charset="UTF-8" name="viewport" content="initial-scale = 1">
    <title>Food App!</title>
    <?php
    	include(app_path().'/inc/config.php');
    ?>

    <link rel="stylesheet" href="tools/bootstrap.min.css">
    <link rel="stylesheet" href="tools/tooltipster.css">  
    <link rel="stylesheet" href="tools/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="" ng-controller="display">

<?php
	include($inc . '/header.php');
?>   

<!-- ==============================.container============================== -->    
<div class="container">

	<div ng-if="tab === 'entries'"> <!-- food and exercise entries -->
		<!-- <h1>{{new_number}}</h1>
		<input ng-model="factory" type="text" class="form-control"> -->


		<h1 class="row center">{{date.long}}</h1>

		<!-- ==============================date navigation============================== -->	
		
		<div class="row margin-bottom">

			<div class="flex-parent col-xs-12 col-sm-8 col-sm-offset-2">

				<div class="my-btn-group flex-child flex-grow-1 min-width-md">
					<button type="button" id="last-week" class="my-btn fa fa-angle-double-left" ng-click="goToDate(-7)"></button>
					<button type="button" id="prev" class="my-btn fa fa-angle-left" ng-click="goToDate(-1)"></button>	
				</div>

				<input ng-keyup="changeDate($event.keyCode)" type="text" placeholder="date" id="date" class="flex-grow-2 my-input">
				
				<button ng-click="today()" type="button" id="today" class="flex-grow-1 max-width-lg my-btn">
					<span class="hidden-xs">today</span>
					<span class="fa fa-star visible-xs"></span>
				</button>

				<div class="my-btn-group flex-grow-1 min-width-md">
					<button type="button" id="next" class="my-btn fa fa-angle-right" ng-click="goToDate(1)"></button>
					<button type="button" id="next-week" class="my-btn fa fa-angle-double-right" ng-click="goToDate(7)"></button>
				</div>

			</div>

		</div>	<!-- .row -->

		<!-- ==============================info============================== -->

		<div class="row">

			<div id="info" class="col-xs-12 col-sm-4">
				<ul class="list-group">
					<li class="list-group-item">
						<span>Today's total calories: </span>
						<span class="badge">{{calories.day}}</span>
					</li>

					<li class="list-group-item">
						<span id="avg-calories-for-the-week-text">Avg calories (last 7 days): </span>
						<span class="badge">{{calories.week_avg}}</span>
					</li>

					<li ng-show="edit_weight !== true" ng-click="editWeight()" class="list-group-item pointer">
						<span>Today's weight: </span>
						<span class="badge">{{weight}}</span>
					</li>

					<li ng-show="edit_weight === true" class="list-group-item">
						<input ng-keyup="enterWeight($event.keyCode)" type="text" placeholder="enter your weight" id="weight">
					</li>
				</ul>
			</div>

			<!-- ==============================inputs============================== -->	

			<!-- food entry -->
			<div class="col-xs-6 col-sm-4 margin-bottom">
				<div>	
					<input ng-model="menu_item.name" ng-keyup="autocomplete('menu', $event.keyCode); enter($event.keyCode, 'menu')" ng-blur="autocomplete.menu = ''" type="text" placeholder="food" id="food" class="form-control">
					
					<div>
						<div ng-repeat="item in autocomplete.menu" ng-class="{'selected': $first}" data-id="{{item.id}}" data-type="{{item.type}}" class="autocomplete-dropdown-item">{{item.name}}</div>
					</div>

					<div>
						<input ng-model="food.quantity" ng-keyup="enter($event.keyCode, 'menu')" type="text" placeholder="quantity" id="food-quantity" class="form-control">
						<select ng-keyup="enter($event.keyCode, 'menu')" name="" id="food-unit" class="form-control">
							<option ng-repeat="unit in assoc_units" ng-selected="unit.default_unit === true" data-unit-id="{{unit.unit_id}}">{{unit.unit_name}}</option>
						</select>
					</div>
				</div>
			</div>

			<!-- exercise entry -->
			<div class="col-xs-6 col-sm-4 margin-bottom">
				<div>
					<input ng-keyup="enter($event.keyCode, 'exercise')" type="text" placeholder="exercise" id="exercise" class="form-control">
					<div id="exercise-autocomplete" class="autocomplete-dropdown"></div>
					<input ng-keyup="enter($event.keyCode, 'exercise')" type="text" placeholder="quantity" class="form-control">
				</div>
			</div>

		</div> <!-- .row -->

		<!-- ==============================recipe popup when logging a recipe============================== -->	
		
		<div class="row">

			<div ng-show="recipe.temporary_contents.length > 0" class="popup col-sm-8">
				<h4 class="center">{{recipe.name}}</h4>
				<p class="col-sm-12">Editing your recipe here will not change the default contents of your recipe.</p>
				<div class="row margin-bottom">
					<div class="col-sm-10 col-sm-offset-1">
						<input ng-model="recipe.portion" type="text" placeholder="enter percentage of recipe" class="form-control">
						<input ng-model="food.name" ng-keyup="autocomplete('food', $event.keyCode); enter($event.keyCode, 'food', 'temporary_recipe')" ng-blur="autocomplete.food = ''" type="text" placeholder="add food to {{recipe.name}}" id="temporary-recipe-popup-food-input" class="form-control">
						
						<div id="" class="autocomplete-dropdown">
							<div ng-repeat="food in autocomplete.food" ng-class="{'selected': $first}" data-id="{{food.food_id}}" class="autocomplete-dropdown-item">{{food.food_name}}</div>
						</div>
						
						<input ng-model="food.quantity" ng-keyup="enter($event.keyCode, 'food', 'temporary_recipe')" type="text" placeholder="quantity" id="temporary-recipe-popup-food-quantity" class="form-control">
						<select ng-model="unit_id" ng-keyup="enter($event.keyCode, 'food', 'temporary_recipe')" name="" id="temporary-recipe-popup-unit-select" class="form-control">
							<option ng-repeat="unit in assoc_units" ng-selected="unit.default_unit === true" data-unit-id="{{unit.unit_id}}">{{unit.unit_name}}</option>
						</select>
					</div>
				</div>
				<table class="table table-bordered">
					<tr>
						<th>food</th>
						<th>unit</th>
						<th>quantity</th>
					</tr>
					<tr ng-repeat="item in recipe.temporary_contents">
						<td>{{item.food_name}}</td>
						<td>
							<select name="" id="">
								<option ng-repeat="unit in item.assoc_units" ng-selected="unit.unit_name === item.unit_name" value="">{{unit.unit_name}}</option>
							</select>
						</td>
						<td><input ng-model="item.quantity" type="text"></td>
						<td><i ng-click="deleteFromTemporaryRecipe(item)" class="delete-item fa fa-times"></i></td>
					</tr>
				</table>
				
				<button ng-click="recipe.temporary_contents.length = 0" class="close-popup btn btn-sm">cancel</button>
				<button ng-click="addMenuEntry()" class="btn btn-default col-sm-12">enter</button>
			</div>

		</div> <!-- .row -->

		<!-- ==========================food and exercise entries========================== -->

		<div class="row">
			<!-- display food entries -->
			<div class="col-xs-12 col-sm-6">
				<table class="table table-bordered">
					<caption class="bg-blue">entries for the day</caption>
					<tr><th>food</th><th>quantity</th><th>unit</th><th>calories</th><th>recipe</th></tr>
					<tr ng-repeat="entry in food_entries" data-entry-id="{{entry.entry_id}}">
						<td>{{entry.food_name}}</td>
						<td>{{entry.quantity}}</td>
						<td>{{entry.unit_name}}</td>
						<td>{{entry.calories}}</td>
						<td>
							<span ng-if="entry.recipe_name" class="badge">{{entry.recipe_name}}</span>
							<span ng-if="!entry.recipe_name">N/A</span>
						</td>
						<td>
							<i ng-click="deleteItem('food_entries', 'entry', entry.entry_id, displayFoods)" class="delete-item fa fa-times"></i>
						</td>
					</tr>
				</table>
			</div>
			<!-- display exercise entries -->
			<div class="col-xs-12 col-sm-6">
				<table class="table table-bordered">
					<caption class="bg-blue">entries for the day</caption>
					<tr><th>exercise</th><th>quantity</th></tr>
		
					<tr ng-repeat="entry in exercise_entries" data-entry-id="{{entry.entry_id}}">
						<td>{{entry.exercise_name}}</td>
						<td>{{entry.quantity}}</td>
						<td><i class="delete-item fa fa-times"></i></td>
					</tr>
				</table>
			</div>
					
		</div> <!-- .row -->

	</div> <!-- end entries tab -->

	<!-- ==========================foods tab========================== -->

	<div ng-if="tab === 'foods'">

		<div class="row">

			<div class="col col-sm-6">
				<input ng-keyup="insert($event.keyCode, 'foods', displayFoods)" type="text" placeholder="add a new food" id="create-new-food" class="form-control">
				<hr>
				<div>
					<table class="table table-bordered">
						<tr>
							<th>name</th>
							<th>default</th>
							<th>calories</th>
						</tr>
						<tr ng-repeat="item in all_foods_with_units" data-food-id="{{item.food.food_id}}">
							<td ng-click="displayFoodAndAssocUnits(item.food.food_id, item.food.food_name)" class="pointer">{{item.food.food_name}}</td>
							<td>{{item.food.default_unit_name}}</td>
							<td>{{item.food.default_unit_calories}}</td>
							<td><i ng-click="deleteItem('foods', 'food', item.food.food_id, displayFoods)" class="delete-item fa fa-times"></i></td>
						</tr>
					</table>
					<!-- <ul class="list-group">
						<li ng-repeat="food in foods" data-food-id="{{food.food_id}}" class="list-group-item">
							<span ng-click="displayFoodAndAssocUnits(food.food_id, food.food_name)" class="pointer">{{food.food_name}}</span>
							<i ng-click="deleteItem('foods', 'food', food.food_id, displayFoods)" class="delete-item fa fa-times"></i>
						</li>
					</ul> -->
				</div>
			</div>
			
			<!-- ==========================recipes========================== -->
			
			<div class="col col-sm-6">
				<input ng-keyup="insert($event.keyCode, 'recipes', displayRecipeList)" type="text" placeholder="add a new recipe" id="create-new-recipe" class="form-control">
				<hr>
			
				<div>

					<table class="table table-bordered">
						<tr>
							<th>name</th>
							<th>calories</th>
							<th></th>
						</tr>
						<tr ng-repeat="recipe in recipes" class="pointer">
							<td ng-click="displayRecipeContents(recipe.id, recipe.name)" data-recipe-id="{{recipe.id}}">{{recipe.name}}</td>
							<td>calories</td>
							<td><i ng-click="deleteItem('recipes', 'recipe', recipe.id, displayRecipes)" class="delete-item fa fa-times"></i></td>
						</tr>
					</table>
					
					<!-- <ul class="list-group">
						<li ng-repeat="recipe in recipes" ng-click="displayRecipeContents(recipe.recipe_id, recipe.recipe_name)" data-recipe-id="{{recipe.recipe_id}}" ng-click="" class="pointer list-group-item">
							{{recipe.recipe_name}}
							<i ng-click="deleteItem('recipes', 'recipe', recipe.recipe_id, displayRecipes)" class="delete-item fa fa-times"></i>
						</li>
					</ul> -->
				</div>
			</div>

		</div> <!-- .row -->

		<!-- ==========================food units popup========================== -->

		<div class="row">

			<div ng-show="food_and_assoc_units_array.length > 0" class="popup col-sm-8">
				<div data-food-id="{{food_id}}" class="bold center">{{food_name}}</div>
				
				<ul class="list-group">
					<div ng-repeat="unit in food_and_assoc_units_array" ng-class="{'default-unit': unit.default_unit === true}" class="list-group-item">
						<input ng-model="checked_unit" ng-click="insertUnitInCalories('calories', unit.checked, unit.unit_id)" data-unit-id="{{unit.unit_id}}" type="checkbox" ng-checked="unit.checked === true">
						{{unit.unit_name}}
						<button ng-if="unit.default_unit === true" class="btn btn-sm default" disabled>default</button>
						<button ng-if="unit.default_unit === false && unit.checked === true" ng-click="updateDefaultUnit(unit.unit_id)" class="btn btn-sm make-default show-hover-item">make default</button>
						<input ng-model="unit.calories" ng-keyup="updateCalories($event.keyCode, unit.unit_id, unit.calories)" type="text" data-unit-id="{{unit.unit_id}}" value="{{unit.calories}}" placeholder="calories" class="calories-input">
					</div>
				</ul>
				
				<button ng-click="food_and_assoc_units_array.length = 0" class="close-popup btn btn-sm">close</button>
			</div>

		</div> <!-- .row -->

		<!-- ==========================recipe popup========================== -->

		<div class="row">

			<div ng-show="recipe.name !== undefined" class="popup col-sm-8">
				<h4 class="center">{{recipe.name}}</h4>
				<div class="row margin-bottom">
					<div class="col-sm-10 col-sm-offset-1">
						<input ng-model="food.name" ng-keyup="autocomplete('food', $event.keyCode); enter($event.keyCode, 'food')" ng-blur="autocomplete.food = ''" type="text" placeholder="add food to {{recipe.name}}" id="recipe-popup-food-input" class="form-control">
						
						<div id="" class="autocomplete-dropdown">
							<div ng-repeat="food in autocomplete.food" ng-class="{'selected': $first}" data-id="{{food.food_id}}" class="autocomplete-dropdown-item">{{food.food_name}}</div>
						</div>
						
						<input ng-model="food.quantity" ng-keyup="enter($event.keyCode, 'food')" type="text" placeholder="quantity" id="recipe-popup-food-quantity" class="form-control">
						<select ng-model="unit_id" ng-keyup="enter($event.keyCode, 'food')" name="" id="" class="form-control">
							<option ng-repeat="unit in assoc_units" ng-selected="unit.default_unit === true" data-unit-id="{{unit.unit_id}}">{{unit.unit_name}}</option>
						</select>
					</div>
				</div>
			
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<table class="table table-bordered">
							<tr>
								<th>food</th>
								<th>unit</th>
								<th>quantity</th>
							</tr>
							<tr ng-repeat="item in recipe.contents">
								<td>{{item.food_name}}</td>
								<td>{{item.quantity}}</td>
								<td>{{item.unit_name}}</td>
								<td><i ng-click="deleteItem('recipe_entries', 'food', item.id, displayRecipeContents)" class="delete-item fa fa-times"></i></td>
							</tr>
						</table>
					</div>
				</div>
				<button ng-click="recipe.name = undefined" class="close-popup btn btn-sm">close</button>
			</div>

		</div> <!-- .row -->

	</div> <!-- foods tab -->

	<!-- ==========================exercises tab========================== -->

	<div ng-if="tab === 'exercises'" class="">

		<div class="row margin-bottom">

			<div class="col-sm-4 col-sm-offset-4">
				<input ng-keyup="insert($event.keyCode, 'exercises', displayExercises)" type="text" placeholder="add a new exercise" id="create-new-exercise" class="form-control">
			</div>

		</div> <!-- .row -->

		<div class="row">

			<div class="col-sm-6 col-sm-offset-3">
				<div>
					<ul class="list-group">
						<li ng-repeat="exercise in exercises" data-exercise-id="{{exercise.exercise_id}}" class="list-group-item">
							{{exercise.exercise_name}}
							<i ng-click="deleteItem('exercises', 'exercise', exercise.exercise_id, displayExercises)" class="delete-item fa fa-times"></i>
						</li>
					</ul>
				</div>
			</div>

		</div> <!-- .row -->

	</div> <!-- exercises tab -->

	<!-- ==========================units tab========================== -->

	<div ng-if="tab === 'units'">

		<div class="row">

			<div class="col col-sm-6">
				<input ng-keyup="insert($event.keyCode, 'food_units', displayUnitList)" type="text" placeholder="add a new food unit" id="create-new-food-unit" class="form-control">
				<hr>
			
				<div id="display-food-units">
					<li ng-repeat="unit in units" data-unit-id="{{unit.unit_id}}" class="list-group-item">
						{{unit.unit_name}}
						<i ng-click="deleteItem('food_units', 'unit', unit.unit_id, displayUnitList)" class="delete-item fa fa-times"></i>
					</li>
				</div>
			</div>
			
			<div class="col col-sm-6">
				<input type="text" placeholder="add a new exercise unit" class="form-control">
				<hr>
			
			</div>

		</div> <!-- .row -->

	</div> <!-- units tab -->
      
</div> <!-- .container -->  



<?php
include($inc . '/footer.php');
?>
