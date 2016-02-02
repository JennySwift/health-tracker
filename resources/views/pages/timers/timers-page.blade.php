<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
    <meta charset="UTF-8">
    <title>tracker</title>
    @include('templates.shared.head-links')
</head>
<body>

@include('templates.shared.header')
<feedback></feedback>
<loading></loading>

<div class="container">
    <timers-page></timers-page>
</div>

@include('templates.shared.footer')

</body>
</html>
