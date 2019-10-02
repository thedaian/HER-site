<?php
		//BUSINESS CARD ORDER ENTRY WEBSITE
		//MASTER INCLUDE FILE
		
		
		///some variables
$status_ids = array ( 1 => "new", 2 => "pending", 3 => "working", 4 => "paid", 5 => "delivered");

$priceof=array();

$priceof['colorlaser1000']=65;
$priceof['colorlaser500']=55;
$priceof['colorlaserback1000']=25;
$priceof['blacklaserback1000']=15;
$priceof['colorlaserback500']=12.50;
$priceof['blacklaserback500']=7.50;

$priceof['litho1000']=99.50;
$priceof['litho2500']=149.50;
$priceof['litho5000']=275.50;

$priceof['150cardoption']=15.95;

$priceof['cutout']=15;

//date_default_timezone_set('EST');

$date_format = "F jS, Y";
$date_small = "m.d.Y";
$date_full = "m.d.Y g:ia";
//MySQL Configuration
 
	$mysql_server = "mysql.studiopolaris.dreamhosters.com"; 	// Address of MySQL server
	$mysql_user = "spdb"; 			// User to connect to database with
	$mysql_password = "ad1914"; 	// Password for above user
 	$mysql_database = "cards"; //name of the databse

//Make the connection

	$connection = @mysql_connect($mysql_server,$mysql_user,$mysql_password);  
		if (!$connection) die("Problem with database. ".mysql_error());
	$db_select = @mysql_select_db($mysql_database);
		if (!$db_select) die("Unable to select $mysql_database database ".mysql_error());
		
 
 	function mysqlquery($sql) //basically error reporting
 	{
 		$query=mysql_query($sql);
 		//echo $sql;
 		if (!$query)
 			die ('<p><b>Mysql Error:</b> '.mysql_error().'<br/> <b>query:</b> '.$sql.'</p>');
 		return $query;
 	}
 
 	function one_result_query($sql) //returns the array for you
 	{
 		$query=mysqlquery($sql);
 		return mysql_fetch_array($query);
 	} 
 	
 	function number_of_rows($sql) //returns count of rows from that SQL
 	{

 		$query=mysqlquery($sql);
 		return mysql_num_rows($query);
 	}



	//here we're gonna set up our common arrays that will be used throughout the session
	
//	echo "chyea";
	
		//echo "COMPANY=".$global_company;
	$company=one_result_query("SELECT * FROM companies WHERE id='".$global_company."'");
	
//	echo "name".$company['name']." !!";
	
	
	if ($global_office != 'all')
	{
		$office=one_result_query("SELECT * FROM offices WHERE id='".$global_office."'");
		
		if ($office['company'] != $company['id']) die ('invalid office');

	}


//////////// FUNCTIONS ///////////////////////////


function currentBack()
{
	if ($_SESSION['backtemplate'])
	{
		
			$template=one_result_query("SELECT * FROM back_templates WHERE id ='".$_SESSION['backtemplate']."'");
			echo "<img src='../phpThumb/phpThumb.php?src=../backs/".$template['src']."/preview.jpg'><br/><i>(current choice)</i>";
			echo "<input type='hidden' name='backtemplate' value='".$_SESSION['backtemplate']."'>";
	}
	else
		echo "<i>(none selected)</i>";
		
	
}


function backtemplatelist($company,$office)
{
	$row_count=3;
	
	$SQL="SELECT * FROM back_templates WHERE company= '".$company."' AND (office = 0";
	
	//echo $SQL;
	
//	echo $SQL;
	
	if ($office) $SQL=$SQL." OR office = '".$office."')";
		else
			$SQL=$SQL." OR 1=1)";
	
	$query=mysqlquery($SQL);
	

		echo "<h4>choose back design</h4><p><i>hover for larger view</i></p>";
		$max=2;
		$count=1;
		
		echo "<div id='backtemplates'>";
		echo "<div id='currentback' style='width:200px; height:140px;'>";
		currentBack();
		echo "</div>";
		
		
		while ($template=mysql_fetch_array($query))
		{
		
				
				echo "<a href='' onClick='return updateBackTemplate(".$template['id'].");'><img class='full' style='border:none;' src='../phpThumb/phpThumb.php?src=../backs/".$template['src']."/preview.jpg'><img style='margin:3px; border:2px solid #000;' src='../phpThumb/phpThumb.php?src=../backs/".$template['src']."/preview.jpg&w=80'></a>";
				if ($count==$max) { $count=0; echo "<br/>"; }
				$count++;
		}	
		echo "</div>";
		
}



