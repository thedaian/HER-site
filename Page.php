<?php
/*
Page.php

Page class
	header-the header text
	menu-the main menu text
	side_menu-the side menu text
	content-the actual page content
	dirLocation-the directory where the template is stored
	
	function Page($loc='template')
		constructor method
		accepts a directory location, defaults to template
		also loads the header and menu information
	
	function loadPage($template=NULL)
		accepts a template file
		loads the template file into memory
	
	function replace_tags($tags = array(),&$file)
		accepts an array of tags, and a reference to the file (template) being replaced
		replaces tags in the template with the required infomation
	
	function setTable($input,$reset=false)
		accepts a string as input, and an optional boolean value
		resets the table variable if the value is TRUE otherwise
		appends the input string to the end of the table variable
	
	function outputAll()
		outputs all the page files
	
	function outputHeader()
		outputs just the header file
		
	function error($msg)
		accepts a message
		dies with "ERROR:" and the message
*/
define('BASE',0);
define('HEADER',1);
define('PAGE',2);
define('TABLE',3);

class Page
{
	var $page;
	var $html_header;
	var $menu_header;
	var $quick_facts;
	var $dirLocation;

	function __construct($loc='templates/')
	{
		$this->dirLocation=$loc;
		//$this->loadHeaders();
	}

	function loadHeaders($subdirectory='') {
		$this->html_header=$this->load_page('html_header');
		$this->menu_header=$this->load_page($subdirectory.'menu_header');
		$this->quick_facts=$this->load_page('quick_facts');
	}
	
	function load_page($template=NULL, $type=BASE)	{
		if($template!=NULL) { //make sure a page was input
			$template=$this->dirLocation.$template.'.html'; //change template name to conform to the directory location
			if(file_exists($template))
				return file_get_contents($template);
			else
				$this->error("Template file $template not found.");
		} else {
			$this->error('No template file specified.');
		}
	}
	
	function replace_tags($tags = array(), &$file) {//	echo $file;
		if (sizeof($tags) > 0) { //as long as tags has an element
			foreach ($tags as $tag => $data) { //loop through each tag, with data being the key (IE, the value being replaced)
				$file = str_ireplace("[" . $tag . "]", $data, $file); //replace the key CASE INSENSITIVE!!!
			}
		} else { $this->error('No tags designated for replacement.'); }
	}
/*
	function setTable($input,$reset=false)	{
		if($reset==true)
			$this->table='';
		$this->table.=$input;
	}

	function replace_table(&$file)	{
		$file=eregi_replace("{TABLE}", $this->table, $file); //replace the key CASE INSENSITIVE!!!
	}
*/
	function generatePage()	{
		global $homepage, $action;
		//$this->page=str_ireplace("[HTML_HEADER]", $this->html_header, $this->page);
		//$this->page=str_ireplace("[MENU_HEADER]", $this->menu_header, $this->page);
		//$this->page=str_ireplace("[QUICK_FACTS]", $this->quick_facts, $this->page);
		$this->page=str_ireplace("[PAGE_BODY]", $this->main_page, $this->page);
		//$this->page=str_ireplace("[url]", getURL(), $this->page);
		//$this->page=str_ireplace("[homepage]", $homepage, $this->page);
		//$this->page=str_ireplace("[action]", $action, $this->page);
	}

	function output_page()	{
		global $page_start;
		$this->generatePage();
		$page_creation = round(microtime(TRUE) - $page_start, 3);// + '0.02';
		$this->page=str_ireplace("[TIME]", $page_creation, $this->page);
		echo $this->page;
	}

	
	function generate_list($query, $rowHTML, $linesPerRow=3, $encode='')
	{
		$list='';
		global $db;
		for($i=0; $i<$db->how_many($query); $i++)
		{
			$resultSet=$db->get_row($query);
			$row=$rowHTML.chr(13);
			// if($resultSet[0]==$selected)
			// {
				// $resultSet['selected']=' selected="selected"';
			// } else {
				// $resultSet['selected']='';
			// }
			if($encode!='')
				$resultSet[$encode]=htmlentities($resultSet[$encode]);
			$this->replace_tags($resultSet, $row);
			$list.=$row;
			if(($i>0)&&($i%($linesPerRow-1)==0))
			{
			//echo " i= $i %3=";
			//echo $i%3;
				$list.='</tr><tr>';
			}
		}
		return $list;
	}

	function error($msg) {
		die("ERROR: $msg");
	}
}
?>