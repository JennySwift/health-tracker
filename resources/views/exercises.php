
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
	<input type="text" placeholder="add a new exercise" id="create-new-exercise" class="form-control">
	<hr>
	<div id="display-exercises"></div>
      
</div> <!-- .main closing tag -->  



<?php
include($plugins);
?>
<script type="text/javascript" src="<?php echo $js . '/delete.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $js . '/create.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $js . '/display.js'; ?>"></script>
