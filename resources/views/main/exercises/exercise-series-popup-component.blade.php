<script id="series-popup-template" type="x-template">

	<div>
		<popup
		    :show-popup.sync="showPopup"
		    id="series-popup"
		    :redirect-to="redirectTo"
		    :update="updateSeries"
		    :destroy="deleteSeries"
		>
		    <div slot="content">
				<h3 class="popup-title">@{{ selectedSeries.name }} series</h3>

				<label for="seriesName">Name your series</label>
				<input v-model="selectedSeries.name" type="text" name="seriesName" placeholder="name"/>

				<div>
					<label for="exercise-series-priority"></label>
					<input
							v-model="selectedSeries.priority"
							type="text"
							id="exercise-series-priority"
							name="exercise-series-priority"
							placeholder="priority"
							class="form-control"
					/>
				</div>
		    </div>
		</popup>

	</div>

</script>