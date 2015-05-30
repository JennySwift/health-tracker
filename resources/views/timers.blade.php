<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	<?php
		include(base_path().'/resources/views/templates/config.php');
		include($head_links);
	?>
</head>
<body>

	<?php
		include($header);
	?> 
	
	<div ng-controller="timers" id="timers" class="container">

		<h1>User is payee</h1>

		<table class="table table-bordered margin-bottom-lg">
			<tr>
				<th>Payer</th>
				<th>Description</th>
				<th>Rate/hour</th>
				<th>Paid</th>
			</tr>
			<tr ng-repeat="timer in timers.payee">
				<td>[[timer.payer.name]]</td>
				<td>[[timer.description]]</td>
				<td>$[[timer.rate_per_hour]]</td>
				<td>
					<span ng-if="!timer.paid" class="label label-danger">unpaid</span>
					<span ng-if="timer.paid" class="label label-success">paid</span>
				</td>
			</tr>
			
		</table>

		<h1>User is payer</h1>

		<table class="table table-bordered">
			<tr>
				<th>Payee</th>
				<th>Description</th>
				<th>Rate/hour</th>
				<th>Paid</th>
			</tr>
			<tr ng-repeat="timer in timers.payer">
				<td>[[timer.payee.name]]</td>
				<td>[[timer.description]]</td>
				<td>$[[timer.rate_per_hour]]</td>
				<td>
					<span ng-if="!timer.paid" class="label label-danger">unpaid</span>
					<span ng-if="timer.paid" class="label label-success">paid</span>
				</td>
			</tr>
			
		</table>

	</div>

	<?php include($footer); ?>

	@include('footer')

</body>
</html>