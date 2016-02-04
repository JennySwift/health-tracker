<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
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
