<?php
//create the main basic page elements
include 'Page.php';

$Page=new Page();
$Page->page=$Page->load_page('main');

//database
include 'Database.php';
$db=new Database();

//make sure everything got filled in
if( (isset($_POST['email'])) && (isset($_POST['password'])) && (isset($_POST['password_confirm'])))
{
	if(!(empty($_POST['email'])||empty($_POST['password'])||empty($_POST['password_confirm'])))
	{
		//make sure the passwords are the same
		if($_POST['password']==$_POST['password_confirm'])
		{
			$email=make_safe($_POST['email']);
			$password=make_safe($_POST['password']);
			$result=$db->get_row($db->query("SELECT clientID FROM clients WHERE email='?'", $email));
			if(isset($result['clientID']))
			{
				//their email is in the system already, so we boot them out and tell them they might have an account with us already
				$contents['error']='<br>There is already an account registered to '.$email.'.  If you have forgotten your password, please reset it.';
				$forms['email_address']=$_POST['email'];
			} else {
				//continue to the registration process
				$db->query("INSERT INTO `clients` (`email`, `password`, `last_activity`) VALUES('?', '?', ?)", $email, md5($password), time());
				$contents['error']=false;
				$contents['email']=$email;
				$contents['password']=$password;
			}
		} else {
			$contents['error']='<br>The passwords you entered do not match.';
			$forms['email_address']=$_POST['email'];
		}
	} else {
		//one of the fields is blank
		$contents['error']='<br>Either the username or one of the password fields were blank, please try registering again.';
		$forms['email_address']=$_POST['email'];
	}
} else {
	//one of the fields never got submitted
	//critical error, die upon it
	die('Error: One of the fields did not submit properly.  Contact the site administrator.');
}

if($contents['error']===false)
{
	$contents['google_ordering']='';
	$contents['logged_in']='';
	$Page->replace_tags($contents, $Page->page);
	
	$Page->main_page=$Page->load_page('new_register_form');
} else {
	$contents['google_ordering']='';

	$contents['logged_in']='';
	$Page->replace_tags($contents, $Page->page);
	
	$Page->main_page=$Page->load_page('index');
	
	$forms['return_to']=$_POST['return_to'];
	$contents['login_form']=$Page->load_page('login_form');
	$contents['register_form']=$Page->load_page('register_form');
	$Page->replace_tags($forms, $contents['login_form']);
	$Page->replace_tags($forms, $contents['register_form']);
}

$Page->replace_tags($contents, $Page->main_page);
$Page->output_page();
?>