<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
    <meta charset="UTF-8">
    <title>tracker</title>
    @include('main.shared.head-links')
</head>
<body>

@include('main.shared.header.navbar-component')

<feedback></feedback>
<loading></loading>

<div class="container">
    <exercise-units-page
    >
    </exercise-units-page>
</div>

@include('main.shared.footer.footer')

</body>
</html>