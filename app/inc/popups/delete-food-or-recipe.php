<div ng-show="show.popups.delete_food_or_recipe_entry" ng-click="closePopup($event, 'delete_food_or_recipe_entry')" class="popup-outer">

	<div class="popup-inner">

		<button ng-click="deleteFoodEntry()">delete only this food</button>
		<button ng-click="deleteRecipeEntry()">delete the whole recipe</button>

	</div>
	
</div>