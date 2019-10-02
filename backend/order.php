<?php include ('resources/top.php'); ?>

<?php include ('resources/leftnav.php'); ?>

<?php

	$order=one_result_query("SELECT * FROM orders WHERE id= '".$_GET['id']."'");
		if (!$order) die ("<div id='content'><h3>order not found</h3></div>");
		
	$person=one_result_query("SELECT * FROM people WHERE id= '".$order['person']."'");
		if (!$person) die ("<h3>client not found</h3>");
		$company=one_result_query("SELECT * FROM companies WHERE id ='".$person['company']."'");
	
	
	$template=one_result_query("SELECT * FROM templates WHERE id= '".$order['template']."'");
	if ($order['back_template'])
		$back=one_result_query("SELECT * FROM back_templates WHERE id='".$order['back_template']."'");
	
	
		
		?>
		
	
<div id='content'>
<h1>order detail</h1>
<h2 style='margin-bottom:0px;'>order #<?=$order['id']?> - <?=$order['status']?></h2>

<a href="update.php?order=<?=$_GET['id']?>&status=proofing">mark as proofing</a> - 
<a href="update.php?order=<?=$_GET['id']?>&status=printing">mark as printing</a> - 
<a href="update.php?order=<?=$_GET['id']?>&status=delivered">mark as delivered</a><br/><br/>

	

<a href='list.php?status=<?=$order['status']?>'>back to order list</a>
<table cellspacing=10 cellpadding=10 width=100%>

<td valign=top><h3>client</h3>

<p><b><?=$person['name'] ?></b><Br/>
<?=$company['name']?><br/>
<a href="mailto:<?=$person['email']?>"><?=$person['email']?></a></p>

		<h3>details</h3>
		<p>

		<?=$person['position']?><br/>

		<?="<b>address</b> - ".$person['address']?><br/>
		<?="<b>cell</b> -".$person['cell']?><Br/>
		<?="<b>fax</b> -".$person['fax']?><Br/>
		<?="<b>direct</b> -".$person['direct']?><Br/>
		<?="<b>office</b> -".$person['office']?><Br/><Br/>
		<?="<b>website</b> -".$person['website']?><Br/><Br/>
		<?="<b>slogan</b> -".$person['slogan']?><Br/><Br/>
		</p>
		

<p><a href="http://<?=$company['domain']?>/userpictures/<?=$person['pic']?>">Headshot File</a></p>

</td>

<td valign=top><h3>cards</h3>
<p><b><?= $order['quantity'] ?> <?= $order['type'] ?> business cards</b>
		<?php if ($order['cutout']=='yes') echo "<Br/><i>w/ photo cutout</i>"; ?>
		<?php if ($order['backprinting']=='color') echo "<br/><i>w/ full color back</i>"; ?>
		<?php if ($order['backprinting']=='black') echo "<Br/><i>w/ black and white back</i>"; ?>
		<?php if ($order['litho150']=='yes') echo "<Br/><i>w/ 150 laser card option</i>"; ?>
		<?php echo "<Br/>".nl2br($order['specialinstructions']);		?>
		
		</p>
</td>
<td valign=top><h3>template</h3>
<img src="http://<?= $company['domain'] ?>/cards/<?= $template['src'] ?>/smallpreview.jpg"><Br/>
<span class='caption'><?= $template['name'] ?></span><br/><br/>

<?php if ($back) { ?>

	<img src="http://<?= $company['domain'] ?>/backs/<?= $back['src'] ?>/preview.jpg"><Br/>
	<span class='caption'><?= $back['name'] ?></span><br/><br/>

<?php } ?>

</td>

</table>
</div>


<?php include ('resources/bottom.php'); ?>