<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php

	if($_GET[Graph] == 'Network')
	{
		include("../Class/db.class.php");
		$db = new DB();
		
		switch($_GET['Case'])
		{
			case 'CD':
				$sql = "SELECT `year`,`chemical_disease`.`PubMedIDs` 
						FROM `chemical_disease`,`pubmed`
						WHERE `chemical_disease`.`ChemicalID` = '$_GET[Object1]'
						AND	`chemical_disease`.`DiseaseID` = '$_GET[Object2]'
						AND `chemical_disease`.`PubMedIDs` = `pubmed`.`PubMedIDs`
						ORDER BY `year` DESC";
				break;
			case 'GD':
				$sql = "SELECT `year`,`gene_disease`.`PubMedIDs`
						FROM `gene_disease`,`pubmed`
						WHERE `gene_disease`.`GeneID` = '$_GET[Object1]'
						AND `gene_disease`.`DiseaseID` = '$_GET[Object2]'
						AND `gene_disease`.`PubMedIDs` = `pubmed`.`PubMedIDs`
						ORDER BY `year` DESC";
				break;	
			case 'CG':
				$sql = "SELECT `year`,`chemical_gene`.`PubMedIDs`
						FROM `chemical_gene`,`pubmed`
						WHERE `chemical_gene`.`ChemicalID` = '$_GET[Object1]'
						AND `chemical_gene`.`GeneID` = '$_GET[Object2]'
						AND `chemical_gene`.`PubMedIDs` = `pubmed`.`PubMedIDs`
						ORDER BY `year` DESC";
				break;		
		}
		$ansql = $db->SQL_select($sql);
		new_table($ansql,$db);
	}
	else if($_GET[Graph] == 'Histogram')
	{
		echo "<script>window.alert('施工中')</script>";
	}
	
	function new_table($arr,$db)
	{
		echo "<div class='popover-options'>";
			echo "<table border='1'>";
				echo "<tr>";
					echo "<th>Year</th>";
					echo "<th>PubMedID</th>";
					echo "<th>主要物件</th>";
				echo "</tr>";
				foreach($arr as $Value){
				echo "<tr>";
					echo "<td>".$Value->year."</td>";
					echo "<td><a href='http://ctdbase.org/detail.go?type=reference&acc=".$Value->PubMedIDs."' target='_blank'>".$Value->PubMedIDs."</a></td>";
					$temp = PubMedID_Search($Value->PubMedIDs,$db);
					echo "<td><a href='#' data-toggle='popover' data-placement='right' data-content='";
						echo "<table border=1>";
							echo "<tr>";
								echo "<th>Chemical</th>";
								echo "<th>Disease</th>";
								echo "<th>Gene</th>";
							echo "</tr>";
							for($i = 0 ; isset($temp[Chemical][$i]) || isset($temp[Disease][$i]) || isset($temp[Gene][$i]) ; $i++){
							echo "<tr>";
								echo "<td><a href=../Network/Index.php?_return=".str_replace("'",'`',$temp[Chemical][$i][ChemicalID])." target=_blank>".str_replace("'",'`',$temp[Chemical][$i][ChemicalName])."</a></td>";
								echo "<td><a href=../Network/Index.php?_return=".str_replace("'",'`',$temp[Disease][$i][DiseaseID])." target=_blank>".str_replace("'",'`',$temp[Disease][$i][DiseaseName])."</a></td>";
								echo "<td><a href=../Network/Index.php?_return=".str_replace("'",'`',$temp[Gene][$i][GeneID])." target=_blank>".str_replace("'",'`',$temp[Gene][$i][GeneSymbol])."</a></td>";
							echo "</tr>";
							}
						echo "</table>'>";
					echo "點我</a></td>";
				echo "</tr>";}
			echo "</table>";
		echo "</div>";
	}
	/*function PubMedID_Search($ID,$db)
	{
		$sql = "SELECT `chemical`.`ChemicalID`,`chemical`.`ChemicalName`
				FROM `chemical`,`chemical_disease`,`chemical_gene`
				WHERE `chemical_disease`.`PubMedIDs` = '$ID'
				AND`chemical_gene`.`PubMedIDs` = '$ID'
				AND (`chemical_disease`.`ChemicalID` = `chemical`.`ChemicalID`
					 OR `chemical_gene`.`ChemicalID` = `chemical`.`ChemicalID`)
				GROUP BY `ChemicalID`";
		$ans = $db->SQL_select($sql);
		if(isset($ans[0]))
			$ansql[] = $ans;
		
		$sql = "SELECT `disease`.`DiseaseID`,`disease`.`DiseaseName`
				FROM `disease`,`chemical_disease`,`gene_disease`
				WHERE `chemical_disease`.`PubMedIDs` = '$ID'
				AND `gene_disease`.`PubMedIDs` = '$ID'
				AND (`chemical_disease`.`DiseaseID` = `disease`.`DiseaseID`
					 OR `gene_disease`.`DiseaseID` = `disease`.`DiseaseID`)
				GROUP BY `DiseaseID`";
		$ans = $db->SQL_select($sql);
		if(isset($ans[0]))
			$ansql[] = $ans;
		
		$sql = "SELECT `gene`.`GeneID`,`gene`.`GeneSymbol`
				FROM `gene`,`chemical_gene`,`gene_disease`
				WHERE `chemical_gene`.`PubMedIDs` = '$ID'
				AND `gene_disease`.`PubMedIDs` = '$ID'
				AND (`chemical_gene`.`GeneID` = `gene`.`GeneID`
					 or `gene_disease`.`GeneID` = `gene`.`GeneID`)
				GROUP BY `GeneID`";
		$ans = $db->SQL_select($sql);
		if(isset($ans[0]))
			$ansql[] = $ans;
			
		return $ansql;
	}	*/
	function PubMedID_Search($ID,$db)
	{
		$arr = array('Chemical'=>array(),'Disease'=>array(),'Gene'=>array());
		//找Chemical
		$sql = "SELECT `chemical`.`ChemicalID`,`chemical`.`ChemicalName`
				FROM `chemical`,`chemical_disease`
				WHERE `chemical_disease`.`PubMedIDs` = '$ID'
				AND `chemical_disease`.`ChemicalID` = `chemical`.`ChemicalID`
				GROUP BY `ChemicalID`";
		$ans = $db->SQL_select($sql);
		foreach($ans as $Value)
		{
			$temp = array(
						'ChemicalID' => $Value->ChemicalID,
						'ChemicalName' => $Value->ChemicalName
			);
			array_push($arr['Chemical'],$temp);
		}
		$sql = "SELECT `chemical`.`ChemicalID`,`chemical`.`ChemicalName`
				FROM `chemical`,`chemical_gene`
				WHERE `chemical_gene`.`PubMedIDs` = '$ID'
				AND `chemical_gene`.`ChemicalID` = `chemical`.`ChemicalID`
				GROUP BY `ChemicalID`";
		$ans = $db->SQL_select($sql);
		foreach($ans as $Value)
		{
			$temp = array(
						'ChemicalID' => $Value->ChemicalID,
						'ChemicalName' => $Value->ChemicalName
			);
			if(!in_array($temp,$arr['Chemical']))
				array_push($arr['Chemical'],$temp);
		}
		//找Disease
		$sql = "SELECT `disease`.`DiseaseID`,`disease`.`DiseaseName`
				FROM `disease`,`chemical_disease`
				WHERE `chemical_disease`.`PubMedIDs` = '$ID'
				AND `chemical_disease`.`DiseaseID` = `disease`.`DiseaseID`
				GROUP BY `DiseaseID`";
		$ans = $db->SQL_select($sql);
		foreach($ans as $Value)
		{
			$temp = array(
						'DiseaseID' => $Value->DiseaseID,
						'DiseaseName' => $Value->DiseaseName
			);
			array_push($arr['Disease'],$temp);
		}
		$sql = "SELECT `disease`.`DiseaseID`,`disease`.`DiseaseName`
				FROM `disease`,`gene_disease`
				WHERE `gene_disease`.`PubMedIDs` = '$ID'
				AND `gene_disease`.`DiseaseID` = `disease`.`DiseaseID`
				GROUP BY `DiseaseID`";
		$ans = $db->SQL_select($sql);
		foreach($ans as $Value)
		{
			$temp = array(
						'DiseaseID' => $Value->DiseaseID,
						'DiseaseName' => $Value->DiseaseName
			);
			if(!in_array($temp,$arr['Disease']))
				array_push($arr['Disease'],$temp);
		}
		//找Gene
		$sql = "SELECT `gene`.`GeneID`,`gene`.`GeneSymbol`
				FROM `gene`,`chemical_gene`
				WHERE `chemical_gene`.`PubMedIDs` = '$ID'
				AND `chemical_gene`.`GeneID` = `gene`.`GeneID`
				GROUP BY `GeneID`";
		$ans = $db->SQL_select($sql);
		foreach($ans as $Value)
		{
			$temp = array(
						'GeneID' => $Value->GeneID,
						'GeneSymbol' => $Value->GeneSymbol
			);
			array_push($arr['Gene'],$temp);
		}
		$sql = "SELECT `gene`.`GeneID`,`gene`.`GeneSymbol`
				FROM `gene`,`gene_disease`
				WHERE `gene_disease`.`PubMedIDs` = '$ID'
				AND `gene_disease`.`GeneID` = `gene`.`GeneID`
				GROUP BY `GeneID`";
		$ans = $db->SQL_select($sql);
		foreach($ans as $Value)
		{
			$temp = array(
						'GeneID' => $Value->GeneID,
						'GeneSymbol' => $Value->GeneSymbol
			);
			if(!in_array($temp,$arr['Gene']))
				array_push($arr['Gene'],$temp);
		}
		
		return $arr;
	}
?>
<script>
		$(function () { $('.popover-show').popover('show');});
		$(function () { $('.popover-hide').popover('hide');});
		$(function () { $('.popover-destroy').popover('destroy');});
		$(function () { $('.popover-toggle').popover('toggle');});
		$(function () { $(".popover-options a").popover({html : true });});
</script>