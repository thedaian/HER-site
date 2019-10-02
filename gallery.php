<?php
//create the main basic page elements
include 'common.php';

$Page->main_page=$Page->load_page('gallery');
//30% size: height="180" width="315"
//20% size: height="120" width="210"

if(isset($_GET['catagory']))
{
	$catagory=make_safe($_GET['catagory']);
} else {
	$catagory=1;
}

if($catagory>0)
{
	$tableRow='<td class="center"><h4 class="dark_gray">Template #[templateNum]</h4>
<a class="galleryPopup" id="[templateID]"><img src="card_templates/template.png" height="120" width="210" class="template"></a><br>
<form action="order.php" method="POST" name="[templateID]"><input type="hidden" name="front" value="[templateID]">
<input type="radio" name="back" value="yes" checked="true" id="backyes[templateID]"><label for="backyes[templateID]" class="small">With Back</label> 
<input type="radio" name="back" value="no" id="backno[templateID]"><label for="backno[templateID]" class="small">Without Back</label>
<br><input type="submit" value="Use Template"></form></td>';
} else {
	$tableRow='<td class="center"><h3 class="dark_gray">Template #[templateNum]</h3>
<a class="galleryPopup" id="[templateID]"><img src="card_templates/template.png" height="120" width="210" class="template"></a><br></td>';
}

switch($catagory)
{
	case 0:
		$contents['catagory_name']='Back';
		$contents['catagory0']='';
		$contents['catagory0a']='';
		$contents['catagory1']='<a href="gallery.php?catagory=1">';
		$contents['catagory1a']='</a>';
		$contents['catagory2']='<a href="gallery.php?catagory=2">';
		$contents['catagory2a']='</a>';
		break;
	case 2:
		$contents['catagory_name']='Team Front';
		$contents['catagory2']='';
		$contents['catagory2a']='';
		$contents['catagory0']='<a href="gallery.php?catagory=0">';
		$contents['catagory0a']='</a>';
		$contents['catagory1']='<a href="gallery.php?catagory=1">';
		$contents['catagory1a']='</a>';
		break;
	default:
	case 1:
		$contents['catagory_name']='Individual Front';
		$contents['catagory1']='';
		$contents['catagory1a']='';
		$contents['catagory0']='<a href="gallery.php?catagory=0">';
		$contents['catagory0a']='</a>';
		$contents['catagory2']='<a href="gallery.php?catagory=2">';
		$contents['catagory2a']='</a>';
		break;
}

$query=$db->query("SELECT * FROM templates WHERE catagory=? ORDER BY templateNum ASC", $catagory);

$contents['templates']=$Page->generate_list($query, $tableRow, 5, 'fileName');

$Page->replace_tags($contents, $Page->main_page);
$Page->output_page();
?>