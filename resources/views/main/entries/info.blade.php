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

		<weight
			:date="date"
		>
		</weight>
	</ul>

</div>