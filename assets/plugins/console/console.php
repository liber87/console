<?php
define('MODX_API_MODE', true);
define('IN_MANAGER_MODE', true);
include_once(__DIR__."/../../../index.php");
$modx->db->connect();
if (empty ($modx->config)) {
    $modx->getSettings();
}
if (!isset($modx->pluginCache['Console'])){
    die();
}
$modx->invokeEvent('OnManagerPageInit',array('invokedBy'=>'Console'));
$language = $modx->config['manager_language'];
include_once(MODX_BASE_PATH.'assets/plugins/console/lang/english.inc.php');
$languageFile = MODX_BASE_PATH."assets/plugins/console/lang/{$language}.inc.php";
if (file_exists($languageFile)) include_once ($languageFile);
if(!isset($_SESSION['mgrValidated']) || $_SESSION['mgrRole'] != 1) {
	die($_lang['login_as_admin']);
}
if (!isset($_SESSION['console'])) {
	$_SESSION['console'] = array(
		'sql' => '',
		'php' => ''
	);
}

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
	&& (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
$q = $modx->db->query("SHOW TABLES");
$tables = array();
while ($row = $modx->db->getRow($q)) {
    $tables[] = array_values($row)[0];
}
if ($isAjax && isset($_POST['code']) && isset($_POST['mode'])) {
	$out = '';
	$mode = $_POST['mode'];
	$code = $_POST['code'];
	$_SESSION['console'][$mode] = $code;
	if ($mode == 'php') {
		eval(preg_replace('/^<\?php(.*)(\?>)?$/s', '$1', $code));
	} else {
		if ($code!=''){
			$tstart = $modx->getMicroTime();
			if (!$result = $modx->db->query($code)) {
				echo $modx->db->getLastError();
			} else {
				$tend = $modx->getMicroTime();
				$totaltime = $tend - $tstart;
				$i=0;
				while($row=$modx->db->getRow($result) ){
					if ($i==0){
						foreach($row as $key=>$value){
							$head .= '<th>'.$key.'</th>';
						}
						$head = '<tr>'.$head.'</tr>
';
						$i=1;
					}
					$ROW='';
					foreach($row as $key=>$value){
							$ROW .= '<td><pre>'.    $text=mb_substr($value,0,30).'</pre></td>';
					}
					
					$body.='<tr '.($i % 2 == 0?'class="even"':'').'>'.$ROW.'</tr>
';								
					$i++;
				}
				
				$table = '<div style="height: 200px;overflow: scroll;width: auto;"><table class="MySql" border=1 cellspacing=0 cellpadding=3>'.$head.$body.'</table></div>';
				echo $lang['query_complete'].' '.$modx->db->getAffectedRows().' '.$_lang['rows'].' '.$totaltime.' '.$_lang['time'].'<br/>';
				echo $table;
			}
			
		}
	}
	die();
}
?>
<!DOCTYPE html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type">
		<meta content="<?php echo $language; ?>" name="language">
		<meta content="Bumkaka" name="author">
		<title>Console MODx</title>
		<link rel="stylesheet" type="text/css" href="<?php echo MODX_MANAGER_URL; ?>media/style/<?php echo $modx->config['manager_theme']; ?>/style.css" /> 	
		<script src="<?php echo MODX_MANAGER_URL; ?>media/script/jquery/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo MODX_SITE_URL; ?>assets/plugins/console/ace/ace.js" type="text/javascript"></script>
		<script src="<?php echo MODX_SITE_URL; ?>assets/plugins/console/ace/ext-language_tools.js" type="text/javascript"></script>
		<style>
			 .MySql th, .MySql td {
				margin: 0.1em;
				padding: 0.3em;
				text-shadow: 0 1px 0 #FFFFFF;
				vertical-align: middle;
			}
			.MySql{
			border-collapse: collapse;
			}
            .tables, .queries {
                display: inline-block;
                margin-bottom:15px;
            }
            .tables select, .tables button,  .queries select, .queries button{
                width:auto;
                display: inline-block;
            }
			th {
				background: -moz-linear-gradient(center top , #FFFFFF, #CCCCCC) repeat scroll 0 0 transparent;
				color: #000000;
				font-weight: bold;
			}
			table tr.even th, .even {
				background: none repeat scroll 0 0 #DFDFDF;
			}
			
			.MySql td:hover{
				background-color:#B6C6D7;
				color: #000000;
			}
			pre{
				font:13px Arial;
				}
			
		</style>
		<script type="text/javascript">
			(function($){
				$(document).ready(function(){
					var pane = $('#modulePane').addClass('dynamic-tab-pane-control');
					var tabs = $('.tab',pane).remove();
					$('.tab-page').hide().eq(0).show();
					tabs.wrapInner('<span/>').prependTo(pane).wrapAll('<div class="tab-row"/>');
					pane.on('click','.tab',function(){
						$('.tab').removeClass('selected');
						$(this).addClass('selected');
						var index = $(this).addClass('selected').index();
						$('.tab-page').hide().eq(index).show();
					});
					$('.tables').on('click','button',function(e){
					    var text = '`'+$('select[name="tables-list"]').val()+'`';
					    var editor = ace.edit('sql-editor');
                        editor.getSession().insert(editor.getCursorPosition(), text);
                    }).on('change','select',function(e){
                        var table = '`'+this.value+'`';
                        $('option','select[name="tables-queries"]').each(function(){
                            var value = this.value;
                            $(this).text(this.value.replace('[+table+]',table));
                        });
                    });
                    $('select[name="tables-list"]').trigger('change');
                    $('.queries').on('click','button',function(e){
                        var text = $('select[name="tables-queries"]').val();
                        var table = '`'+$('select[name="tables-list"]').val()+'`';
                        text = text.replace('[+table+]',table);
                        var editor = ace.edit('sql-editor');
                        editor.getSession().insert(editor.getCursorPosition(), text);
                    });
					$('.tab',pane).eq(0).click();
					$('textarea[data-editor]').each(function () {
			            var textarea = $(this);
			            var mode = textarea.data('editor');
			            var editDiv = $('<div>', {
			                id:'sql-editor',
			                width: '100%',
			                height: 150
			            }).insertBefore(textarea);
			            textarea.hide();
			            var editor = ace.edit(editDiv[0]);
			            editor.$blockScrolling = Infinity;
			            editor.getSession().setValue(textarea.val());
			            editor.getSession().setMode("ace/mode/" + mode);
			            ace.config.loadModule("ace/ext/emmet", function () {
				            ace.require("ace/lib/net").loadScript("emmet.js", function () {
				                editor.setOption("enableEmmet", true);
				            });
				        });
				 
				        ace.config.loadModule("ace/ext/language_tools", function () {
				            ace.require("ace/lib/net").loadScript("emmet.js", function () {
				                editor.setOptions({
					                enableSnippets: true,
					                enableBasicAutocompletion: true,
					                enableLiveAutocompletion: false
					            });
				            });
				        });
			            textarea.closest('form').submit(function () {
			                textarea.val(editor.getSession().getValue());
			            })
			        });
			        $('form').submit(function(e){
			        	e.preventDefault();
			        	var form = $(this);
			        	var textarea = $('textarea[data-editor]',this);
			        	var code = textarea.val();
			        	var mode = textarea.data('editor');
			        	var results = $('.results',form.parent());
			        	$('input').prop('disabled',true);
			        	$.post(window.location.href,{
			        		mode: mode,
			        		code: code
			        	},function(response){
			        		$('input').prop('disabled',false);
			        		results.html(response);
			        	}).fail(function(xhr){
			        		results.html(xhr.responseText);
			        		$('input').prop('disabled',false);
			        		alert('Server error '+xhr.status+': '+xhr.statusText);
			        	});
			        });
				});
			})(jQuery)
		</script>
	</head>		

  
	<body style="background-color:#EEEEEE">
    
		<div class="sectionBody">
			<div class="tab-pane" id="modulePane">
				<div class="tab-page" id="tab-sql">
					<h2 class="tab"><?php echo $_lang['run_sql_query'];?></h2>
                    <div class="queries">
                        <select name="tables-queries">
                            <?php
                            $options = array("SELECT * FROM [+table+] WHERE ", "UPDATE [+table+] SET  WHERE ","INSERT INTO [+table+] () VALUES ()","DELETE FROM [+table+] WHERE ","TRUNCATE TABLE [+table+]","DROP TABLE [+table+]");
                            foreach ($options as $option) {
                                echo "<option value=\"{$option}\">{$option}</option>";
                            }
                            ?>
                        </select>
                        <button class="fa fa-paste"></button>
                    </div>
					<div class="tables">
                        <select name="tables-list">
                            <?php
                                foreach ($tables as $table) {
                                    echo "<option value=\"{$table}\">{$table}</option>";
                                }
                            ?>
                        </select>
                        <button class="fa fa-paste"></button>
                    </div>
					<form method="POST">
						<textarea name="sql" data-editor="sql" class="sql"><?php 
						echo $_SESSION['console']['sql'];
						?></textarea>
						<br/>
						<input type="submit" value="<?php echo $_lang['run']; ?>">
					</form>
					
					<div class="results"></div>
				</div>
				
				
				
				<div class="tab-page" id="tab-php">
					<h2 class="tab"><?php echo $_lang['run_php_code'];?></h2>
							
					<form method="POST">
						<textarea name="php" data-editor="php" class="php"><?php echo $_SESSION['console']['php']==''?"<?php\n":$_SESSION['console']['php']; ?></textarea>
						<br/>
						
						<input type="submit" value="<?php echo $_lang['run']; ?>">
						<div class="results" style="height: 200px;overflow: scroll;width: auto;background:none repeat scroll 0 0 #F9F9F9;background: none repeat scroll 0 0 #F9F9F9;
border-color: #999999 #DDDDDD #DDDDDD #999999;
border-radius: 3px 3px 3px 3px;
border-style: solid;
border-width: 1px;
box-shadow: 0 1px 3px #E8E8E8 inset;
margin: 0 5px 0 0;
min-height: 17px;
padding: 4px 2px 4px 4px;
vertical-align: baseline;">
						</div>
					</form>
				</div>
				
				
				<div class="tab-page" id="tab-pek">
					<h2 class="tab"><?php echo $_lang['about'];?></h2>
							
					<p>	MODx Console<br/>
						Author: Bumkaka<br/>
						        thanks Dmi3yy<br/>
					</p>
				</div>
				
			</div>
		</div>	
  </body>
</html>	
