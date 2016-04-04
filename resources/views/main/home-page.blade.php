<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>tracker</title>
	@include('main.shared.head-links')
</head>
<body>

@include('main.shared.header.header')
<feedback></feedback>
<loading></loading>


<div class="container">
	<router-view></router-view>
</div>

@include('main.shared.footer.footer')

</body>
</html>
