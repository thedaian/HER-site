<?php
//create the main basic page elements
include 'common.php';

$Page->main_page=$Page->load_page('coming_soon');

if(isset($_SESSION['clientID']))
{
	$contents['logged_in']='<li>Logged in as '.$_SESSION['client_name'].'.</li><li><a href="order.php">Create a new order</a></li><li><a href="index.php?action=logout">Logout</a></li>';
	$Page->replace_tags($contents, $Page->page);
} else {
	$contents['logged_in']='';
	$Page->replace_tags($contents, $Page->page);
}

$contents['title']="Product Listing";

$Page->replace_tags($contents, $Page->main_page);
$Page->output_page();
?>