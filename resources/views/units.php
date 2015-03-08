
<!doctype html>
<html lang="en" class="">
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
<div id="" class="container">
	<div class="col col-sm-6">
		<input type="text" placeholder="add a new food unit" id="create-new-food-unit" class="form-control">
		<hr>
		<div id="display-food-units"></div>
	</div>
	<div class="col col-sm-6">
		<input type="text" placeholder="add a new exercise unit" id="create-new-exercise-unit" class="form-control">
		<hr>
		<div id="display-exercise-units"></div>
	</div>
      
</div> <!-- .main closing tag -->  



<?php
include($plugins);
?>
<script type="text/javascript" src="<?php echo $js . '/display.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $js . '/create.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $js . '/delete.js'; ?>"></script>