function template_list($company,$office,$format)
{
	$row_count=3;
	
	$SQL="SELECT * FROM templates WHERE company= '".$company."' AND (office = 0";
	
	//echo $SQL;
	
//	echo $SQL;
	
	if ($office) $SQL=$SQL." OR office = '".$office."')";
		else
			$SQL=$SQL." OR 1=1)";
	
	$query=mysqlquery($SQL);
	
	
	if ($format=='table')
	{
		echo "<div align='center'><table cellspacing=10 id='templatelist'>\n";
		$count=0;
		echo "<tr>";
		while ($template=mysql_fetch_array($query))
		{
			echo "<td valign='center'><a href='../steptwo/index.php?template=".$template['id']."'><img style='border:3px solid #333;' src='../cards/".$template['src']."/smallpreview.jpg'></a>";
			
			
			echo "</td>";
			if ($count==$row_count-1) { echo "</tr>\n<tr>"; $count=0; }
			else	$count++;
		
		}	
		echo "\n</tr>";
	
		echo "</table></div>";
	} 
	else
	{
		echo "<center><div class='darkbox' style='overflow:auto;'>";
		while ($template=mysql_fetch_array($query))
		{
				echo "<a href='' onClick='return updateTemplate(".$template['id'].");'><img style='border:0px;' src='../cards/".$template['src']."/smallpreview.jpg'></a>";
		}	
		echo "</div></center>";
	}
}

function build_preview($template_id,$person_id)
{
	//$person=one_result_query("SELECT * FROM people WHERE id= '".$person_id."'");
	$template=one_result_query("SELECT * FROM templates WHERE id='".$template_id."'");
	if (!$template) die (mysql_error());
	
	$templatestring= "<img style='border:3px solid #333;' src='../cards/".$template['src']."/fullpreview.jpg'>";
	$templatestring.="<input type='hidden' name='template' value='".$template_id."'>";
	
	
	//print_r($person);
	
	// $template=file_get_contents('../cards/'.$template['src'].'/template.html');
// 	$template=str_replace("%name%",$person['name'],$template);
// 	$template=str_replace("%position%",$person['position'],$template);
// 	
// 	if ($person['direct']) $template=str_replace("%direct%",$person['direct'],$template); else $template=str_replace("%direct%","",$template);
// 	if ($person['cell']) $template=str_replace("%cell%",$person['cell'],$template); else $template=str_replace("%cell%","",$template);
// 	if ($person['office']) $template=str_replace("%office%",$person['office'],$template); else $template=str_replace("%office%","",$template);
// 	if ($person['fax']) $template=str_replace("%fax%",$person['fax'],$template); else $template=str_replace("%fax%","",$template);
// 	
// 	$template=str_replace("%email%",$person['email'],$template);
// 	
// 	$template=str_replace("%website%",$person['website'],$template);
// 	
// 	$template=str_replace("%address%",str_replace("\n","<Br/>",$person['address']),$template);

	
	if ($template['cutout']=='1') $templatestring.="<i>this template requires your photo to be cut out of the background, a $15 one time fee</i>";
	
	echo $templatestring;
	
	
	
}

function order_button($id)
{
	global $priceof;
	
	
	
	
	$order=one_result_query("SELECT * FROM orders WHERE id='".$id."'");
	$template=one_result_query("SELECT * FROM templates WHERE id ='".$order['template']."'");
	
	$item=1;
	
	$printingtotal=$order['subtotal'];
	
	//echo $printingtotal;
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

	}

	
	$description = "order #".$order['id']." - ".$order['type']." business cards ";
	
	if ($order['type']=='laser')
	{
	if ($order['backprinting']=='color')
		$description.="w/ full color back";
	if ($order['backprinting']=='none')
		$description.="one sided";
	if ($order['backprinting']=='black')
		$description.="w/ b&w back";
	}
	else
	{
		$description.="(includes full color back)";
	}
	
	

	
	?>
	
	<div align='right'>
	<form name='checkout' method="POST" action="https://sandbox.google.com/checkout/cws/v2/Merchant/749712147172292/checkoutForm" accept-charset="utf-8">

  <input type="hidden" name="item_name_1" value="Business Cards"/>
  <input type="hidden" name="item_description_1" value="<?=$description?>"/>
  <input type="hidden" name="item_quantity_1" value="<?=$order['quantity']?>"/>
  <input type="hidden" name="item_price_1" value="<?=$printingtotal/$order['quantity'];?>"/>

<?php echo $extras; ?>

  <input type="hidden" name="ship_method_name_1" value="Free Shipping"/>
  <input type="hidden" name="ship_method_price_1" value="0.00"/>

  <input type="hidden" name="tax_rate" value="0.0675"/>
  <input type="hidden" name="tax_us_state" value="OH"/>

  <input type="hidden" name="_charset_"/>

  

	</div>
	
	<?php


}

