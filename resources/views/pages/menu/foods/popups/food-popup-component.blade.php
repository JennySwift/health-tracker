<script id="food-popup-template" type="x-template">

	<div class="row">

		<div v-show="showPopup" v-on:click="closePopup($event)" id="food-info-popup" class="popup-outer">

			<div class="popup-inner">

				<h3 class="center">@{{ selectedFood.food.name }}</h3>

				<table class="table">
					<tr>
						<th>unit</th>
						<th>calories</th>
						<th>default</th>
					</tr>
					<tr
							v-for="unit in selectedFood.all_food_units"
							v-class="{'default-unit': unit.id === selectedFood.food.default_unit_id}"
					>
						<td>
							<input
									checklist-model="selectedFood.food_units"
									checklist-value="unit.id"
									v-on:click="insertOrDeleteUnitInCalories(unit.id)"
									type="checkbox">
							@{{ unit.name }}
						</td>
						<td>
							<input
									v-model="unit.calories"
									v-on:keyup="updateCalories($event.keyCode, unit.id, unit.calories)"
									type="text"
									placeholder="calories"
									id="food-unit-calories"
									class="form-control"
							>
						</td>
						<td>
							<button
									v-if="unit.id === selectedFood.food.default_unit_id"
									class="btn btn-sm default"
									disabled
							>
								default
							</button>

							<button
									v-if="unit.id !== selectedFood.food.default_unit_id && selectedFood.food_units.indexOf(unit.id) !== -1"
									v-on:click="updateDefaultUnit(selectedFood.food.id, unit.id)"
									class="btn btn-sm make-default show-hover-item"
							>
								make default
							</button>
						</td>
					</tr>
				</table>

				<button v-on:click="show.popups.food_info = false" class="close-popup btn btn-sm">close</button>

			</div>

		</div>

	</div>

</script>

