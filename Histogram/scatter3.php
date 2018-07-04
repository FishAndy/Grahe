<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>文獻年份散佈圖</title>
        <script src="http://code.highcharts.com/highcharts.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <h1><font face="DFKai-sb">生醫物件之視覺化探索</font></h1>
        <ul class="nav nav-tabs">
            <li><a href="../104專題生.html">Home</a></li>
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">搜尋方式<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="../Network/index.php">物件關聯網絡圖</a></li>
                    <li><a href="">文獻分布關聯圖</a></li>
                    <li><a href="#">文獻網路圖</a></li>
                </ul>
            </li>
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">相關網站<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="http://ctdbase.org/" target="_blank">Comparative Toxicogenomics Database | CTD</a></li>
                </ul>
            </li>
            <li><a href="../help.html">Help</a></li>
        </ul>
        <form method="post">
            <input type="text" name="search" />
            <select name="yearone">
                <option value="1990"SELECTED>1990</option>
                <option value="1991">1991</option>
                <option value="1992">1992</option>
                <option value="1993">1993</option>
                <option value="1994">1994</option>
                <option value="1995">1995</option>
                <option value="1996">1996</option>
                <option value="1997">1997</option>
                <option value="1998">1998</option>
                <option value="1999">1999</option>
                <option value="2000">2000</option>
                <option value="2001">2001</option>
                <option value="2002">2002</option>
                <option value="2003">2003</option>
                <option value="2004">2004</option>
                <option value="2005">2005</option>
                <option value="2006">2006</option>
                <option value="2007">2007</option>
                <option value="2008">2008</option>
                <option value="2009">2009</option>
                <option value="2010">2010</option>
                <option value="2011">2011</option>
                <option value="2012">2012</option>
                <option value="2013">2013</option>
                <option value="2014">2014</option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option> 
        	</select>
         	<font face="DFKai-sb">--></font> 
            <select name="yeartwo">
                <option value="1990">1990</option>
                <option value="1991">1991</option>
                <option value="1992">1992</option>
                <option value="1993">1993</option>
                <option value="1994">1994</option>
                <option value="1995">1995</option>
                <option value="1996">1996</option>
                <option value="1997">1997</option>
                <option value="1998">1998</option>
                <option value="1999">1999</option>
                <option value="2000">2000</option>
                <option value="2001">2001</option>
                <option value="2002">2002</option>
                <option value="2003">2003</option>
                <option value="2004">2004</option>
                <option value="2005">2005</option>
                <option value="2006">2006</option>
                <option value="2007">2007</option>
                <option value="2008">2008</option>
                <option value="2009">2009</option>
                <option value="2010">2010</option>
                <option value="2011">2011</option>
                <option value="2012">2012</option>
                <option value="2013">2013</option>
                <option value="2014">2014</option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018"SELECTED>2018</option>
            </select>&nbsp;&nbsp;&nbsp;          
            <input type="submit" value="search" />
        </form>
    	<div id="container"></div>
    </body>
</html>
<?php

if(isset($_POST[search]) && $_POST[search] != NULL)
{
	include("../Class/db.class.php");$db = new DB();
		$db = new DB();
	$x = array();//�~
	$Objectcase='Disease';
	$year=$_POST["yeartwo"]-$_POST["yearone"];
	
	$Search_Disease = $db->SQL_select("SELECT `DiseaseID`,`DiseaseName` FROM `disease` 
							  			WHERE `DiseaseID`='$_POST[search]'
							  			OR `DiseaseName`='$_POST[search]'");
										
	if(isset($Search_Disease[0]->DiseaseID) && $Search_Disease[0]->DiseaseID != NULL)
	{
	 for($i = 0 ; $i <$year+1 ; $i++)
	 {
         $x[$i]=array(
		 'y'=>'',
         'year'=>$_POST["yearone"]+$i
          );                 
	 }	
	 $Ch_Diyear = $db->SQL_select("SELECT `year`,COUNT(`year`)AS`number`
								   FROM `chemical_disease`,`disease`,`pubmed`
								   WHERE `disease`.`DiseaseID`='{$Search_Disease[0]->DiseaseID}'
						           AND `disease`.`DiseaseID`=`chemical_disease`.`DiseaseID`
								   AND `chemical_disease`.`PubMedIDs`=`pubmed`.`PubMedIDs`
								   AND `year`>='$_POST[yearone]' AND `year`<='$_POST[yeartwo]'
								   GROUP BY `pubmed`.`PubMedIDs` ORDER BY `year`");	  
	 for($i = 0 ; $i < $year+1 ; $i++)
	 {
		for($j = 0 ; $j <count($Ch_Diyear) ; $j++)
		{
		 // echo $x[$i]->year;
		  if($x[$i][year]==$Ch_Diyear[$j]->year)
		  {
			  $a=$x[$i][y]+1;
			  $x[$i]=array(
			   'y'=>$a,
			   'year'=>$x[$i][year]
			 ); 
			
		  }		
	    }              		
	 } 	
	
	 $Ge_Diyear = $db->SQL_select("SELECT `year`,COUNT(`year`)AS`number`
								   FROM `gene_disease`,`disease` ,`pubmed`
								   WHERE `disease`.`DiseaseID`='{$Search_Disease[0]->DiseaseID}'
								   AND `disease`.`DiseaseID`=`gene_disease`.`DiseaseID`
								   AND `gene_disease`.`PubMedIDs`=`pubmed`.`PubMedIDs` 
						           AND `year`>='$_POST[yearone]' AND `year`<='$_POST[yeartwo]'
							       GROUP BY `pubmed`.`PubMedIDs` ORDER BY `year`");
		
	for($i = 0 ; $i < $year+1 ; $i++)
	 {
		for($j = 0 ; $j < count($Ge_Diyear) ; $j++)
		{
		  if($x[$i][year]==$Ge_Diyear[$j]->year)
		  {
			  $a=$x[$i][y]+1;
			   $x[$i]=array(
			   'y'=>$a,
			   'year'=>$x[$i][year]
			 ); 
			// break;
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
   title: {
        text: '<?php echo $Search_Disease[0]->DiseaseName ?>文獻年份散佈圖',
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