function mark_order($order,$status)
{
	$SQL="UPDATE orders SET status = '".$status."' WHERE id= '".$order."'";
	mysqlquery($SQL);
}

function order_list($sql)
{
	$query=mysqlquery($sql);
	
	$count=0;
	echo "<table class='orderlist' cellspacing=0 cellpadding=10 width=100%>";
	while ($order=mysql_fetch_array($query))
	{
		$person=one_result_query("SELECT * FROM people WHERE id='".$order['person']."'");
		$company=one_result_query("SELECT * FROM companies WHERE id='".$person['company']."'");
		$template=one_result_query("SELECT * FROM templates WHERE id='".$order['template']."'");
		
		echo "<tr ";
		if ($count % 2 == 0) echo "class='even'";
		echo ">";
	
		echo "<td valign=top>";
		echo "<a href='order.php?id=".$order['id']."'><b>Order #:".$order['id']." - ".date("m/d/Y",$order['date'])."</b></a><br/><Br/>";
		echo $person['name']."<br/>";
		echo $company['name'];
		
		
		echo "</td>";
		
		?>
		
		
		<td valign='top'><p><b><?= $order['quantity'] ?> <?= $order['type'] ?> business cards</b>
		<?php if ($order['cutout']=='yes') echo "<Br/><i>w/ photo cutout</i>"; ?>
		<?php if ($order['backprinting']=='color') echo "<br/><i>w/ full color back</i>"; ?>
		<?php if ($order['backprinting']=='black') echo "<Br/><i>w/ black and white back</i>"; ?>
		<?php if ($order['litho150']=='yes') echo "<Br/><i>w/ 150 laser card option</i>"; ?>
		
		
		
		</p></td>
		<td>
		<img src="http://<?= $company['domain'] ?>/cards/<?= $template['src'] ?>/smallpreview.jpg" width="150px"><br/>
		<span class='caption'><?= $template['name'] ?></span>
		
		</td>
		<?php 
		
		echo "</tr>";
		
		
		$count++;
		
	}
	echo "</table>";
}

function order_summary($id,$button)
{
		$order=one_result_query("SELECT * FROM orders WHERE id='".$id."'");
		$person=one_result_query("SELECT * FROM people WHERE id='".$order['person']."'");
		$template=one_result_query("SELECT * FROM templates WHERE id='".$order['template']."'");
		if ($order['back_template']) $back=one_result_query("SELECT * FROM back_templates WHERE id='".$order['back_template']."'");
		
		//print_r($order);
		
		echo "<div id='ordersummary' style='float:left; margin-right:15px;'><h3>order summary</h3>";
		echo "<h4>order #".$order['id']."</h4>";
		?>
		<table cellspacing=10>
		<td valign='top' align='right'><img src='../cards/<?= $template['src'] ?>/smallpreview.jpg'><br/>
		
		<img src='../backs/<?=$back['src'] ?>/preview.jpg'>
		
		</td>
		
		<td valign='top'><p><b><?= $order['quantity'] ?> <?= $order['type'] ?> business cards</b>
		<?php if ($order['cutout']=='yes') echo "<Br/><i>w/ photo cutout</i>"; ?>
		<?php if ($order['backprinting']=='color') echo "<br/><i>w/ full color back</i>"; ?>
		<?php if ($order['backprinting']=='black') echo "<Br/><i>w/ black and white back</i>"; ?>
		<?php if ($order['litho150']=='yes') echo "<Br/><i>w/ 150 laser card option</i>"; ?>
		
		
		
		</p></td>
		</table>
<!-- 
		<h4>details</h4>
		<p>
		<b><?=$person['name']?></b><br/>
		<?=$person['position']?><br/>
		<?=$person['email']?><Br/><br/>
		<?="<b>address</b> - ".$person['address']?><br/>
		<?="<b>cell</b> -".$person['cell']?><Br/>
		<?="<b>fax</b> -".$person['fax']?><Br/>
		<?="<b>direct</b> -".$person['direct']?><Br/>
		<?="<b>office</b> -".$person['office']?><Br/><Br/>
		<?="<b>website</b> -".$person['website']?><Br/><Br/>
		<?="<b>slogan</b> -".$person['slogan']?><Br/><Br/>
		</p>
		
 -->
		<div align='right'>
		<b>subtotal: </b>$<?=number_format($order['subtotal'],2)?><br/>
		<b>+ tax: </b>$<?=number_format($order['tax'],2)?>
		<h2>total: $<?=number_format($order['total'],2)?></h2>
		</div>

		<?php
		if ($button=='yes')
		{
			$_SESSION['order']=$order['id'];
			echo "<a href='completeOrder.php'><img align='right' style='border:none;' src='../resources/checkout.gif'></a>";
			
		
		}
			//order_button($id);
			

		echo "</div>";
}

		
		





?>