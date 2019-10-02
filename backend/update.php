<?php include('cards_main.php');


		$SQL="UPDATE orders SET status ='".$_GET['status']."' WHERE id='".$_GET['order']."'";
		
		$query=mysqlquery($SQL);
		
		Header("Location: order.php?id=".$_GET['order']);
		
?>