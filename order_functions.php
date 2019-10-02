<?php
//this file is used for the major functions involved with ordering
//doing this prevents repeated code

//has price functions, right now, just calc_price() but since we use that in two files, better to store it elsewhere than in here
include 'price_functions.php';

function select_front_template()
{
	global $Page, $db, $contents;
	//generates the template list
	$tableRow='<td class="center"><h3>Template #[templateNum]</h3>
	<a class="galleryPopup" id="[templateID]"><img src="card_templates/[fileName]" height="120" width="210" class="template"></a>
	<br><input type="radio" value="[templateID]" name="front"> Template #[templateNum]</td>';
	$query=$db->query("SELECT * FROM templates WHERE catagory>0 ORDER BY templateNum ASC");
	$contents['templates']=$Page->generate_list($query, $tableRow, 5, 'fileName');
	$contents['next_page']="select_back_template";
	$contents['order_info']='';
	
	return 'templates';
}

function select_back_template()
{
	global $Page, $db, $contents;
	//template list for the back
	$tableRow='<td class="center"><br><h4 class="dark_gray">Template #[templateNum]</h4>
	<a class="galleryPopup" id="[templateID]"><img src="card_templates/[fileName]" height="120" width="210" class="template"></a><br>
	<input type="radio" value="[templateID]" name="back" id="back[templateID]"> <label for="back[templateID]" class="small">Use This Back</label><br></td>';
	$query=$db->query("SELECT * FROM templates WHERE catagory=0 ORDER BY templateNum ASC");
	$contents['templates']=$Page->generate_list($query, $tableRow, 5, 'fileName');
	$contents['templates'].='<td class="center" valign="top"><br><h4 class="dark grey">Blank</h4><img src="card_templates/blank.png" height="120" width="210" class="template"><br><input type="radio" value="0" name="back" id="blank"><label for="blank" class="small">Use This Back</label></td>';
	$contents['next_page']="add_info";
	$contents['order_info']='<input type="hidden" name="front" value="'.$_POST['front'].'">';
	
	return 'templates';
}

function add_info()
{
	//add/edit information, such as name/address, phone numbers, etc
	global $contents;
	$contents['next_page']="process_info";
	$contents['order_info']='<input type="hidden" name="front" value="'.$_POST['front'].'">
							<input type="hidden" name="back" value="'.$_POST['back'].'">';
	if(isset($_SESSION['clientID']))
	{
		$contents['order_info'].='<input type="hidden" name="clientID" value="'.$_SESSION['clientID'].'">';
	}
	return 'information_form';
}

