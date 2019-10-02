<div id='leftnav'>
<ul>

<?php 
$SQL="SELECT COUNT( * ) AS num
FROM orders
WHERE STATUS = 'ordered'";

$ordered=one_result_query($SQL);

$SQL="SELECT COUNT( * ) AS num
FROM orders
WHERE STATUS = 'proofing'";
$proofing=one_result_query($SQL);

$SQL="SELECT COUNT( * ) AS num
FROM orders
WHERE STATUS = 'printing'";
$printing=one_result_query($SQL);

$SQL="SELECT COUNT( * ) AS num
FROM orders
WHERE STATUS = 'delivered'";
$delivered=one_result_query($SQL);


?>


<li class=''><a href='/list.php?status=ordered'>ordered (<?=$ordered['num']?>)</a></li>
<li class=''><a href='/list.php?status=proofing'>proofing (<?=$proofing['num']?>)</a></li>
<li class=''><a href='/list.php?status=printing'>printing (<?=$printing['num']?>)</a></li>
<li class=''><a href='/list.php?status=delivered'>delivered (<?=$delivered['num']?>)</a></li>

<li class='bold' style='margin-top:10px;'><a href='http://checkout.google.com/sell' target='_blank'>payments</a></li>
<li><form method='get' action='order.php'>order lookup: <input name='id' value='order#' size='5'></form></li>
</ul>
</div>

