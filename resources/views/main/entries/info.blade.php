<div id="info">
	<ul class="list-group">
		<li class="list-group-item">
			<span>Today's total calories: </span>
			<span class="badge">@{{ calories.day | roundNumber }}</span>
		</li>

		<li class="list-group-item">
			<span id="avg-calories-for-the-week-text">Avg calories (last 7 days): </span>
			<span class="badge">@{{ calories.averageFor7Days | roundNumber }}</span>
		</li>

		<li
			v-show="edit_weight !== true"
			v-on:click="editWeight()"
			class="list-group-item pointer"
		>
			<span>Today's weight: </span>
			<span
				v-if="weight"
				class="badge"
			>
				@{{ weight.weight | roundNumber 'one' }}
			</span>
		</li>

		<li v-show="editWeight === true" class="list-group-item">
			<input
				v-on:keyup="insertOrUpdateWeight($event.keyCode)"
				type="number"
				placeholder="enter your weight"
				id="weight"
			>
		</li>
	</ul>

</div>