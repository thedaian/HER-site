<?php
//create the main basic page elements
include 'Page.php';

$Page=new Page();
$Page->page=$Page->load_page('main');

//sessions
session_name('cards');
session_start();

$contents['google_ordering']='';
/*
if(isset($_SESSION['clientID']))
{
	$contents['logged_in']='<li>Logged in as '.$_SESSION['client_name'].'.</li><li><a href="index.php?action=logout">Logout</a></li>';
} else {
	$contents['logged_in']='';
}*/

$Page->replace_tags($contents, $Page->page);

include 'Database.php';
$db=new Database();

?>