<script id="food-popup-template" type="x-template">

	<div class="row">

		<div v-show="showPopup" v-on:click="closePopup($event)" id="food-info-popup" class="popup-outer">

			<div class="popup-inner">

				<h3 class="center">@{{ selectedFood.name }}</h3>

				<table class="table">
					<tr>
						<th>unit</th>
						<th>calories</th>
						<th>default</th>
					</tr>
					<tr
							v-for="unit in units"
							v-bind: class="{'default-unit': unit.id === selectedFood.defaultUnit.data.id}"
					>
						<td>
							<input
									checklist-model="selectedFood.units"
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
									v-if="unit.id === selectedFood.defaultUnit.data.id"
									class="btn btn-sm default"
									disabled
							>
								default
							</button>

							<button
									v-if="unit.id !== selectedFood.defaultUnit.data.id && selectedFood.units.indexOf(unit.id) !== -1"
									v-on:click="updateDefaultUnit(selectedFood.id, unit.id)"
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

