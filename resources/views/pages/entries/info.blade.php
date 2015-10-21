<div id="info">
	<ul class="list-group">
		<li class="list-group-item">
			<span>Today's total calories: </span>
			<span class="badge">[[calories.day]]</span>
		</li>

		<li class="list-group-item">
			<span id="avg-calories-for-the-week-text">Avg calories (last 7 days): </span>
			<span class="badge">[[calories.week_avg]]</span>
		</li>

		<li ng-show="edit_weight !== true" ng-click="editWeight()" class="list-group-item pointer">
			<span>Today's weight: </span>
			<span class="badge">[[weight]]</span>
		</li>

		<li ng-show="edit_weight === true" class="list-group-item">
			<input ng-keyup="insertOrUpdateWeight($event.keyCode)" type="number" placeholder="enter your weight" id="weight">
		</li>
	</ul>

</div>