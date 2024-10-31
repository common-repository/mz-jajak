<?php
/*
Plugin Name: Mz-jajak
Version: 2.1
Plugin URI: http://www.tirta-fajri.net
Description: Mz-jajak merupakan plugin untuk menampilkan Jajak pendapat di widget wordpress 
Author: Muhammad Tarmizi
Author URI: http://www.tirta-fajri.net

*/
/*  Copyright 2010 Muhammad Tarmizi

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
// Hook untuk menambah admin menu
add_action('admin_menu', 'mz_jajak_add_page');
register_activation_hook(__FILE__,'jajak_install');

function mz_jajak_add_page() {
    add_options_page('MZ Jajak', 'MZ Jajak', 'administrator', 'mz_jajak', 'mz_jajak_options_page');
}

function jajak_install () {
   global $wpdb;
   global $jajak_db_version;

   $table_name = $wpdb->prefix . "mzjajak";
   $sql = "CREATE TABLE " . $table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  title text NOT NULL,
	  name1 text NOT NULL,
	  name2 text NOT NULL,
	  name3 text NOT NULL,
	  name4 text NOT NULL,
	  name5 text NOT NULL,
	  name6 text NOT NULL,
	  votes1 int NOT NULL,
	  votes2 int NOT NULL,
	  votes3 int NOT NULL,
	  votes4 int NOT NULL,
	  votes5 int NOT NULL,
	  votes6 int NOT NULL,
	  enabled int NOT NULL,
	  UNIQUE KEY id (id)
	);";

	$wpdb->query($sql);
	$data = array( 'title' => 'Jajak Pendapat', 'mzjajak_pie' => 'false');
	    if (!get_option('widget_mzjajak')){
	      add_option('widget_mzjajak', $data);
	    } else {
	      update_option('widget_mzjajak', $data);
	    }		
}

function control() {
			$options = $newoptions = get_option('widget_mzjajak');
			if($_POST["mzjajak_submit"]) {
				$newoptions['title'] = strip_tags(stripslashes($_POST["mzjajak_title"]));
				if(empty($newoptions['title'])) $newoptions['title'] = 'Jajak Pendapat';
				$newoptions['mzjajak_pie'] = ($_POST["mzjajak_pie"] == 'true')? 'true' : 'false';
				$newoptions['mzjajak_bgcolor'] = strip_tags(stripslashes($_POST["mzjajak_bgcolor"]));
				if(empty($newoptions['mzjajak_bgcolor'])) $newoptions['mzjajak_bgcolor'] = 'FFFFFF';
				$newoptions['mzjajak_fcolor'] = strip_tags(stripslashes($_POST["mzjajak_fcolor"]));
				if(empty($newoptions['mzjajak_fcolor'])) $newoptions['mzjajak_fcolor'] = '000000';

			}
			if ($options != $newoptions) {
				$options = $newoptions;
				update_option('widget_mzjajak', $options);
			}
			
			$title = htmlspecialchars($options['title'], ENT_QUOTES);
			$mzjajak_pie = ($options['mzjajak_pie'] == 'true')? 'checked="checked"' : '';
			$mzjajak_bgcolor = $options['mzjajak_bgcolor'];
			$mzjajak_fcolor = $options['mzjajak_fcolor'];
			
?>
			<p><label for="mzjajak_title"><?php _e('Title:'); ?><br/><input	style="width: 220px;" id="mzjajak_title" name="mzjajak_title"	type="text" value="<?php echo $title; ?>" /></label></p>

			<p><label for="mzjajak_pie"><?php _e('Use Pie Chart:'); ?><br/><input type="checkbox" id="mzjajak_pie" name="mzjajak_pie" type="text" value="true" <?php echo $mzjajak_pie;?> /> </label></p>
			<p><label for="mzjajak_bgcolor"><?php _e('Pie Chart Background Color:'); ?><br/><input	style="width: 100px;" id="mzjajak_bgcolor" name="mzjajak_bgcolor" maxlength="6" type="text" value="<?php echo $mzjajak_bgcolor; ?>" /></label></p>
			<p><label for="mzjajak_fcolor"><?php _e('Pie Chart Font Color:'); ?><br/><input	style="width: 100px;" id="mzjajak_fcolor" name="mzjajak_fcolor" maxlength="6" type="text" value="<?php echo $mzjajak_fcolor; ?>" /></label></p>
			<input type="hidden" id="mzjajak_submit" name="mzjajak_submit" value="1" />
<?php
}
function mz_jajak_options_page() {
	global $wpdb;
	$table_name = $wpdb->prefix . "mzjajak";
	
	
	echo '<div class="wrap">';
    echo "<h2>" . __( 'MZ Jajak Plugin Options', 'mt_trans_domain' ) . "</h2>";
	?>
	<script type="text/javascript"><!--
	google_ad_client = "pub-0599577055043252";
	/* 728x90, created 4/13/11 */
	google_ad_slot = "1677118631";
	google_ad_width = 728;
	google_ad_height = 90;
	//-->
	</script>
	<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
	<?php
	if ($_GET['edit']) {
		$rows = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE id=" . $_GET['edit']);
		foreach ($rows as $rows) {
			$judul = $rows->title;
			$ops1 = $rows->name1;
			$ops2 = $rows->name2;
			$ops3 = $rows->name3;
			$ops4 = $rows->name4;
			$ops5 = $rows->name5;
			$ops6 = $rows->name6;
			$idedit=$rows->id;
		}
	}
	if ($_GET['delete']) {
		$results = $wpdb->query("DELETE FROM " . $table_name . " WHERE id=" . $_GET['delete']);
		echo '<div class="updated"><p><strong>'.'Poll Deleted!'.'</strong></p></div>';
	}	
	if ($_POST['setvar']=="Y") {
		$query = $wpdb->query("UPDATE " . $table_name . " SET enabled=0 WHERE enabled=1");
		$query = $wpdb->query("UPDATE " . $table_name . " SET enabled=1 WHERE id=".$_POST['set']);
	}			
	if ( $_POST['cor']) {
			if($_POST[ideditpro]!="") {
				$wpdb->update($table_name, array( 'title' => $_POST["pollquestion"], 'name1' => $_POST["answer1"], 'name2' => $_POST["answer2"], 'name3' => $_POST["answer3"], 'name4' => $_POST["answer4"], 'name5' => $_POST["answer5"], 'name6' => $_POST["answer6"],  ), array( 'id' => $_POST['ideditpro'] ));
			} else {
			$pollquestion=$_POST['pollquestion'];
			$answer1=$_POST['answer1'];
			$answer2=$_POST['answer2'];
			$answer3=$_POST['answer3'];
			$answer4=$_POST['answer4'];
			$answer5=$_POST['answer5'];
			$answer6=$_POST['answer6'];
			$answer7=$_POST['answer7'];
			$answer8=$_POST['answer8'];
		    $sql = 'INSERT INTO ' . $table_name . ' SET ';
			$sql .= 'title = "'. $pollquestion .'", ';
			$sql .= 'name1 = "'. $answer1 .'", ';
			$sql .= 'name2 = "'. $answer2 .'", ';
			$sql .= 'name3 = "'. $answer3 .'", ';
			$sql .= 'name4 = "'. $answer4 .'", ';
			$sql .= 'name5 = "'. $answer5 .'", ';
			$sql .= 'name6 = "'. $answer6 .'"';
			echo "$pollquestion";
			$wpdb->query($sql);
		}
		$idedit="";
		$judul="";
		$ops1="";
		$ops2="";
		$ops3="";
		$ops4="";
		$ops5="";
		$ops6="";
		echo '<div class="updated"><p><strong>'. 'Jajak Saved ' .'</strong></p></div>';
	}


	$count = $wpdb->get_results("SELECT count(*) as jlh FROM " . $table_name);
	foreach ($count as $count) {
		$banyak = $count->jlh;
	}
	if ($banyak==1) {
		$query = $wpdb->query("UPDATE " . $table_name . " SET enabled=1");
	}
	if ($banyak>0) {
		echo "<h3>List Jajak</h3>";
		echo "<form name=formlist method=post action=''>";
		$rows = $wpdb->get_results("SELECT * FROM " . $table_name);
		foreach ($rows as $rows) {
		
		if ($rows->enabled==1) {
			$terpilih="checked";
		} else {
			$terpilih="";
		}
			echo "<input type='radio' name='set' value='".$rows->id."' ".$terpilih." /> " . $rows->title . " - <a href='?page=mz_jajak&view=". $rows->id ."'>View</a> - <a href='?page=mz_jajak&edit=". $rows->id ."'>Edit</a> - <a href='?page=mz_jajak&delete=". $rows->id ."'>Delete</a>";
			echo "<br />";
		}
		echo '<input type="hidden" name="setvar" value="Y" /><br /><input name=pilih type="submit" value="Set to Active" /></form>';
	}	
	
	if ($_GET['view']) {

		$rows = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE id=" . $_GET['view']);
		foreach ($rows as $rows) {
			$total = $rows->votes1 + $rows->votes2 + $rows->votes3 + $rows->votes4 + $rows->votes5 + $rows->votes6;
			if ($total<>0) {
				echo '<h3>Poll ID '.$_GET["view"].' - '.$rows->title.'</h3>';
				echo '<table width=50% border=0><tr>';
				if ($rows->name1!="") { echo "<tr><td>". $rows->name1 ." </td><td> ". $rows->votes1 . "</td></tr>"; }
				if ($rows->name2!="") { echo "<tr><td>". $rows->name2 ." </td><td> ". $rows->votes2 . "</td></tr>"; }
				if ($rows->name3!="") { echo "<tr><td>". $rows->name3 ." </td><td> ". $rows->votes3 . "</td></tr>"; }
				if ($rows->name4!="") { echo "<tr><td>". $rows->name4 ." </td><td> ". $rows->votes4 . "</td></tr>"; }
				if ($rows->name5!="") { echo "<tr><td>". $rows->name5 ." </td><td> ". $rows->votes5 . "</td></tr>"; }
				if ($rows->name6!="") { echo "<tr><td>". $rows->name6 ." </td><td> ". $rows->votes6 . "</td></tr>"; }
				echo "<tr><td><b>Total </b></td><td> <b>". $total."</b></td></tr></table> ";
				$linkbuat="<a href='?page=mz_jajak&baru='buatbaru'>Buat Jajak</a>";
			}
		}
	}
	else {	
	?>

	<h3>Buat Jajak</h3>
	<form name="form1" method="post" action="">
  	
 	<table width="50%" border="0">
    <tr> 
      	<td width="31%"> Pertanyaan Jajak</td>
      	<td width="69%"> <input name="pollquestion" type="text" size="50" value="<?php echo"$judul";?>">
    	</td>
    </tr>
    <tr> 
      	<td>Pilihan 1</td>
      	<td><input name="answer1" type="text" size="25" value="<?php echo"$ops1";?>"></td>
    </tr>
    <tr> 
      	<td>Pilihan 2</td>
      <td><input name="answer2" type="text" size="25" value="<?php echo"$ops2";?>"></td>
    </tr>
    <tr> 
      <td>Pilihan 3</td>
      <td><input name="answer3" type="text" size="25" value="<?php echo"$ops3";?>"></td>
    </tr>
    <tr> 
      <td>Pilihan 4</td>
      <td><input name="answer4" type="text" size="25" value="<?php echo"$ops4";?>"></td>
    </tr>
    <tr> 
      <td>Pilihan 5</td>
      <td><input name="answer5" type="text" size="25" value="<?php echo"$ops5";?>"></td>
    </tr>
    <tr> 
      <td>Pilihan 6</td>
      <td><input name="answer6" type="text" size="25" value="<?php echo"$ops6";?>"></td>
    </tr>
    <tr>
      <td>
	  <?
  		if($idedit!="") {
			echo"<input type=hidden name=ideditpro value=$idedit>";
			$vlu="Edit Jajak";
			$linkbuat = "<a href='?page=mz_jajak&baru='buatbaru'>Buat Jajak</a>";
		} else {
			echo"<input type=hidden name=ideditpro value=''>";
			$vlu="Create Jajak";
			$linkbuat ="";
		}
  ?>
	  </td>
      <td><input type="submit" name="cor" value="<?php echo"$vlu";?>"></td>
    </tr>
  </table>
	</form>
<?php
}
echo "<br> $linkbuat";
}


