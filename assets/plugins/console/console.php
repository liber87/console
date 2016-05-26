<?php
	$language = (isset($language))? $language : $modx->config['manager_language'];
	$languageFolder = MODX_BASE_PATH.'assets/plugins/console/lang';
	if (!file_exists($languageFolder."/".$language.".inc.php")) {
    	$language ="english";
	}
	include_once($languageFolder."/".$language.".inc.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type">
		<meta content="<?php echo $language; ?>" name="language">
		<meta content="Bumkaka" name="author">
		<title>Console MODx</title>
		<link rel="stylesheet" type="text/css" href="media/style/<?php echo $modx->config['manager_theme']; ?>/style.css" /> 	
		<script type="text/javascript" src="media/script/mootools/moodx.js"></script>
		<script src="media/script/tabpane.js" type="text/javascript"></script>
		
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
	</head>		

  
	<body style="background-color:#EEEEEE">
    
		<div class="sectionBody">
			<div class="tab-pane" id="modulePane">
				<script type="text/javascript">
							var tpSearchOptions=new WebFXTabPane($('modulePane'));
				</script>
				
				<div class="tab-page" id="tab-sql">
					<h2 class="tab"><?php echo $_lang['run_sql_query'];?></h2>
							
					<form method="POST" action="index.php?a=console">
						<textarea name="sql" style="width:99%;height:150px"><?php 
						echo $_POST['sql']==''?'SELECT id,pagetitle FROM '.$modx->db->config['table_prefix'].'site_content':$_POST['sql']; 
						?></textarea>
						<br/>
						<input type="submit" value="<?php echo $_lang['run']; ?>">
					</form>
					
					<div>
					<?php
					if ($_POST['sql']!=''){
						$tstart = $modx->getMicroTime();
						if (!$result = $modx->db->query($_POST['sql'])) {
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
					
					?>
					</div>
					<script type="text/javascript">
							tpSearchOptions.addTabPage($('tab-sql'));
					</script>	
				</div>
				
				
				
				<div class="tab-page" id="tab-php">
					<h2 class="tab"><?php echo $_lang['run_php_code'];?></h2>
							
					<form method="POST" action="index.php?a=console">
						<textarea name="php" style="width:99%;height:150px"><?php echo $_POST['php']; ?></textarea>
						<br/>
						
						<input type="submit" value="<?php echo $_lang['run']; ?>">
						<div style="height: 200px;overflow: scroll;width: auto;background:none repeat scroll 0 0 #F9F9F9;background: none repeat scroll 0 0 #F9F9F9;
border-color: #999999 #DDDDDD #DDDDDD #999999;
border-radius: 3px 3px 3px 3px;
border-style: solid;
border-width: 1px;
box-shadow: 0 1px 3px #E8E8E8 inset;
margin: 0 5px 0 0;
min-height: 17px;
padding: 4px 2px 4px 4px;
vertical-align: baseline;">
						<?php
						if ($_POST['php']!=''){
							eval($_POST['php']);
						}
						?>
						</div>
					</form>
					<script type="text/javascript">
							tpSearchOptions.addTabPage($('tab-php'));
					</script>	
				</div>
				
				
				<div class="tab-page" id="tab-pek">
					<h2 class="tab"><?php echo $_lang['about'];?></h2>
							
					<p>	MODx Console<br/>
						Author: Bumkaka<br/>
						        thanks Dmi3yy<br/>
						V 0.1 Beta   18 july 2013<br/>
						<br/>
						v 0.1<br/>
						- Created SQL, CSV, Resourse quick create, PHP tabs.<br/>
					</p>
					<script type="text/javascript">
							tpSearchOptions.addTabPage($('tab-pek'));
					</script>	
				</div>
				
			</div>
		</div>	
  </body>
</html>	
