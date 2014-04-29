<?php

	include_once('../assets/libs/document.class.inc.php');
	$language = (isset($language))? $language : $modx->config['manager_language'];
	$languageFolder = MODX_BASE_PATH.'assets/plugins/console/lang';
	if (!file_exists($languageFolder."/".$language.".inc.php")) {
    	$language ="english";
	}
	include_once($languageFolder."/".$language.".inc.php");
	if ($_POST['fast_resourse']!=''){
		if ($_POST['type']=='1'){
			$p = explode('<br />',nl2br(strip_tags($_POST['fast_resourse'])));
			if (count($p)>1){
				foreach($p as $key=>$value){
					if (str_replace(' ','',trim($value))!='')	$pages[] = trim($value);
				}
			} else {
				$pages = explode (",",$_POST['fast_resourse']);
			}

			if ($_POST['parent']!=''){
				$doc = new Document($_POST['parent']);
				$doc->Set('isfolder',1);
				$doc->Save();
			}
			foreach($pages as $page) {
				$doc = new Document();
				$doc->Set('parent', $_POST['parent']==''?0:$_POST['parent']);
				$doc->Set('template', $_POST['template']);
				$doc->Set('published', $_POST['publishe']==''?1:$_POST['published']);
				$doc->Set('hidemenu', '0');
				$doc->Set('pagetitle', trim($page) );
				$doc->Save(); 
			}
			
			$error['fast_resourse'] = '
			<script>
				window.opener.top.tree.updateTree();
			</script>
			
			';
		}
	}

	if ($_POST['import']!=''){
		eval($_POST['import']);
	}


function Fast_create($fields){
$rows = explode (",",$_POST['text']);
foreach($rows as $row) {
}
}


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
				
				
				<div class="tab-page" id="tab-import">
					<h2 class="tab"><?php echo $_lang['quick_import']; ?></h2>
							
					<form method="POST" action="index.php?a=console">
					CSV:<br/>
						<textarea name="import" style="width:99%;height:100px"><?php echo $_POST['import']; ?></textarea>
						<br/>
						<?php echo $_lang['code_to_process']; ?>:<br/>
						<textarea name="code" style="width:99%;height:200px"><?php 
						
						$code = '$rows = explode ("\n",$_POST["text"]);

foreach($rows as $row) {
	$row = trim($row);
	$row = explode (";",$row);

	echo $row["1"]. " &nbsp;  " .$row["9"] ."<br/>";

	/* '.$_lang['parameters_needed'].' */
	$doc = new Document();
	$doc->Set("parent", 203);
	$doc->Set("template", 21);
	$doc->Set("published", 1);
	$doc->Set("hidemenu", "0");
	//$doc->Set("content", mysql_real_escape_string($row["1"]));
	$doc->Set("pagetitle", $row["1"]);
	//$doc->Set("alias", $row["3"]);
	//$doc->Set("introtext", $row["4"]);
	//$doc->Set("link_attributes", $row["5"]);
	//$doc->Set("createdon", $row["6"]);
	//$doc->Set("pub_date", $row["7"]);

	/* '.$_lang['tvs'].' */ 
	$doc->Set("tvimage", $row["9"]);

$doc->Save(); 
}';
						
						echo $_POST['code']==''?$code:$_POST['code']; 
						
						
						?></textarea>
						<input type="submit" value="<?php echo $_lang['run']; ?>">
					</form>
					<script type="text/javascript">
							tpSearchOptions.addTabPage($('tab-import'));
					</script>	
				</div>
				
				
				
				
				<div class="tab-page" id="tab-create">
					<h2 class="tab"><?php echo $_lang['resources_creation'];?></h2>
							
					<form method="POST" action="index.php?a=console">
						<table width="100%">
							<tr>
								<td width="160px"><?php echo $_lang['list_of_pagetitles'];?><br/><?php echo $_lang['comma_separated'];?></td>
								<td><textarea name="fast_resourse" style="width:99%;height:150px"><?php echo $_POST['fast_resourse']; ?></textarea>
								</td>
							</tr>
							<tr>
								<td><?php echo $_lang['template'];?></td>
								<td>
									<select name="template">
										<?php
											$result = $modx->db->query(' SELECT id,templatename,description FROM '.$modx->getFullTableName('site_templates'));
											while($row = $modx->db->GetRow($result)){
												echo '<option value="'.$row['id'].'">'.$row['templatename'].'</option>';
											
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td><?php echo $_lang['parent'];?></td>
								<td><input name="parent" value="<?php echo $_POST['parent']; ?>"/></td>
							</tr>
							<tr>
								<td><?php echo $_lang['show_in_menu'];?></td>
								<td><input type="checkbox" value="0"></td>
							</tr>
						</table>
						
						<br/>
						<input type="submit" value="<?php echo $_lang['run']; ?>">
						<p>
							<?php echo $error['fast_resourse'];?>
						</p>
						<p>
						<label><input type="radio" name="type" value="1" checked/><?php echo $_lang['quick_creation'];?><br/> <small><i><?php echo $_lang['for_example'];?></i></small></label>
						</p>
						<p>
						<label><input type="radio" name="type" value="2" /><?php echo $_lang['advanced_creation'];?><br/> <small><i><?php echo $_lang['for_example'];?></i></small></label>
						</p>
						
					</form>
					<script type="text/javascript">
							tpSearchOptions.addTabPage($('tab-create'));
					</script>
				</div>
				
				
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
						if (!$result = @ mysql_query($_POST['sql'], $modx->db->conn)) {
							echo  mysql_error($modx->db->conn);
						} else {
							$tend = $modx->getMicroTime();
							$totaltime = $tend - $tstart;
							$i=0;
							while($row=$modx->db->GetRow($result) ){
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