function jajak_set_cookie() {
	$answer=$_POST['answer'];
	if ($answer!="") {
		setcookie("mzjajak", "submitted", time()+86400); 
	} 
}

function init_jajak_widget() {
	register_sidebar_widget('MZ Jajak', 'show_jajak');
	register_widget_control('MZ Jajak', 'control');
}

function show_jajak($args) {

	extract($args);
	global $wpdb;
	$table_name = $wpdb->prefix . "mzjajak";
	$options = get_option('widget_mzjajak');

	if ($_POST['formvote']=="Y" && !$_POST['answer']=="") {
		$answer=$_POST['answer'];
		$id=$_POST['id'];
		
		switch ($answer) {
			case 1:
				$answert="votes1";
			break;
			case 2:
				$answert="votes2";
			break;
			case 3:
				$answert="votes3";
			break;
			case 4:
				$answert="votes4";
			break;
			case 5:
				$answert="votes5";
			break;
			case 6:
				$answert="votes6";
			break;
		}
		
		if ($_COOKIE["mzjajak"]!="submitted") {
			$query = $wpdb->query("UPDATE " . $table_name . " SET ".$answert."=".$answert."+1 WHERE id=".$id);
		}
		$rows = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE id=".$id);
		foreach ($rows as $rows) {
			if (!$rows->name1=="") { $strd[0] = $rows->name1; $data[0]=$rows->votes1; }
			if (!$rows->name2=="") { $strd[1] = $rows->name2; $data[1]=$rows->votes2; }
			if (!$rows->name3=="") { $strd[2] = $rows->name3; $data[2]=$rows->votes3; }
			if (!$rows->name4=="") { $strd[3] = $rows->name4; $data[3]=$rows->votes4; }
			if (!$rows->name5=="") { $strd[4] = $rows->name5; $data[4]=$rows->votes5; }
			if (!$rows->name6=="") { $strd[5] = $rows->name6; $data[5]=$rows->votes6; }
			$total = 0; $d = array(); $p = array(); $w = array(); $gp=count($strd);
			for($j=0;$j<count($strd);$j++) {
    			$total += $data[$j];
			}
	
			if ($total > 0) {
				for($i=1;$i<=count($strd);$i++) {
					$p[$i] = round(($data[$i-1]/$total) * 100);
					$wd[$i] = round(($data[$i-1]/$total) * 150);
    				$d[$i] = ($data[$i-1]/$total) * 360;
    				$d[$i] += $d[$i-1];
				}
			}
			$mwd=max($wd);
			$hg=round(100/$gp)."%";
			for($i=1;$i<=count($strd);$i++) {
				$w[$i]= round(($wd[$i]/$mwd)*150);
			}
			$options = $newoptions = get_option('widget_mzjajak');
			echo $before_widget;
			echo $before_title.$options['title'].$after_title ;
			echo "<h3><center>".$rows->title."</center></h3>";
			echo "<table width=98% border=0 cellspacing=0 cellpadding=0><tr>";
			if ($options['mzjajak_pie']=='true') {
				$pi = "p";
				echo "<td valign=bottom align=center colspan=2><img src=".get_bloginfo('url')."/wp-content/plugins/mz-jajak/mz-jajak/img.php width=98% height=150 alt='Result of Mz-jajak'> </td>";  
			} else {
				foreach ($strd as $key => $value) {
					$gbr=$key+1;
					echo "<td valign=bottom align=center width=".$hg.">".$p[$key+1] . "%<br><img src=".get_bloginfo('url')."/wp-content/plugins/mz-jajak/mz-jajak/".$gbr.".jpg height=".$w[$key+1]." width=100% alt=".stripslashes($value) . "> </td>"; 
				}
			}
				echo "</tr></table><table width=98% border=0 cellspacing=1 cellpadding=1>";
				foreach ($strd as $key => $value) {
					$gbr=$key+1;
	 				echo "<tr><td width=16><img src=".get_bloginfo('url')."/wp-content/plugins/mz-jajak/mz-jajak/".$gbr.$pi.".jpg height=15 width=15></td><td>".stripslashes($value) . " </td><td align=right> ".$data[$key]."</td></tr>"; 
				}
			
			echo "<tr><td colspan=2><i>Total Voting </i></td><td align=right><b>".$total."</b></td></tr>";
			echo '<tr><td style="font-size:x-small" align=right colspan=3>Created by <a href="http://www.tirta-fajri.net">Mizi</a></td><tr></table>' . $after_widget;
		}
		

	} else if (($_COOKIE['mzjajak']=="submitted") or ($_POST['formvote']=="Y")) {
		$rows = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE enabled=1");
		foreach ($rows as $rows) {
			if (!$rows->name1=="") { $strd[0] = $rows->name1; $data[0]=$rows->votes1; }
			if (!$rows->name2=="") { $strd[1] = $rows->name2; $data[1]=$rows->votes2; }
			if (!$rows->name3=="") { $strd[2] = $rows->name3; $data[2]=$rows->votes3; }
			if (!$rows->name4=="") { $strd[3] = $rows->name4; $data[3]=$rows->votes4; }
			if (!$rows->name5=="") { $strd[4] = $rows->name5; $data[4]=$rows->votes5; }
			if (!$rows->name6=="") { $strd[5] = $rows->name6; $data[5]=$rows->votes6; }
			$total = 0; $d = array(); $p = array(); $w = array(); $gp=count($strd);
			for($j=0;$j<count($strd);$j++) {
    			$total += $data[$j];
			}
	
			if ($total > 0) {
				for($i=1;$i<=count($strd);$i++) {
					$p[$i] = round(($data[$i-1]/$total) * 100);
					$wd[$i] = round(($data[$i-1]/$total) * 150);
    				$d[$i] = ($data[$i-1]/$total) * 360;
    				$d[$i] += $d[$i-1];
				}
			}
			$mwd=max($wd);
			$hg=round(100/$gp)."%";
			for($i=1;$i<=count($strd);$i++) {
				$w[$i]= round(($wd[$i]/$mwd)*150);
			}
			$options = $newoptions = get_option('widget_mzjajak');
			echo $before_widget;
			echo $before_title.$options['title'].$after_title ;
			echo "<h3><center>".$rows->title."</center></h3>";
			echo "<table width=98% border=0 cellspacing=0 cellpadding=0><tr>";
			if ($options['mzjajak_pie']=='true') {
				$pi = "p";
				echo "<td valign=bottom align=center colspan=2><img src=".get_bloginfo('url')."/wp-content/plugins/mz-jajak/mz-jajak/img.php width=98% height=150 alt='Result of Mz-jajak'> </td>";  
			} else {
				foreach ($strd as $key => $value) {
					$gbr=$key+1;
					echo "<td valign=bottom align=center width=".$hg.">".$p[$key+1] . "%<br><img src=".get_bloginfo('url')."/wp-content/plugins/mz-jajak/mz-jajak/".$gbr.".jpg height=".$w[$key+1]." width=100% alt=".stripslashes($value) . "> </td>"; 
				}
			}
				echo "</tr></table><table width=98% border=0 cellspacing=1 cellpadding=1>";
				foreach ($strd as $key => $value) {
					$gbr=$key+1;
	 				echo "<tr><td width=16><img src=".get_bloginfo('url')."/wp-content/plugins/mz-jajak/mz-jajak/".$gbr.$pi.".jpg height=15 width=15></td><td>".stripslashes($value) . " </td><td align=right> ".$data[$key]."</td></tr>"; 
				}
			
			echo "<tr><td colspan=2><i>Total Voting </i></td><td align=right><b>".$total."</b></td></tr>";
			echo '<tr><td style="font-size:x-small" align=right colspan=3>Created by <a href="http://www.tirta-fajri.net">Mizi</a></td><tr></table>' . $after_widget;
		}

	} else {
   		$rows = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE enabled=1");
   
		foreach ($rows as $rows) {

			echo $before_widget;
			echo $before_title.$options['title'].$after_title ;
			echo '<form action="" method="post">';
			echo "<h3><center>".$rows->title."</center></h3>";
			echo "<table align=center width=100%>";
			if (!$rows->name1=="") { echo '<tr><td width="4">' . '<input type="radio" name="answer" value="1" /> '.'</td><td>'.stripslashes($rows->name1)."</td></tr />"; }
			if (!$rows->name2=="") { echo '<tr><td width="4">' . '<input type="radio" name="answer" value="2" /> '.'</td><td>'.stripslashes($rows->name2)."<br />"; }
			if (!$rows->name3=="") { echo '<tr><td width="4">'. '<input type="radio" name="answer" value="3" /> '.'</td><td>'.stripslashes($rows->name3)."<br />"; }
			if (!$rows->name4=="") { echo '<tr><td width="4">' . '<input type="radio" name="answer" value="4" /> '.'</td><td>'.stripslashes($rows->name4)."<br />"; }
			if (!$rows->name5=="") { echo '<tr><td width="4">' . '<input type="radio" name="answer" value="5" /> '.'</td><td>'.stripslashes($rows->name5)."<br />"; }
			if (!$rows->name6=="") { echo '<tr><td width="4">' . '<input type="radio" name="answer" value="6" /> '.'</td><td>'.stripslashes($rows->name6)."<br />"; }
			echo "</table>";
			echo '<input type="hidden" name="formvote" value="Y" /><input type="hidden" name="id" value="'.$rows->id.'" /><input type="image" value="Submit" src="'.get_bloginfo('url').'/wp-content/plugins/mz-jajak/mz-jajak/vote.png" alt="Vote" /></form>';
			echo '<p style="font-size:x-small" align="right">Created by <a href="http://www.tirta-fajri.net">Mizi</a></p>' . $after_widget;
		}
	}
}

add_action("plugins_loaded", "init_jajak_widget");
add_action("get_header", "jajak_set_cookie");
?>