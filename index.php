<?php
//create the main basic page elements
include 'Page.php';

$Page=new Page();
$Page->page=$Page->load_page('main');

//database
include 'Database.php';
$db=new Database();

//client session
session_name('cards');
session_start();

$contents['google_ordering']='';

if(isset($_GET['action']) && ($_GET['action']=="logout"))
{
	unset($_SESSION['clientID']);
	unset($_SESSION['client_name']);
}

$Page->main_page=$Page->load_page('index');
/*
if(isset($_SESSION['clientID']))
{
	$contents['logged_in']='<li>Logged in as '.$_SESSION['client_name'].'.</li><li><a href="order.php">Create a new order</a></li><li><a href="index.php?action=logout">Logout</a></li>';
	$Page->replace_tags($contents, $Page->page);
} else {
	$contents['logged_in']='';
	$Page->replace_tags($contents, $Page->page);
}*/

$Page->replace_tags($contents, $Page->main_page);
$Page->output_page();
?>