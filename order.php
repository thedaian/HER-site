<?php
//create the main basic page elements
include 'common.php';

if(isset($_POST['next_page']))
{
	$action=$_POST['next_page'];
	if(isset($_POST['front']))
	{
		$_POST['front']=make_safe($_POST['front']);
	}
	if(isset($_POST['back']))
	{
		$_POST['back']=make_safe($_POST['back']);
	}
} else {
	if(isset($_POST['front'])&&($_POST['front']>0))
	{
		if($_POST['back']=='yes')
		{
			$action='select_back_template';
		} else {
			$action='add_info';
		}
		$front=make_safe($_POST['front']);
	} else {
		$action='select_front_template';
	}
}

if(($action=='add_info')||($action=='process_info'))
{
	$contents['email_address']=isset($_POST['email']) ? $_POST['email'] : '';
	
	$contents['name']=isset($_POST['name']) ? $_POST['name'] : '';
	$contents['altemail']=isset($_POST['altemail']) ? $_POST['altemail'] : '';
	$contents['direct']=isset($_POST['direct']) ? $_POST['direct'] : '';
	$contents['office']=isset($_POST['office']) ? $_POST['office'] : '';
	$contents['cell']=isset($_POST['cell']) ? $_POST['cell'] : '';
	$contents['fax']=isset($_POST['fax']) ? $_POST['fax'] : '';
	$contents['other']=isset($_POST['other']) ? $_POST['other'] : '';
	
	$contents['address']=isset($_POST['address']) ? $_POST['address'] : '';
	$contents['website']=isset($_POST['website']) ? $_POST['website'] : '';
	$contents['slogan']=isset($_POST['slogan']) ? $_POST['slogan'] : '';
	
	$contents['error']='';

	if((isset($_POST['newuser'])) && ($_POST['newuser']=="false"))
	{
		//login functionality
		if(!(empty($_POST['email'])||empty($_POST['password'])))
		{
			$email=make_safe($_POST['email']);
			$password=make_safe($_POST['password']);
			$result=$db->get_row($db->query("SELECT clientID, name, password FROM clients WHERE email='?'", $email));
			if(isset($result['name']))
			{
				//old website used md5 hashing, so we're using it here, even though it's less secure and has a greater chance of collisions
				//legacy software, hooray for that
				if(md5($password)==$result['password'])
				{
					$contents['error']=false;
					$_SESSION['clientID']=$result['clientID'];
					$_SESSION['client_name']=$result['name'];
					$action='process_info';
				} else {
					$contents['error']='<tr><td colspan="2">Password incorrect, please try again.</td></tr>';
					$contents['email_address']=$_POST['email'];
					$action='add_info';
				}
			} else {
				$contents['error']='<tr><td colspan="2">Email not found.</td></tr>';
				$contents['email_address']=$_POST['email'];
				$action='add_info';
			}
		} else {
			$contents['error']='<tr><td colspan="2">Either the username or password was blank, please log in again.</td></tr>';
			$contents['email_address']=$_POST['email'];
			$action='add_info';
		}
	} else if((isset($_POST['newuser'])) && ($_POST['newuser']=="true"))
	{
		//registration functionality
		if(!(empty($_POST['email'])||empty($_POST['password'])||empty($_POST['password_confirm'])))
		{
			//make sure the passwords are the same
			if($_POST['password']==$_POST['password_confirm'])
			{
				$email=make_safe($_POST['email']);
				$password=make_safe($_POST['password']);
				
				//not sure if we need this/if it's even a good idea.  well, it's probably a good idea, but might not be needed for something like this site
				//maybe ask paul
				//$result=$db->get_row($db->query("SELECT clientID FROM clients WHERE email='?'", $email));
				if(isset($result['clientID']))
				{
					//their email is in the system already, so we boot them out and tell them they might have an account with us already
					$contents['error']='<tr><td colspan="2">There is already an account registered to '.$email.'.  If you have forgotten your password, please reset it.</td></tr>';
					$contents['email_address']=$_POST['email'];
					$action='add_info';
				} else {
					//continue to the registration process
					$contents['error']=false;
					$contents['email']=$email;
					$contents['password']=$password;
					$action='process_info';
				}
			} else {
				$contents['error']='<tr><td colspan="2">The passwords you entered do not match.</td></tr>';
				$contents['email_address']=$_POST['email'];
				$action='add_info';
			}
		} else {
			//one of the fields is blank
			$contents['error']='<tr><td>Either the username or one of the password fields were blank, please try registering again.</td></tr>';
			$contents['email_address']=$_POST['email'];
			$action='add_info';
		}
	}
	
	if(isset($_SESSION['clientID']))
	{
		$action='process_info';
		/*$contents['login_form']=$Page->load_page('welcome_back_form');
		$forms['client_name']=$_SESSION['client_name'];
		$Page->replace_tags($forms, $contents['login_form']);
		$contents['register_form']='';*/
	} else {
		//$contents['login_form']=$Page->load_page('login_form');
		$contents['register_form']=$Page->load_page('register_form');
		//$Page->replace_tags($forms, $contents['login_form']);
		//$Page->replace_tags($forms, $contents['register_form']);
	}
}

include 'order_functions.php';

$contents['google_ordering']='';

switch($action)
{
	case 'select_front_template':
		$contents['title']="Select a template for the front of your card";
		$page=select_front_template();
		break;
	case 'select_back_template':
		if(isset($_POST['front']))
		{
			$contents['title']="Select a template for the back of your card";
			$page=select_back_template();
		} else {
			$contents['title']='Select a template for the front of your card';
			$page=select_front_template();
		}
		break;
	case 'add_info':
		if(isset($_POST['back']))
		{
			$contents['title']="New Customers";
			$page=add_info();
		} else {
			$contents['title']="Select a template for the back of your card";
			$page=select_back_template();
		}
		break;
	case 'process_info':
		$contents['title']="Select printing options";
		$page=process_info();
		break;
	case 'confirm_order';
		$contents['title']="Confirm your order.";
		$page=confirm_order();
		break;
	case 'process_order';
		//print_r($_POST);
		$page=process_order();
		break;
}
$Page->replace_tags($contents, $Page->page);

$Page->main_page=$Page->load_page($page);
$Page->replace_tags($contents, $Page->main_page);

$Page->output_page();
?>