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
	include("../Class/db.class.php");
	$db = new DB();
	$x = array();//年
	
	$search_Disease = $db->SQL_select("SELECT `DiseaseID`,`DiseaseName` FROM `disease` 
							  			WHERE `DiseaseID`='$_POST[search]' OR `DiseaseName`='$_POST[search]'");
	$search_Chemical = $db->SQL_select("SELECT `ChemicalID`,`ChemicalName` FROM `chemical`
							  			WHERE `ChemicalID`='$_POST[search]'OR `ChemicalName`='$_POST[search]'");
	$search_Gene = $db->SQL_select("SELECT `GeneID`,`GeneSymbol` FROM `gene` 
							  			WHERE `GeneID`='$_POST[search]'OR `GeneSymbol`='$_POST[search]'");
	if(isset($search_Disease[0]->DiseaseID) && $search_Disease[0]->DiseaseID != NULL)
	{
		$Ch_Diyear = $db->SQL_select("SELECT `year`,COUNT(`year`)AS`number`
										 FROM `chemical_disease`,`disease`,`pubmed`
											 WHERE `disease`.`DiseaseID`='{$search_Disease[0]->DiseaseID}'
											  AND `disease`.`DiseaseID`=`chemical_disease`.`DiseaseID`AND `chemical_disease`.`PubMedIDs`=`pubmed`.`PubMedIDs`AND `year`>='1990' AND `year`<='2018'
											  GROUP BY `year` ORDER BY `year`");
		$Ge_Diyear = $db->SQL_select("SELECT `year`,COUNT(`year`)AS`number`
										  FROM `gene_disease`,`disease` ,`pubmed`
									         WHERE `disease`.`DiseaseID`='{$search_Disease[0]->DiseaseID}'
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
			   'year'=>$Ch_Diyear[$j]->year
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
			   'year'=>$Ge_Diyear[$j]->year
			 ); 
			 break;
		  }
		  			
	    }
		              		
	}									 
	}
	else if(isset($search_Chemical[0]->ChemicalID) && $search_Chemical[0]->ChemicalID != NULL)
	{
		$Ch_Diyear = $db->SQL_select("SELECT `year`,COUNT(`year`)AS`number`
										 FROM `chemical_disease`,`chemical`,`pubmed`
											 WHERE `chemical`.`ChemicalID`='{$search_Chemical[0]->ChemicalID}'
											  AND `chemical`.`ChemicalID`=`chemical_disease`.`ChemicalID`AND `chemical_disease`.`PubMedIDs`=`pubmed`.`PubMedIDs`AND `year`>='1990' AND `year`<='2018'
											  GROUP BY `year` ORDER BY `year`");
		$Ch_Geyear = $db->SQL_select("SELECT `year`,COUNT(`year`)AS`number`
										  FROM `chemical_gene`,`chemical` ,`pubmed`
									         WHERE `chemical`.`ChemicalID`='{$search_Chemical[0]->ChemicalID}'
										     AND `chemical`.`ChemicalID`=`chemical_gene`.`ChemicalID`AND `chemical_gene`.`PubMedIDs`=`pubmed`.`PubMedIDs` AND `year`>='1990'AND `year`<='2018'	
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
			   'year'=>$Ch_Diyear[$j]->year
			 ); 
			 break;
		  }
		
			
	    }
		              		
	} 
	for($i = 0 ; $i < 29 ; $i++)
	{
		for($j = 0 ; $j < count($Ch_Geyear) ; $j++)
		{
		  if($x[$i][year]==$Ch_Geyear[$j]->year)
		  {
			  $a=$x[$i][y]+$Ch_Geyear[$j]->number;
			   $x[$i]=array(
			   'y'=>$a,
			   'year'=>$Ch_Geyear[$j]->year
			 ); 
			 break;
		  }
		  			
	    }
		              		
	}									 
	}
	else if(isset($search_Gene[0]->GeneID) && $search_Gene[0]->GeneID != NULL)
	{
		$Ch_Geyear = $db->SQL_select("SELECT `year`,COUNT(`year`)AS`number` 
		                              FROM `chemical_gene`,`gene` ,`pubmed` 
									  WHERE `gene`.`GeneID`='{$search_Gene[0]->GeneID}' AND `gene`.`GeneID`=`chemical_gene`.`GeneID`AND `chemical_gene`.`PubMedIDs`=`pubmed`.`PubMedIDs` 
									  AND `year`>='1990'AND `year`<='2018'GROUP BY `year` ORDER BY `year`");
		$Ge_Diyear = $db->SQL_select("SELECT `year`,COUNT(`year`)AS`number` 
		                             FROM `gene_disease`,`gene` ,`pubmed` 
									 WHERE `gene`.`GeneID`='{$search_Gene[0]->GeneID}' AND `gene`.`GeneID`=`gene_disease`.`GeneID`AND `gene_disease`.`PubMedIDs`=`pubmed`.`PubMedIDs` 
									 AND `year`>='1990'AND `year`<='2018'GROUP BY `year` ORDER BY `year`");
	
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
		for($j = 0 ; $j <count($Ch_Geyear) ; $j++)
		{
			 echo $x[$i]->year;
		  if($x[$i][year]==$Ch_Geyear[$j]->year)
		  {
			  $a=$x[$i][y]+$Ch_Geyear[$j]->number;
			  $x[$i]=array(
			   'y'=>$a,
			   'year'=>$Ch_Geyear[$j]->year
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
			   'year'=>$Ge_Diyear[$j]->year
			 ); 
			 break;
		  }
		  			
	    }
		              		
	}									 
	}
	
}
?>
<script>
var chart = new Highcharts.Chart({
    chart: {
        renderTo: 'container'
    }, 
    xAxis: {
	
       categories: ['1990', '1991', '1992', '1993', '1994', '1995', '1996', '1997', '1998', '1999', '2000', '2001', '2002', '2003', '2004', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018']
    },
    
    plotOptions: {
        series: {
            cursor: 'pointer',
            point: {
                events: {
                    click: function() {
                        alert ('Category: '+ this.category +', value: '+ this.y);
                    }
                }
            }
        }
    },
    
    series: [{
        type: "scatter",
       	data:<?php echo json_encode($x); ?>
		
      //  data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]        
    }]
});
</script>