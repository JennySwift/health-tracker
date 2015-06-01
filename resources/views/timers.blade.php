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

	@include('templates.header')
	
	<div ng-controller="projects" id="timers" class="container">

		<input ng-model="new_project.email" type="text" placeholder="payer email">
		<input ng-model="new_project.description" type="text" placeholder="description">
		<input ng-model="new_project.rate" type="text" placeholder="rate">
		<button ng-click="insertProject()">Create project</button>



		<h1>User is payee</h1>

		<table class="table table-bordered margin-bottom-lg">
			<tr>
				<th>Payer</th>
				<th>Description</th>
				<th>Rate/hour</th>
				<th>Time</th>
				<th>$</th>
				<th>Paid</th>
			</tr>
			<tr ng-repeat="project in projects.payee">
				<td>[[project.payer.name]]</td>
				<td>[[project.description]]</td>
				<td>$[[project.rate_per_hour]]</td>
				<td>[[project.total_time_user_formatted]]</td>
				<td>[[project.price]]</td>
				<td>
					<span ng-if="!project.paid" class="label label-danger">unpaid</span>
					<span ng-if="project.paid" class="label label-success">paid</span>
				</td>
			</tr>
			
		</table>

		<h1>User is payer</h1>

		<table class="table table-bordered">
			<tr>
				<th>Payee</th>
				<th>Description</th>
				<th>Rate/hour</th>
				<th>Time</th>
				<th>$</th>
				<th>Paid</th>
			</tr>
			<tr ng-repeat="project in projects.payer">
				<td>[[project.payee.name]]</td>
				<td>[[project.description]]</td>
				<td>$[[project.rate_per_hour]]</td>
				<td>[[project.total_time_user_formatted]]</td>
				<td>[[project.price]]</td>
				<td>
					<span ng-if="!project.paid" class="label label-danger">unpaid</span>
					<span ng-if="project.paid" class="label label-success">paid</span>
				</td>
			</tr>
			
		</table>

	</div>

	<?php include($footer); ?>

	@include('footer')

</body>
</html>