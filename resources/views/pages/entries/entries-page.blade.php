<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	@include('templates.shared.head-links')
</head>
<body>

@include('templates.shared.header')
<feedback-directive></feedback-directive>
@include('templates.shared.loading')

<div class="container">
	<entries-page></entries-page>
</div>

@include('templates.shared.footer')

</body>
</html>
