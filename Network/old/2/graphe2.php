<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body>
</body>
</html>
<?php
	include("db.class.php");
	$db = new DB();
	
	switch($_GET['case'])
	{
		case 'CD':
			$query = $db->SQL_select("SELECT `DirectEvidence`,`PubMedIDs` 
								  	  FROM `chemical-disease` 
								      WHERE `ChemicalID` = '$_GET[search1]' 
								      AND `DiseaseID` = '$_GET[search2]'");
			echo "<table border=1>";
				echo "<tr>";
					echo "<th>DirectEvidence</th>";
					echo "<th>PubMedIDs</th>";
				echo "</tr>";                                   
			for($i = 0 ; $i < count($query) ; $i++)
			{
				echo "<tr>";
					echo "<td>".$query[$i]->DirectEvidence."</td>";
				$str = explode("|",$query[$i]->PubMedIDs);
				for($j = 0 ; $j < count($str) ; $j++)
					echo "<td><a href='http://ctdbase.org/detail.go?type=reference&acc=".$str[$j]."'target='_blank'>".$str[$j]."</a></td>";
				echo "</tr>";
			}
			echo "</table>";
			break;
		case 'GD':
			$query = $db->SQL_select("SELECT `DirectEvidence`,`PubMedIDs` 
									  FROM `gene-disease` 
									  WHERE `GeneID` = '$_GET[search1]' 
									  AND `DiseaseID` = '$_GET[search2]'");
			echo "<table border=1>";
				echo "<tr>";
					echo "<th>DirectEvidence</th>";
					echo "<th>PubMedIDs</th>";
				echo "<tr>";
			for($i = 0 ; $i < count($query) ; $i++)
			{
				echo "<tr>";
					echo "<td>".$query[$i]->DirectEvidence."</td>";
				$str = explode("|",$query[$i]->PubMedIDs);
				for($j = 0 ; $j < count($str) ; $j++)
					echo "<td><a href='http://ctdbase.org/detail.go?type=reference&acc=".$str[$j]."'target='_blank'>".$str[$j]."</a></td>";
				echo "</tr>";
			}
			echo "</table>";
			break;
		case 'CG':
			$query = $db->SQL_select("SELECT `chemical-gene`.`Interaction`,`chemical-gene`.`InteractionActions`,`PubMedIDs`
									  FROM `chemical-gene` 
									  WHERE `ChemicalID` = '$_GET[search1]' 
									  AND `GeneID` = '$_GET[search2]'");
			echo "<table border=1>";
				echo "<tr>";
					echo "<th>Interaction</th>";
					echo "<th>InteractionActions</th>";
					echo "<th>PubMedIDs</th>";
				echo "</tr>";
			for($i = 0 ; $i < count($query) ; $i++)
			{
				echo "<tr>";
					echo "<td>".$query[$i]->Interaction."</td>";
					echo "<td>".$query[$i]->InteractionActions."</td>";
				$str = explode("|",$query[$i]->PubMedIDs);
				for($j = 0 ; $j < count($str) ; $j++)
					echo "<td><a href='http://ctdbase.org/detail.go?type=reference&acc=".$str[$j]."'target='_blank'>".$str[$j]."</a></td>";
				echo "</tr>";
			}
			echo "</table>";
			break;	
	}
	//https://www.ncbi.nlm.nih.gov/pubmed/?term=?????
?>