<div v-show="showPopup" v-on:click="closePopup($event)" class="popup-outer">

	<div class="popup-inner">

		<p>The following foods you entered in your recipe do not exist in your foods, but similar foods were found.</p>
		<p>Check the existing food to use that in your recipe, or check the specified food to create a new food with the specified name.</p>

		<table class="table table-bordered">

			<tr>
				<th>specified food</th>
				<th>existing food</th>
			</tr>

			<tr v-for="item in newRecipe.similarNames.foods">
				<td>
					@{{ item.specified_food.name }}
					<div class="vertical-center">
						<input v-model="item.checked" value="item.specified_food.name" type="radio">
					</div>
				</td>
				<td>
					[[item.existing_food.name]]
					<div class="vertical-center">
						<input ng-model="item.checked" ng-value="item.existing_food.name" type="radio">
					</div>
				</td>
			</tr>

		</table>

		<table class="table table-bordered">

			<th>specified unit</th>
			<th>existing unit</th>

			<tr v-for="item in newRecipe.similarNames.units">
				<td>
					@{{ item.specified_unit.name }}
					<div class="vertical-center">
						<input v-model="item.checked" value="item.specified_unit.name" type="radio">
					</div>
				</td>
				<td>
					@{{ item.existing_unit.name }}
					<div class="vertical-center">
						<input v-model="item.checked" value="item.existing_unit.name" type="radio">
					</div>
				</td>
			</tr>
		</table>

		<div>
			<button v-on:click="showPopup = false">Cancel</button>
			<button v-on:click="quickRecipeFinish()">Go</button>
		</div>

	</div>
</div>