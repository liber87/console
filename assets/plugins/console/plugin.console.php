<?php
if ($_SESSION['mgrRole'] != 1) return;
$e = &$modx->event; 
switch ($e->name)
{
    case 'OnManagerTreeRender':
    if ($modal=='yes')
    $SCRIPT = <<<console
var icon = '<a class="treeButton"  onclick="window.open(\'../assets/plugins/console/console.php\',\'gener\',\'width=800,height=560,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')" title="Console"><i class="fa fa-terminal fa-lg"></i></a>';
console;
    
    if ($modal=='no')
    $SCRIPT = <<<console
var icon = '<a class="treeButton" onclick="top.main.location.href=\'../assets/plugins/console/console.php\'" title="Консоль"><i class="fa fa-terminal fa-lg"></i></a>';
console;
    $SCRIPT .= <<<console
var menu = document.getElementById('treeMenu');
var el = document.createElement('td');
el.innerHTML = icon;
if (menu.tagName === 'TABLE') {
    menu.getElementsByTagName('table')[0].getElementsByTagName('tr')[0].appendChild(el);
} else {
    menu.appendChild(el.firstChild);
}
console;
    $e->output('<script>'.$SCRIPT.'</script>');
    break;
}