<?php
 /*
     Example10 : A 3D exploded pie graph
 */

 // Standard inclusions   
 include("pChart/pData.class");
 include("pChart/pChart.class");
 include_once "../../../../wp-config.php";
 global $wpdb;
 $table_name = $wpdb->prefix . "mzjajak";
 $options = get_option('widget_mzjajak');
 $bgclr=str_split($options['mzjajak_bgcolor'],2);
 $fclr=str_split($options['mzjajak_fcolor'],2);
 foreach($bgclr as $hex) {
	$ds[]=hexdec($hex);
 }
 foreach($fclr as $hex) {
	$fds[]=hexdec($hex);
 }
 // GetDataBase

 $rows = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE enabled=1");
		foreach ($rows as $rows) {
	 	$titlej = $rows->title;
		$pjgt = strlen($titlej);
		$ttl=str_split($titlej,27);
		$ttl=implode("\r\n",$ttl);

		
			if (!$rows->name1=="") { $strd[0] = $rows->name1; $hasil[0]=$rows->votes1; }
			if (!$rows->name2=="") { $strd[1] = $rows->name2; $hasil[1]=$rows->votes2; }
			if (!$rows->name3=="") { $strd[2] = $rows->name3; $hasil[2]=$rows->votes3; }
			if (!$rows->name4=="") { $strd[3] = $rows->name4; $hasil[3]=$rows->votes4; }
			if (!$rows->name5=="") { $strd[4] = $rows->name5; $hasil[4]=$rows->votes5; }
			if (!$rows->name6=="") { $strd[5] = $rows->name6; $hasil[5]=$rows->votes6; }
			$total = 0; 
			for($j=0;$j<count($strd);$j++) {
    			$total += $hasil[$j];
			}
			
 		}

 // Dataset definition 
 $DataSet = new pData;
if ($options['mzjajak_pie']=='true') {
 $DataSet->AddPoint($hasil,"Serie1");
 $DataSet->AddPoint($strd,"Serie2");
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie2");

 // Initialise the graph
 $Test = new pChart(220,310);
 $Test->drawFilledRoundedRectangle(1,1,219,309,5,$ds[0],$ds[1],$ds[2]);
 $Test->drawRoundedRectangle(1,1,219,309,5,$ds[0],$ds[1],$ds[2]);
// $Test->createColorGradientPalette(195,204,56,223,110,41,5);

 // Draw the pie chart
 $Test->setFontProperties("Fonts/tahoma.ttf",9);
 $Test->AntialiasQuality = 0;
 $Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),110,120,75,PIE_PERCENTAGE,FALSE,50,20,5);
 $Test->drawPieLegend(10,200,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
 
} else {

 foreach($hasil as $key => $value) {
 	$sr = $key+1;
 	$DataSet->AddPoint($value,"Serie$sr");
 }
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie();
 foreach($strd as $key => $value) {
	$sr = $key+1;
 	$DataSet->SetSerieName("$value","Serie$sr");
 }

 // Initialise the graph
 
 $Test = new pChart(220,310);
 $Test->setFontProperties("Fonts/tahoma.ttf",9);
 $Test->setGraphArea(30,75,210,210);
 $Test->drawFilledRoundedRectangle(1,1,219,309,5,$ds[0],$ds[1],$ds[2]);
 $Test->drawRoundedRectangle(1,1,219,309,5,$ds[0],$ds[1],$ds[2]);
 $Test->drawGraphArea(255,255,255,TRUE);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,$fds[0],$fds[1],$fds[2],TRUE,0,2,TRUE);
 $Test->drawGrid(4,TRUE,230,230,230,50);

 // Draw the 0 line
 //$Test->setFontProperties("Fonts/tahoma.ttf",10);
 //$Test->drawTreshold(0,143,55,72,FALSE,TRUE);

 // Draw the bar graph
 $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE,80);
 $Test->drawFilledRectangle(30,211,210,230,$ds[0],$ds[1],$ds[2]);

 // Finish the graph
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->drawLegend(20,225,$DataSet->GetDataDescription(),250,250,250);
 
}
 // Write the title

 $Test->setFontProperties("Fonts/MankSans.ttf",11);
 $Test->drawTextBox(10,5,210,110,"$ttl \r\n($total voted)",0,$fds[0],$fds[1],$fds[2],ALIGN_TOP_LEFT,TRUE);
 $Test->Render(header('Content-type: image/png'));
?>