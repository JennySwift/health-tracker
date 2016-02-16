<script id="temporary-recipe-popup-template" type="x-template">

<div>

	<div
		v-show="showPopup"
		v-on:click="closePopup($event)"
		class="popup-outer"
	>
		<div class="popup-inner">

			<h4 class="center">@{{ recipe.name }}</h4>
			<p class="col-sm-12">Editing your recipe here will not change the default contents of your recipe.</p>
			<div class="row margin-bottom">
				<div class="col-sm-10 col-sm-offset-1">

					<input
							v-model="recipe.portion"
							type="text"
							placeholder="how many portions? 1 for the full recipe, .5 for half, etc."
							class="form-control"
					>

				</div>
			</div>

			<table class="table table-bordered">

				<tr>
					<th>food</th>
					<th>quantity</th>
					<th>unit</th>
				</tr>

				<tr v-for="ingredient in recipe.ingredients">

					<td>@{{ ingredient.name }}</td>

					<td>
						<input v-model="ingredient.quantity" type="text">
					</td>

					<td>
						<select
								v-model="ingredient.unit"
								id="unit"
								class="form-control"
						>
							<option
									v-for="unit in ingredient.units"
									v-bind:value="unit"
							>
								@{{ unit.name }}
							</option>
						</select>
					</td>

					<td>
						<i
							v-on:click="deleteFromTemporaryRecipe(item)"
							class="delete-item fa fa-times"
						>
						</i>
					</td>

				</tr>
			</table>

			<div class="buttons">
				<button
						v-on:click="show.popups.temporary_recipe = false"
						class="close-popup btn btn-sm"
				>
					Cancel
				</button>

				<button
						v-on:click="insertRecipeEntry()"
						class="btn btn-default"
				>
					Enter
				</button>
			</div>

		</div>

	</div>


</div>

</script>