function process_info()
{
	global $Page, $db, $contents;
	if($_POST['back']=="no") { $_POST['back']=0; }
	//process the information recieved in add_info()
	if((isset($_POST['newuser'])) && ($_POST['newuser']=="true"))
	{
	//email, password (as md5 hash), name, position, altemail, office, cell, fax, other, address, website, slogan, last activity
	// additionally special  12
		$sql="INSERT INTO clients (`email`, `password`, `name`, `title`, `altemail`, `office`, `cell`, `fax`, `other`, `phone_order`,
		`address`, `website`, `slogan`, `last_activity`) VALUES('?', '?', '?', '?', '?', '?', '?', '?', '?', '?', 
		'?', '?', '?', ?)";
		$db->query($sql, $contents['email'], md5($contents['password']), $_POST['name'], $_POST['position'], $_POST['altemail'], $_POST['office'], $_POST['cell'], $_POST['fax'], $_POST['other'], $_POST['phone_order'], $_POST['address'], $_POST['website'], $_POST['slogan'], time());
	
		if($_POST['special']=="Please use this space to list any special instructions, awards, designations, or design considerations for your business cards.")
		{
			$_POST['special']='';
		}
	} else {
		//if the user is logging in, then we would confirm their information here, or something like that
		//maybe not do it now though
		//$sql="UPDATE clients SET name='?', title='?', altemail='?', office='?', cell='?', fax='?', other='?', address='?', website='?', slogan='?' WHERE clientID=".$_SESSION['clientID'];
		$_POST['special']='';
	}
	
	//$contents['price_list']=
	
	$contents['next_page']="process_order";
	$contents['order_info']='<input type="hidden" name="front" value="'.$_POST['front'].'">
							<input type="hidden" name="back" value="'.$_POST['back'].'" id="backID">
							<input type="hidden" name="special" value="'.$_POST['special'].'">';
	
	//display their chosen templates
	$front=$db->get_row($db->query("SELECT templateNum, fileName FROM templates WHERE templateID=?", $_POST['front']));
	
	$contents['fileName']=$front['fileName'];
	if($_POST['back']>0)
	{
		$back=$db->get_row($db->query("SELECT templateNum, fileName FROM templates WHERE templateID=?", $_POST['back']));
		$contents['backSide']='<h4 class="dark_gray">Back</h4><img src="card_templates/large/'.$back['fileName'].'" height="180" width="315" class="template">';
		$contents['back_litho']='<p style="padding-left:30px; font-size:13px;"><input type="radio" name="lithobacklam" value="yes"><b>Laminated Back Printing </b>Back of the card will be laminated (glossy) as well.  Cannot write on laminated surface.</p>
		<p style="padding-left:30px; font-size:13px;"><input type="radio" name="lithobacklam" value="no"><b>Unlaminated Back Printing </b>Leave the back of the card unlaminated (matte).  Can be written on.</p>';
	} else {
		$contents['backSide']='';
		$contents['back_litho']='';
	}
	$contents['info']='';
	$contents['laser_prices']=generate_price_list(1, $_POST['back']);
	$contents['litho_prices']=generate_price_list(2, $_POST['back']);
	
	$contents['subtotal']=number_format(0, 2);
	$contents['tax']=number_format(0, 2);
	$contents['total']=number_format(0, 2);
	
	return 'options_select';
}
//THIS IS OLD, AND WILL LIKELY BE REMOVED  SAVE FOR NOW SINCE I DONT KNOW IF PAUL WILL WANT TO ADD THIS STEP BACK IN OR NOT
function confirm_order()
{
	global $Page, $db, $contents;
	//gives customer one last chance to confirm the order.  this page can also be used to tell the customer what's going to happen next (IE: send proof to email, etc)
	$cards=explode('|', $_POST['quantity']);
	
	//if they did not want a back side on laser printed cards
	if(isset($_POST['laserback'])||($_POST['laserback']=="none"))
	{
		$backMethod=false;
	//if they wanted full color laser printed cards
	} else if($_POST['laserback']=="color")
	{
		$backMethod="color";
	}
	//if they choose a back side for the litho printed cards
	if(isset($_POST['lithoback'])&&($_POST['lithoback']=="yes"))
	{
		//and they wanted it laminated
		if(isset($_POST['lithobacklam'])&&($_POST['lithobacklam']=="yes"))
		{
			$backMethod="laminated";
		//otherwise, it'll be unlaminated
		} else {
			$backMethod="unlaminated";
		}
	} else {
	//otherwise, they didn't choose a back and nothing will be printed
		$backMethod=false;
	}
	//cost calculations
	$subtotal=calc_total($cards[0], $cards[1], $backMethod); //add a cutout option for certain templates
	$contents['subtotal']=number_format($subtotal, 2);
	$contents['tax']=number_format($subtotal*TAX, 2);
	$contents['total']=number_format($subtotal+$subtotal*TAX, 2);
	
	
	//tell them how they choose printing options
	$contents['info']=$cards[0].' cards '.(($cards[1]=="laser") ? "laser" : "litho laminated").' printed.';
	
	$contents['next_page']="process_order";
	//set of POST values to be repeated per page
	$contents['order_info']='<input type="hidden" name="front" value="'.$_POST['front'].'">
							<input type="hidden" name="back" value="'.$_POST['back'].'">
							<input type="hidden" name="special" value="'.$_POST['special'].'">
							<input type="hidden" name="quantity" value="'.$_POST['quantity'].'">';
	if($backMethod) { $contents['order_info'].='<input type="hidden" name="backMethod" value="'.$backMethod.'">'; }
	return 'confirm_order';
}
/*
function calc_total($quantity, $type, $backMethod, $cutout=false)
{
	//maths!!!
	$price=0;
	//first, seperate by card type
	if($type=="laser")
	{
		//then calculate based on how many cards
		if($quantity==500)
		{
			$total=55;
		} else if($quantity==1000)
		{
			$total=65;
		}
		if($backMethod=="color")
		{
			$total+=35*($quantity/1000);
		}
	//litho laminated printing
	} else if($type=="litho")
	{
		//these are expensive
		if($quantity==1000)
		{
			$total=99.50;
		} else if($quantity==2500)
		{
			$total=149.50;
		} else if($quantity==5000)
		{
			$total=275.50;
		}
	}
	//if their template requires additional work (ie more pictures)
	if($cutout)
	{
		$total+=15;
	}
	return $total;
}*/

