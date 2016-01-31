<div v-show="showPopup" v-on:click="closePopup($event)" class="popup-outer">

	<div class="popup-inner">

		<p>The following foods you entered in your recipe do not exist in your foods, but similar foods were found.</p>
		<p>Check the existing food to use that in your recipe, or check the specified food to create a new food with the specified name.</p>

		<table class="table table-bordered">

			<tr>
				<th>specified food</th>
				<th>existing food</th>
			</tr>

			<tr v-for="item in similarNames.foods">
				<td>
					@{{ item.specifiedFood.name }}
					<div class="vertical-center">
						<input v-model="item.checked" value="item.specifiedFood.name" type="radio">
					</div>
				</td>
				<td>
					@{{ item.existingFood.name }}
					<div class="vertical-center">
						<input v-model="item.checked" value="item.existingFood.name" type="radio">
					</div>
				</td>
			</tr>

		</table>

		<table class="table table-bordered">

			<th>specified unit</th>
			<th>existing unit</th>

			<tr v-for="item in similarNames.units">
				<td>
					@{{ item.specifiedUnit.name }}
					<div class="vertical-center">
						<input v-model="item.checked" value="item.specifiedUnit.name" type="radio">
					</div>
				</td>
				<td>
					@{{ item.existingUnit.name }}
					<div class="vertical-center">
						<input v-model="item.checked" value="item.existingUnit.name" type="radio">
					</div>
				</td>
			</tr>
		</table>

		<div>
			<button v-on:click="showPopup = false">Cancel</button>
			<button v-on:click="insertRecipe()">Go</button>
		</div>

	</div>
</div>