<?php
include 'Database.php';
$db=new Database();

$result=$db->get_row($db->query("SELECT * FROM templates WHERE templateID=?", $_GET['id']));

echo '<h2 class="red">Template #'.$result['templateNum'].'</h2><br><img src="card_templates/large/template.png" height="300" width="525">';

if($result['catagory']>0)
{
	echo '<form action="order.php" method="POST" name="'.$result['templateID'].'">';
	echo '<input type="hidden" name="front" value="'.$result['templateID'].'">';
	echo '<input type="radio" name="back" value="yes" checked="true" id="backyes'.$result['templateID'].'">';
	echo '<label for="backyes'.$result['templateID'].'" class="small">With Back</label> ';
	echo '<input type="radio" name="back" value="no" id="backno'.$result['templateID'].'">';
	echo '<label for="backno'.$result['templateID'].'" class="small">Without Back</label>';
	echo '<br><input type="submit" value="Use Template"></form>';
}
?>