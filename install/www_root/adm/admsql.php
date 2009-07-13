<?php
/**
* copyright            : (C) 2001-2009 Advanced Internet Designs Inc.
* email                : forum@prohost.org
* $Id: admsql.php,v 1.6 2009/07/11 10:54:37 frank Exp $
*
* This program is free software; you can redistribute it and/or modify it
* under the terms of the GNU General Public License as published by the
* Free Software Foundation; version 2 of the License.
**/

	require('./GLOBALS.php');
	fud_use('db.inc');	
	fud_use('adm.inc', true);

	require($WWW_ROOT_DISK . 'adm/admpanel.php');
?>
<h2>SQL Manager</h2>
<div class="alert">
NOTE: this feature IS DANGEROUS and you could destroy all your data. Please only use it to run SELECT and DESCRIBE statements. Anything else may result in data loss.
</div>
<form name="admsql" method="post" action="admsql.php">
<?php echo _hs; ?>
<table class="datatable">
<tr class="field"><td>
	Enter SQL statement(s):<br />
</td></tr>
<tr class="field"><td>
	<div style="float:right; font-size:xx-small;">
	Database: <?php echo $GLOBALS['DBHOST_USER'].'@'.$GLOBALS['DBHOST_DBNAME'] ?> :: 
	<select onchange="if(this.selectedIndex!=0) document.admsql.sql.value+=this.options[this.selectedIndex].value;">
	<option>Insert table:</option>
	<?php foreach(get_fud_table_list() as $tbl) {
		echo '<option value="'.$tbl.'">'.$tbl.'</option>';
	} ?>
	</select>
	</div>
	<textarea id="sql" name="sql" rows="7" cols="72" style="width:99%;"><?php if (isset($_POST['sql'])) { print $_POST['sql']; } else { print 'SELECT * FROM '; } ?></textarea>
</td></tr>
<tr><td>
	<input type="submit" class="submit" value="Run It" />
</td></tr>
 </table>
</form>
<script type="text/javascript">
/* <![CDATA[ */
document.forms['admsql'].sql.focus();
/* ]]> */
</script>

<?php
if (isset($_POST['sql']) && $_POST['sql'] != '') {
	$sqlfile = str_replace("\r","",$_POST['sql']);
	$sqlfile = str_replace('{SQL_TABLE_PREFIX}', $GLOBALS['DBHOST_TBL_PREFIX'], $sqlfile);
	$sqlfile = explode(";\n", $sqlfile);

	foreach ($sqlfile as $sql) {
		if (preg_match("/[a-zA-Z]/", $sql) and !preg_match("/^(#|--)/", $sql)) {
			if (preg_match('/^\s*use\s+\w+\s*;?$/i', $sql)) {
				echo '<div class="tutor">For security reasons you may not switch to another database</div>';
				break;
			}

			$q = q($sql);

			echo '<h2>SQL Results</h2>';
			echo '<table class="resulttable">';

			$i = 1;
			while ($result = db_fetch_array($q)) {
				echo '<tr class="resulttopic">';
				if ($i == 1) {
					foreach ($result as $key => $value) {
						if (!is_numeric($key)) {
							echo '<th>'.$key.'</th>';
						}
					}
				}
				echo '</tr>';
			
				echo '<tr class="field">';
				foreach ($result as $key => $value) {
					if (!is_numeric($key)) {
						echo '<td>'.$value.'</td>';
					}
				}
				echo '</tr>';

				$i++;
			}

			echo '</table>';
		}

		$num_rows = db_count($q);
		if (!$num_rows) $num_rows = 0;
		echo '<br /><i>'. $num_rows .' row(s) returned.</i>';
	}
}

require($WWW_ROOT_DISK . 'adm/admclose.html');
?>