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
 * @internal    @events OnManagerTreeRender
 * @internal	@properties &modal=Use modal;list;yes,no;yes
 * @internal    @modx_category Manager and Admin
 * @internal    @installset base
 */

if ($_SESSION['mgrRole'] != 1) return;
$e = &$modx->event; 
switch ($e->name)
{
    case 'OnManagerTreeRender':
    if ($modal=='yes')
    $SCRIPT = <<<console
<script>
var icon = '<a class="treeButton"  onclick="window.open(\'../assets/plugins/console/console.php\',\'gener\',\'width=800,height=520,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')" title="Console"><i class="fa fa-terminal fa-lg"></i></a>';
var menu = jQuery('#treeMenu');
if (menu.is('table')) {
    jQuery('table:first tr',menu).append(jQuery(icon).wrap('<td/>').parent());
} else {
    menu.append(icon);
}
</script>
console;
    
    if ($modal=='no')
    $SCRIPT = <<<console
<script>
var icon = '<a class="treeButton" onclick="top.main.location.href=\'../assets/plugins/console/console.php\'" title="Консоль"><i class="fa fa-terminal fa-lg"></i></a>';
var menu = jQuery('#treeMenu');
if (menu.is('table')) {
    jQuery('table:first tr',menu).append(jQuery(icon).wrap('<td/>').parent());
} else {
    menu.append(icon);
}
</script>
console;
        
    echo $SCRIPT;
    break;
}

