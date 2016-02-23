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
								v-model="selectedFood.unitIds"
								v-on:click="updateFood()"
								:value="unit.id"
								type="checkbox"
							>
							@{{ unit.name }}
						</td>

						<td>
							<input
								v-model="unit.calories"
								v-if="selectedFood.unitIds.indexOf(unit.id) !== -1"
								v-on:keyup.13="updateCalories(unit)"
								type="text"
								placeholder="calories"
								id="food-unit-calories"
								class="form-control"
							>
						</td>

						<td>
							<button
								v-if="selectedFood.defaultUnit && unit.id === selectedFood.defaultUnit.data.id"
								class="btn btn-sm default"
								disabled
							>
								Default
							</button>

							<button
									v-if="unit.id !== selectedFood.defaultUnit.data.id && selectedFood.unitIds.indexOf(unit.id) !== -1"
									v-on:click="updateDefaultUnit(unit)"
									class="btn btn-sm make-default show-hover-item"
							>
								Make default
							</button>
						</td>
					</tr>
				</table>

				<button v-on:click="showPopup = false" class="close-popup btn btn-sm">close</button>

			</div>

		</div>

	</div>

</script>

