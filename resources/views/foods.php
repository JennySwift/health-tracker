
<!-- the only thing left I need in this I think is the scripts at the end -->

<!doctype html>
<html lang="en" class="" ng-app="">
<head>
    <meta charset="UTF-8">
    <title>Food App!</title>
    <?php include($head_links); ?>
</head>

<body class="">

<?php
include $header;
?>   

<!-- ==============================main (as opposed to header)============================== -->    
<div id="" class="container" ng-controller="display">

	<div class="row">
		<div class="col col-sm-6">
			<input type="text" placeholder="add a new food" id="create-new-food" class="form-control">
			<hr>

			<div id="display-foods">
				
				<ul class="list-group">
					<li ng-repeat="food in foods" data-food-id="{{food_id}}" class="food-list-item list-group-item">
						{{food_name}}
						<i class="delete-item delete-food fa fa-times"></i>
					</li>
				</ul>;
			</div>

		</div>
		<div id="food-units-popup" class="popup"></div>
		
		<div class="col col-sm-6">
			<input type="text" placeholder="add a new recipe" id="create-new-recipe" class="form-control">
			<hr>
			<div id="display-recipes"></div>
		</div>
		<div id="recipe-popup" class="popup"></div>
	</div>
	
</div> <!-- .main closing tag -->  



<?php
include($plugins);
?>
<script type="text/javascript" src="<?php echo $js . '/display.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $js . '/create.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $js . '/delete.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $js . '/add-entry.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $js . '/update.js'; ?>"></script>
