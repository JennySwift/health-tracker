<script id="temporary-recipe-popup-template" type="x-template">

<div>

	<div
		v-show="showPopup"
		v-on:click="closePopup($event)"
		class="popup-outer"
	>
		<div class="popup-inner">

			<div class="content">
				<h4 class="center">@{{ recipe.name }}</h4>
				<p class="col-sm-12">Editing your recipe here will not change the default contents of your recipe.</p>

				<div class="row margin-bottom">
					<div class="col-sm-10 col-sm-offset-1">

						<label>How many portions? 1 for the full recipe, .5 for half, etc.</label>

						<input
								v-model="portion"
								v-on:keyup="setRecipePortion()"
								type="text"
								placeholder="portion"
								class="form-control"
						>

					</div>
				</div>

				<h3>Add ingredient</h3>

				<new-food-entry
						:date="date"
						:selected-recipe.sync="recipe"
						:recipe-is-temporary="true"
				>
				</new-food-entry>

				<h3>Ingredients</h3>

				<table class="table table-bordered">

					<tr>
						<th>food</th>
						<th>quantity</th>
						<th>unit</th>
					</tr>

					<tr v-for="ingredient in recipe.ingredients.data">

						<td>@{{ ingredient.food.data.name }}</td>

						<td>
							<input v-model="ingredient.quantity" type="text">
						</td>

						<td>
							<select
									v-model="ingredient.unit.data"
									id="unit"
									class="form-control"
							>
								<option
										v-for="unit in ingredient.food.data.units.data"
										v-bind:value="unit"
								>
									@{{ unit.name }}
								</option>
							</select>
						</td>

						<td>
							<i
									v-on:click="deleteIngredientFromTemporaryRecipe(ingredient)"
									class="delete-item fa fa-times"
							>
							</i>
						</td>

					</tr>
				</table>
			</div>



			<div class="buttons">
				<button
						v-on:click="showPopup = false"
						class="close-popup btn btn-default"
				>
					Cancel
				</button>

				<button
						v-on:click="insertEntriesForRecipe()"
						class="btn btn-success"
				>
					Enter
				</button>
			</div>

		</div>

	</div>


</div>

</script>