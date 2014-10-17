//<?php
/**
 * Console 
 * 
 * just make MODx evo development easy
 *
 * @category    plugin
 * @version     0.2
 * @author		By Bumkaka
 * @internal    @properties 
 * @internal    @events OnManagerTreeRender,OnManagerPageInit
 * @internal	@properties &modal=Use modal;list;yes,no;yes &jquery=jQuery source;list;local (assets/js),remote (google code),manual url (specify below);local (assets/js) &manual=jQuery URL override;text;
 * @internal    @modx_category Manager and Admin
 * @internal    @installset base
 */

$e = &$modx->event; 
switch ($e->name)
{
	case 'OnManagerTreeRender':
	$js ='http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js';
	if ($jquery == 'local (assets/js)'){
	    $js = is_file( dirname(dirname(__FILE__)).'/assets/js/jquery.min.js') ?'../assets/js/jquery.min.js':$js;
	}
	$js = $jquery=='manual url (specify below)' ? $manual:$js;
	
	if ($modal=='yes')
	$SCRIPT = <<<console
<script type="text/javascript" src="{$js}"></script>
<script>
jQuery.noConflict()
var icon = '<a onclick="window.open(\'index.php?a=console\',\'gener\',\'width=800,height=500,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')" title="Console"><img style="margin:3px 0 0 5px" src="../assets/plugins/console/icons/console.gif"></a>';
jQuery('#treeMenu table:first tr').append(icon);
</script>
console;
	
	if ($modal=='no')
	$SCRIPT = <<<console
<script type="text/javascript" src="../assets/js/jquery.min.js"></script>
<script>
jQuery.noConflict()
var icon = '<a onclick="top.main.location.href=\'index.php?a=console\'" title="Консоль"><img style="margin:3px 0 0 5px" src="../assets/plugins/console/icons/console.gif"></a>';
jQuery('#treeMenu table:first tr').append(icon);
</script>
console;
		
	echo $SCRIPT;
	break;
	
	case 'OnManagerPageInit':
	if ($_GET['a']!='console') return;
	include('../assets/plugins/console/console.php');
	die();
	break;
}