function process_order()
{
	global $Page, $db, $contents;
	
	$quantity=(isset($_POST['laser_quantity']))? $_POST['laser_quantity'] : $_POST['litho_quantity'];
	$type=(isset($_POST['laser_quantity']))? "laser" : "litho";

	$form['quantity']=$quantity;
	//send values to google checkout, process the result (if possible, look into how google checkout works)
	//need to create a form, and work with that
	//finally, put everything into the database
	$back=(isset($_POST['back']))? $_POST['back'] : false;
	$form['price']=calc_total($quantity, $type, $back); //add a cutout option for certain templates
	$special=make_safe($_POST['special']);
	
	//input values into the database, and get back the order ID
	$db->query("INSERT INTO `orders` (`cardAmount`, `printingType`, `backMethod`, `subtotal`, `tax`, `time_placed`, `special_instructions`, `clientID`, `templateID`, `backTemplateID`) VALUES(?, ?, '?', ?, ?, ?, '?', ?, ?, ?)", $quantity, (($type=="laser")? 0 : 1), '', $form['price'], ($form['price']*TAX), time(), $special, $_SESSION['clientID'], $_POST['front'], $_POST['back']);
	
	$orderID=$db->get_row($db->query("SELECT LAST_INSERT_ID()"));
	
	//fills out the order form for google checkout
	$contents['google_form']=$Page->load_page('google_order_form');
	$contents['google_ordering']='setTimeout("submitForm()", 5000);
function submitForm()
{
	alert("form submitted");
    //document.checkout.submit();
}';
	$form['description']='order #'.$orderID[0].' - '.$type.' business cards ';
	
	if ($type=='laser')
	{
		$form['description'].= ($back>0) ? "w/ full color back" : "one sided";
	} else 
	{
		$form['description'].="(includes full color back)";
	}
	
	$form['extra_items']='';
	
	$Page->replace_tags($form, $contents['google_form']);
	
	//send mail to studiopolaris@aol.com when an order is recieved
	if(SERVER)
	{
		mail("studiopolaris@aol.com", "Business Card Order #".$order[0], "A new order for business cards has been submitted.  Log into the backend system to review it and process the order.");
	}
	
	//these are for extra items, things like if the templates require a photocutout, or if they're choosing 150 litho laminated cards
	//in those cases, we want to add a second/third item to their order in google checkout
	//these options are not currently supported on the site, so they are being left out right now
	/*
	if ($order['cutout']=='yes')
	{	
		$printingtotal = $printingtotal - $priceof['cutout'];
			$item++;
			
			$extras.='<input type="hidden" name="item_name_'.$item.'" value="Photo Cutout"/>
  <input type="hidden" name="item_description_'.$item.'" value="template requires cutout"/>
  <input type="hidden" name="item_quantity_'.$item.'" value="1"/>
  <input type="hidden" name="item_price_'.$item.'" value="'.$priceof['cutout'].'"/>';

	}
	
	
	if ($order['litho150']=='yes')
	{	
		$printingtotal = $printingtotal - $priceof['150cardoption'];
			$item++;
			
			$extras.='<input type="hidden" name="item_name_'.$item.'" value="150 Laser Card Option"/>
  <input type="hidden" name="item_description_'.$item.'" value="temporary laser printed cards"/>
  <input type="hidden" name="item_quantity_'.$item.'" value="1"/>
  <input type="hidden" name="item_price_'.$item.'" value="'.$priceof['150cardoption'].'"/>';
  */
	
	return 'complete_order';
}
?>