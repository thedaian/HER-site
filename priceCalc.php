<?php
include 'price_functions.php';

$subtotal=calc_total($_GET['amount'], $_GET['type'], $_GET['back']);

echo '<b>Subtotal: </b>$'.number_format($subtotal, 2).'<br/>';

$tax=$subtotal*0.0675;

echo '<b>+ tax (OH 6.75%): </b>$'.number_format($tax, 2);
echo '<h2>Total: $'.number_format(($subtotal+$tax), 2).'</h2><br>';
?>