<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>Move</title>
	@include('main.shared.head-links')
</head>
<body>

<navbar></navbar>
<feedback></feedback>
<loading></loading>


<div class="main">
	<router-view
			transition-mode="out-in"
			transition="fade"
	>
	</router-view>
</div>

@include('main.shared.footer.footer')

</body>
</html>

