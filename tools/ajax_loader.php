<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model('collection');
Loader::model('page');
Loader::model('area');

if(isset($_GET['id']))
{
	$cID = $_GET['id'];
	$c = Page::getByID($cID);
	$a = new Area('Contenu');
	print $a->display($c);
	exit();
}