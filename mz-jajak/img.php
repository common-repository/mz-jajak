<?php
Header("Content-Type:image/png");
include_once "../../../../wp-config.php";
$conn=@mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
$table_name = $wpdb->prefix . "mzjajak";
$qry = @mysql_query("SELECT * FROM ".$table_name,$conn);
$row=mysql_fetch_array($qry);
mysql_select_db(DB_NAME,$conn);
$options = get_option('widget_mzjajak');
$bgclr=str_split($options['mzjajak_bgcolor'],2);
$fclr=str_split($options['mzjajak_fcolor'],2);
foreach($bgclr as $hex) {
	$ds[]=hexdec($hex);
}
foreach($fclr as $hex) {
	$fds[]=hexdec($hex);
}
$i=0;
if(!$row['name1']=="") { $str[0] = $row['name1']; $data[0] = $row['votes1']; }
if(!$row['name2']=="") { $str[1] = $row['name2']; $data[1] = $row['votes2']; }
if(!$row['name3']=="") { $str[2] = $row['name3']; $data[2] = $row['votes3']; }
if(!$row['name4']=="") { $str[3] = $row['name4']; $data[3] = $row['votes4']; }
if(!$row['name5']=="") { $str[4] = $row['name5']; $data[4] = $row['votes5']; }
if(!$row['name6']=="") { $str[5] = $row['name6']; $data[5] = $row['votes6']; }
$total = 0;$d = array();$p = array();$kor_x = array();$kor_y = array();$t_x = array();$t_y = array();$d[0] = 0;
for($j=0;$j<count($str);$j++) {
    $total += $data[$j];
}

for($i=1;$i<=count($str);$i++) {
	$p[$i] = round(($data[$i-1]/$total) * 100);
    $d[$i] = ($data[$i-1]/$total) * 360;
    $d[$i] += $d[$i-1];
}

$img = ImageCreate(150,150);
$warna[0] = ImageColorAllocate($img,231,11,0);
$warna[1] = ImageColorAllocate($img,198,253,4);
$warna[2] = ImageColorAllocate($img,63,63,234);
$warna[3] = ImageColorAllocate($img,14,231,55);
$warna[4] = ImageColorAllocate($img,246,203,9);
$warna[5] = ImageColorAllocate($img,242,95,192);
$hitam = ImageColorAllocate($img,$fds[0],$fds[1],$fds[2]);
$putih = ImageColorAllocate($img,$ds[0],$ds[1],$ds[2]);
ImageFill($img,0,0,$putih);

for($k=1;$k<=count($str);$k++) {

ImageArc($img,75,75,140,140,$d[$k-1],$d[$k],$hitam);
  
    $kor_x[$k] = round(75+(70*cos(deg2rad($d[$k-1]))));
    $kor_y[$k] = round(75+(70*sin(deg2rad($d[$k-1]))));
   
    $t = round(($d[$k-1]+$d[$k])/2);
    $t_x[$k] = round(75+(50*cos(deg2rad($t))));
    $t_y[$k] = round(75+(50*sin(deg2rad($t))));
    ImageLine($img,75,75,$kor_x[$k],$kor_y[$k],$hitam);
}


for($k=1;$k<=count($str);$k++) {
    ImageFillToBorder($img,$t_x[$k],$t_y[$k],$hitam,$warna[$k-1]);
}

for($s=1;$s<=count($str);$s++) {
    ImageString($img,3,$t_x[$s]-5,$t_y[$s],$p[$s].'%',$hitam);
}
/*
for($u=1;$u<=count($str);$u++) {
    ImageFilledRectangle($img,5,140+$u*10,10,145+$u*10,$warna[$u-1]);
    ImageString($img,2,15,135+$u*10,$str[$u-1],$hitam);
	ImageString($img,2,130,135+$u*10,$data[$u-1],$hitam);
}
*/
ImagePNG($img);
?>