<?php include ('resources/top.php'); ?>

<?php include ('resources/leftnav.php'); ?>

<div id='content'>
<h1>order list</h1>
<h2><?=$_GET['status']?></h2>

<?php order_list("SELECT * FROM orders WHERE status='".$_GET['status']."' ORDER BY date ASC"); ?>




</div>


<?php include ('resources/bottom.php'); ?>