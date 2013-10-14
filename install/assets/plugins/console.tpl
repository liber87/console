//<?php
/**
 * Console 
 * 
 * just make MODx evo is easy
 *
 * @category    plugin
 * @version     0.1
 * @author		By Bumkaka
 * @internal    @properties 
 * @internal    @events OnManagerTreeRender,OnManagerPageInit
 * @internal    @modx_category Manager and Admin
 * @internal    @installset base
 */

$e = &$modx->event; 
switch ($e->name)
{
	case 'OnManagerTreeRender':
	$SCRIPT = <<<console
<script type="text/javascript" src="../assets/js/jquery.min.js"></script>
<script>
jQuery.noConflict()
var icon = '<a onclick="window.open(\'index.php?a=console\',\'gener\',\'width=800,height=500,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')" title="Консоль"><img style="margin:3px 0 0 5px" src="../assets/plugins/console/icons/console.gif"></a>';
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