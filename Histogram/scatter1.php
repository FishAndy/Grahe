<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>文獻年份散佈圖</title>
</head>
<form method="post">
	<input type="text" name="search" />
    <input type="submit" value="search" />
</form>
<body>
<div id="container"></div>
<script src="http://code.highcharts.com/highcharts.js"></script>
</body>
</html>
<?php

if(isset($_POST[search]) && $_POST[search] != NULL)
{
	include("../Class/db.class.php");$db = new DB();
	$db = new DB();
	$x = array();//年
	$Objectcase='Disease';
	$Search_Disease = $db->SQL_select("SELECT `DiseaseID`,`DiseaseName` FROM `disease` 
							  			WHERE `DiseaseID`='$_POST[search]'
							  			OR `DiseaseName`='$_POST[search]'");
	if(isset($Search_Disease[0]->DiseaseID) && $Search_Disease[0]->DiseaseID != NULL)
	{
		$Ch_Diyear = $db->SQL_select("SELECT `year`,COUNT(`year`)AS`number`
										 FROM `chemical_disease`,`disease`,`pubmed`
											 WHERE `disease`.`DiseaseID`='{$Search_Disease[0]->DiseaseID}'
											  AND `disease`.`DiseaseID`=`chemical_disease`.`DiseaseID`AND `chemical_disease`.`PubMedIDs`=`pubmed`.`PubMedIDs`AND `year`>='1990' AND `year`<='2018'
											  GROUP BY `year` ORDER BY `year`");
		$Ge_Diyear = $db->SQL_select("SELECT `year`,COUNT(`year`)AS`number`
										  FROM `gene_disease`,`disease` ,`pubmed`
									         WHERE `disease`.`DiseaseID`='{$Search_Disease[0]->DiseaseID}'
										     AND `disease`.`DiseaseID`=`gene_disease`.`DiseaseID`AND `gene_disease`.`PubMedIDs`=`pubmed`.`PubMedIDs` AND `year`>='1990'AND `year`<='2018'	
										 GROUP BY `year` ORDER BY `year`");
	
    for($i = 0 ; $i <29 ; $i++)
	{
         $x[$i]=array(
		 'y'=>'',
         'year'=>1990+$i
          );                 
	}		

			//print_r($x);			 
	for($i = 0 ; $i < 29 ; $i++)
	{
		for($j = 0 ; $j <count($Ch_Diyear) ; $j++)
		{
			 echo $x[$i]->year;
		  if($x[$i][year]==$Ch_Diyear[$j]->year)
		  {
			  $a=$x[$i][y]+$Ch_Diyear[$j]->number;
			  $x[$i]=array(
			   'y'=>$a,
			   'year'=>$x[$i][year]
			 ); 
			 break;
		  }
		
			
	    }
		              		
	} 
	for($i = 0 ; $i < 29 ; $i++)
	{
		for($j = 0 ; $j < count($Ge_Diyear) ; $j++)
		{
		  if($x[$i][year]==$Ge_Diyear[$j]->year)
		  {
			  $a=$x[$i][y]+$Ge_Diyear[$j]->number;
			   $x[$i]=array(
			   'y'=>$a,
			   'year'=>$x[$i][year]
			 ); 
			 break;
		  }
		  			
	    }
		              		
	}
	
	//print_r($x);									 
	}
}
?>
<script>
var chart = new Highcharts.Chart({
    chart: {
        renderTo: 'container'
    }, 
   title: {
        text: '<?php echo $Search_Disease[0]->DiseaseName ?>年份散布圖',
        align: 'center',
        x: 70
    },
    xAxis: {
		allowDecimals: false,
	
      tickInterval: 5,
      categories: ['1990', '1991', '1992', '1993', '1994', '1995', '1996', '1997', '1998', '1999', '2000', '2001', '2002', '2003', '2004', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018']
    
    },
    yAxis: 
	{
		allowDecimals: false,
		lineWidth: 1,
        tickWidth: 1,
		title:
		{
			text:"Number",
			align:"high",
			offset: 0,
			rotation: 0,
            y: -10
		}
    },
	  tooltip: {
        shared: true,
        useHTML: true,
        headerFormat: '<small>year:{point.key}</small><table>',
        pointFormat: '<tr><td style="text-align:{series.color}">{series.name} Number: </td>' +
            '<td style="text-align: right"><b>{point.y} </b></td></tr>',
        footerFormat: '</table>',
    },
   legend: 
	{
        enabled: false
    },
    credits: 
	{
        enabled: false
    },
    plotOptions: {
        series: {
            cursor: 'pointer',
            point: {
                events: {
                    click: function() {
     location.href = '../Table/table.php?Graph=Histogram&Case=<?php echo $Objectcase?>&Object=<?php echo $Search_Disease[0]->DiseaseID?>&Year='+this.category;
                 //  alert ('Category: '+ this.category +', value: '+ this.y);
                    }
                }
            }
        }
    },
    
    series: [{
		name: 'article',
        type: "scatter",
       	data:<?php echo json_encode($x); ?>       
    }]
});
</script>