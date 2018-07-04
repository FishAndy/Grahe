<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>網絡圖</title>
</head>
<style>
	#mynetwork {
  width: 1980px;
  height: 1020px;
  border: 1px solid lightgray;
}
</style>
<body>
<form method="post">
	<input type="text" name="search" />
    <input type="submit" value="search" />
</form>
<?php
if(isset($_POST[search]) && $_POST[search] != NULL)
{
	include("db.class.php");
	$db = new DB();
	$nodes = array();
	$edges = array();
	
	$search_Chemical = $db->SQL_select("SELECT `ChemicalID`,`ChemicalName` FROM `chemical` 
							  			WHERE `ChemicalID`='$_POST[search]'
							  			OR `ChemicalName`='$_POST[search]'
							 			OR `CasRN`='$_POST[search]'");
	if(isset($search_Chemical[0]->ChemicalID) && $search_Chemical[0]->ChemicalID != NULL)
	{
		$Chemical_Disease = $db->SQL_select("SELECT `chemical-disease`.`DiseaseID`,`DiseaseName` 
											 FROM `chemical-disease`,`disease`
											 WHERE `ChemicalID`='{$search_Chemical[0]->ChemicalID}'
											 AND `chemical-disease`.`DiseaseID` = `disease`.`DiseaseID`");
		$Chemical_Gene = $db->SQL_select("SELECT `chemical-gene`.`GeneID`,`GeneSymbol`
										  FROM `chemical-gene`,`gene` 
										  WHERE `ChemicalID` = '{$search_Chemical[0]->ChemicalID}'
										  AND `chemical-gene`.`GeneID` = `gene`.`GeneID`");
		$nodes[0] = array(
				"id" => 0,
				"label" => $search_Chemical[0]->ChemicalName,
				"url" => "http://ctdbase.org/detail.go?type=chem&acc=".$search_Chemical[0]->ChemicalID,
				"color" => array("background" => "red"));
		for($i = 0 ; $i < count($Chemical_Disease) ; $i++)
		{
			$nodes[$i+1] = array(
				"id" => 'Disease'.$i,
				"label" => $Chemical_Disease[$i]->DiseaseName,
				"url" => "http://ctdbase.org/detail.go?type=disease&acc=".$Chemical_Disease[$i]->DiseaseID,
				"color" => array("background" => "yellow"));
			$edges[$i] = array(
				"from" => 0,
				"to" => 'Disease'.$i,
				"url" => "http://google.com");
		}
		for($j = 0 ; $j < count($Chemical_Gene) ; $j++)
		{
			$nodes[$j+$i+1] = array(
				"id" => 'Gene'.$j,
				"label" => $Chemical_Gene[$j]->GeneSymbol,
				"url" => "http://ctdbase.org/detail.go?type=gene&acc=".$Chemical_Gene[$j]->GeneID,
				"color" => array("background" => "green"));
			$edges[$j+$i] = array(
				"from" => 0,
				"to" => 'Gene'.$j,
				"url" => "http://google.com");
		}
	}//C000515
	else
	{
		$search_Disease = $db->SQL_select("SELECT `DiseaseID`,`DiseaseName` 
										   FROM `disease` 
										   WHERE `DiseaseID`='$_POST[search]' 
										   OR `DiseaseName`='$_POST[search]'");
		if(isset($search_Disease[0]->DiseaseID) && $search_Disease[0]->DiseaseID != NULL)
		{
			$Disease_Chemical = $db->SQL_select("SELECT `chemical-disease`.`ChemicalID`,`ChemicalName` 
												 FROM `chemical-disease` ,`chemical`
												 WHERE `DiseaseID`='{$search_Disease[0]->DiseaseID}'
												 AND `chemical-disease`.`ChemicalID` = `chemical`.`ChemicalID`");
			$Disease_Gene = $db->SQL_select("SELECT `gene-disease`.`GeneID`,`GeneSymbol` 
											 FROM `gene-disease`,`gene` 
											 WHERE `DiseaseID`= '{$search_Disease[0]->DiseaseID}'
											 AND `gene-disease`.`GeneID` = `gene`.`GeneID`");
			$nodes[0] = array(
				"id" => 0,
				"label" => $search_Disease[0]->DiseaseName,
				"url" => "http://ctdbase.org/detail.go?type=disease&acc=".$search_Disease[0]->DiseaseID,
				"color" => array("background" => "yellow"));
			for($i = 0 ; $i < count($Disease_Chemical) ; $i++)
			{
				$nodes[$i+1] = array(
					"id" => 'Chemical'.$i,
					"label" => $Disease_Chemical[$i]->ChemicalName,
					"url" => "http://ctdbase.org/detail.go?type=chem&acc=".$Disease_Chemical[$i]->ChemicalID,
					"color" => array("background" => "red"));
				$edges[$i] = array(
					"from" => 0,
					"to" => 'Chemical'.$i,
					"url" => "http://google.com");
			}
			for($j = 0 ; $j < count($Disease_Gene) ; $j++)
			{
				$nodes[$j+$i+1] = array(
					"id" => 'Gene'.$j,
					"label" => $Disease_Gene[$j]->GeneSymbol,
					"url" => "http://ctdbase.org/detail.go?type=gene&acc=".$Disease_Gene[$j]->GeneID,
					"color" => array("background" => "green"));
				$edges[$j+$i] = array(
					"from" => 0,
					"to" => 'Gene'.$j,
					"url" => "http://google.com");
			}
		}//MESH:C531617
		else
		{
			$search_Gene = $db->SQL_select("SELECT `GeneID`,`GeneSymbol` 
										    FROM `gene` 
											WHERE `GeneID` = '$_POST[search]'
											OR `GeneSymbol` = '$_POST[search]'");
			if(isset($search_Gene[0]->GeneID) && $search_Gene[0]->GeneID != NULL)
			{
				$Gene_Chemical = $db->SQL_select("SELECT `chemical-gene`.`ChemicalID`,`ChemicalName`
												  FROM `chemical-gene`,`chemical` 
												  WHERE `GeneID` = '{$search_Gene[0]->GeneID}'
												  AND `chemical-gene`.`ChemicalID` = `chemical`.`ChemicalID`");
				$Gene_Disease = $db->SQL_select("SELECT `gene-disease`.`DiseaseID`,`DiseaseName`
												 FROM `gene-disease`,`disease`
												 WHERE `GeneID` = '{$search_Gene[0]->GeneID}'
												 AND `gene-disease`.`DiseaseID` = `disease`.`DiseaseID`");
				$nodes[0] = array(
				"id" => 0,
				"label" => $search_Gene[0]->GeneSymbol,
				"url" => "http://ctdbase.org/detail.go?type=gene&acc=".$search_Gene[0]->GeneID,
				"color" => array("background" => "green"));
				for($i = 0 ; $i < count($Gene_Chemical) ; $i++)
				{
					$nodes[$i+1] = array(
						"id" => 'Chemical'.$i,
						"label" => $Gene_Chemical[$i]->ChemicalName,
						"url" => "http://ctdbase.org/detail.go?type=chem&acc=".$Gene_Chemical[$i]->ChemicalID,
						"color" => array("background" => "red"));
					$edges[$i] = array(
						"from" => 0,
						"to" => 'Chemical'.$i,
						"url" => "http://google.com");
				}
				for($j = 0 ; $j < count($Gene_Disease) ; $j++)
				{
					$nodes[$j+$i+1] = array(
						"id" => 'Disease'.$j,
						"label" => $Gene_Disease[$j]->DiseaseName,
						"url" => "http://ctdbase.org/detail.go?type=disease&acc=".$Gene_Disease[$j]->DiseaseID,
						"color" => array("background" => "yellow"));
					$edges[$j+$i] = array(
						"from" => 0,
						"to" => 'Disease'.$j,
						"url" => "http://google.com");
				}
			}//100
		}
	}
	$nodes = json_encode($nodes);
	$edges = json_encode($edges);
	echo "i = ".$i." & j = ".$j;
}
?>





<div id="mynetwork"></div>
<script src="vis.js"></script>
<script type="text/javascript">
	var nodes = new vis.DataSet(<?php echo $nodes ?>);
	var edges = new vis.DataSet(<?php echo $edges ?>);    
    // create a network
    var container = document.getElementById('mynetwork');
    var data = {
        nodes: nodes,
        edges: edges
    };
    var options = {
	};
    var network = new vis.Network(container, data, options);
	
   /* network.on("selectNode", function (params) {
        if (params.nodes.length === 1) {
            var node = nodes.get(params.nodes[0]);
            window.open(node.url, '_blank');
        }
    });*/
	network.on("click", function(params) {                              
    if (params.nodes.length === 1) {
		var node = nodes.get(params.nodes[0]);
        window.open(node.url, '_blank');
		}        
     else if (params.edges.length==1) {
		 var edge = edges.get(params.edges[0]);
		 window.open(edge.url, '_blank');
		 } 
});
</script>
</body>
</html>