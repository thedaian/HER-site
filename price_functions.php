<?php
function calc_total($quantity, $type, $back, $cutout=false)
{
	//maths!!!
	$price=0;
	//first, seperate by card type
	if($type=="laser")
	{
		//then calculate based on how many cards
		$total=$quantity/10;
		if($back!=0)
		{
			$total+=35*($quantity/1000);
		}
	//litho laminated printing
	} else if($type=="litho")
	{
		//these are expensive
		$total=$quantity/5;
	}
	//if their template requires additional work (ie more pictures)
	if($cutout)
	{
		$total+=15;
	}
	return $total;
}

function generate_price_list($type, $back)
{
	global $db;
	$list='';
	$query=$db->query("SELECT quantity, price FROM prices WHERE product=".$type);
	if(($back>0) && ($type==1)) { $added=35; } else { $added=0; }
	
	for($i=0; $i<$db->how_many($query); $i++)
	{
		$resultSet=$db->get_row($query);
		$row='<option value="'.$resultSet['quantity'].'">'.$resultSet['quantity'].' - $'.number_format(($resultSet['price']+($added*($resultSet['quantity']/1000))),2).'</option>'.chr(13);
		$list.=$row;
	}
	return $list;
	
}

define('TAX', 0.0675);

?>