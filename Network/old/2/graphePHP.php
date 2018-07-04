<?php
		function Search_Chemical($search)
		{
			return "SELECT `ChemicalID`,`ChemicalName` 
					FROM `chemical` 
					WHERE `ChemicalID`='$search'
					OR `ChemicalName`='$search'
					OR `CasRN`='$search'";
		}
		function Search_Disease($search)
		{
			return "SELECT `DiseaseID`,`DiseaseName` 
					FROM `disease` 
					WHERE `DiseaseID`='$search'
					OR `DiseaseName`='$search'";
		}
		function Search_Gene($search)
		{
			return "SELECT `GeneID`,`GeneSymbol` 
					FROM `gene` 
					WHERE `GeneID`='$search'
					OR `GeneSymbol`='$search'";
		}
		function Chemical_Disease($ChemicalID)
		{
			return "SELECT `chemical-disease`.`DiseaseID`,`DiseaseName` 
					FROM `chemical-disease`,`disease`
					WHERE `ChemicalID`='{$ChemicalID}'
					AND `chemical-disease`.`DiseaseID` = `disease`.`DiseaseID`
					Group by `chemical-disease`.`DiseaseID`";
		}
		function Chemical_Gene($ChemicalID)
		{
			return "SELECT `chemical-gene`.`GeneID`,`GeneSymbol`
					FROM `chemical-gene`,`gene` 
					WHERE `ChemicalID` = '{$ChemicalID}'
					AND `chemical-gene`.`GeneID` = `gene`.`GeneID`
					Group by `chemical-gene`.`GeneID`";
		}
		function Disease_Chemical($DiseaseID)
		{
			return "SELECT `chemical-disease`.`ChemicalID`,`ChemicalName` 
					FROM `chemical-disease` ,`chemical`
					WHERE `DiseaseID`='{$DiseaseID}'
					AND `chemical-disease`.`ChemicalID` = `chemical`.`ChemicalID`
					Group by `chemical-disease`.`ChemicalID`";
		}
		function Disease_Gene($DiseaseID)
		{
			return "SELECT `gene-disease`.`GeneID`,`GeneSymbol` 
					FROM `gene-disease`,`gene` 
					WHERE `DiseaseID`= '{$DiseaseID}'
					AND `gene-disease`.`GeneID` = `gene`.`GeneID`
					Group by `gene-disease`.`GeneID`";
		}
		function Gene_Chemical($GeneID)
		{
			return "SELECT `chemical-gene`.`ChemicalID`,`ChemicalName`
					FROM `chemical-gene`,`chemical` 
					WHERE `GeneID` = '{$GeneID}'
					AND `chemical-gene`.`ChemicalID` = `chemical`.`ChemicalID`
					Group by `chemical-gene`.`ChemicalID`";
		}
		function Gene_Disease($GeneID)
		{
			return "SELECT `gene-disease`.`DiseaseID`,`DiseaseName`
					FROM `gene-disease`,`disease`
					WHERE `GeneID` = '{$GeneID}'
					AND `gene-disease`.`DiseaseID` = `disease`.`DiseaseID`
					Group by `gene-disease`.`DiseaseID`";
		}
		
		function Chemical($graph,$db,$ID,$Name,$max)
		{
			$graph->reset_origin();
			$graph->new_chemical($ID , $Name);
			
			$Chemical_Disease = $db->SQL_select(Chemical_Disease($ID));
			for($i = 0 ; $i < $max && $i < count($Chemical_Disease) ; $i++)
			{
				$graph->new_disease($Chemical_Disease[$i]->DiseaseID , $Chemical_Disease[$i]->DiseaseName);
				$graph->new_edges($ID , $Chemical_Disease[$i]->DiseaseID , 'CD');
				
				$graph->chang_origin();
				
				$Disease_Chemical = $db->SQL_select(Disease_Chemical($Chemical_Disease[$i]->DiseaseID));
				for($j = 0 ; $j < $max && $j < count($Disease_Chemical) ; $j++)
				{
					if($Disease_Chemical[$j]->ChemicalID != $ID)
					{
						$graph->new_chemical($Disease_Chemical[$j]->ChemicalID , $Disease_Chemical[$j]->ChemicalName);
						$graph->new_edges($Disease_Chemical[$j]->ChemicalID , $Chemical_Disease[$i]->DiseaseID , 'CD');
					}
				}
				
				$Disease_Gene = $db->SQL_select(Disease_Gene($Chemical_Disease[$i]->DiseaseID));
				for($j = 0 ; $j < $max && $j < count($Disease_Gene) ; $j++)
				{
					if($Disease_Gene[$j]->GeneID != $ID)
					{
						$graph->new_gene($Disease_Gene[$j]->GeneID , $Disease_Gene[$j]->GeneSymbol);
						$graph->new_edges($Disease_Gene[$j]->GeneID , $Chemical_Disease[$i]->DiseaseID , 'GD');
					}
				}
				$graph->reset_origin();
			}
			
			$Chemical_Gene = $db->SQL_select(Chemical_Gene($ID));
			for($i = 0 ; $i < $max && $i < count($Chemical_Gene) ; $i++)
			{
				$graph->new_gene($Chemical_Gene[$i]->GeneID , $Chemical_Gene[$i]->GeneSymbol);
				$graph->new_edges($ID , $Chemical_Gene[$i]->GeneID , 'CG');
				
				$graph->chang_origin();
				
				$Gene_Chemical = $db->SQL_select(Gene_Chemical($Chemical_Gene[$i]->GeneID));
				for($j = 0 ; $j < $max && $j < count($Gene_Chemical) ; $j++)
				{
					if($Gene_Chemical[$j]->ChemicalID != $ID)
					{
						$graph->new_chemical($Gene_Chemical[$j]->ChemicalID , $Gene_Chemical[$j]->ChemicalName);
						$graph->new_edges($Gene_Chemical[$j]->ChemicalID , $Chemical_Gene[$i]->GeneID , 'CG');
					}
				}
				
				$Gene_Disease = $db->SQL_select(Gene_Disease($Chemical_Gene[$i]->GeneID));
				for($j = 0 ; $j < $max && $j < count($Gene_Disease) ; $j++)
				{
					if($Gene_Disease[$j]->DiseaseID != $ID)
					{
						$graph->new_disease($Gene_Disease[$j]->DiseaseID , $Gene_Disease[$j]->DiseaseName);
						$graph->new_edges($Chemical_Gene[$i]->GeneID , $Gene_Disease[$j]->DiseaseID , 'GD');
					}
				}
				$graph->reset_origin();
			}
			return $graph;
		}
		function Disease($graph,$db,$ID,$Name,$max)
		{
			$graph->reset_origin();
			$graph->new_disease($ID , $Name);
			
			$Disease_Chemical = $db->SQL_select(Disease_Chemical($ID));
			for($i = 0 ; $i < $max && $i < count($Disease_Chemical) ; $i++)
			{
				$graph->new_chemical($Disease_Chemical[$i]->ChemicalID , $Disease_Chemical[$i]->ChemicalName);
				$graph->new_edges($Disease_Chemical[$i]->ChemicalID , $ID , 'CD');
			
				$graph->chang_origin();
				$Chemical_Disease = $db->SQL_select(Chemical_Disease($Disease_Chemical[$i]->ChemicalID));
				for($j = 0 ; $j < $max && $j < count($Chemical_Disease) ; $j++)
				{
					if($Chemical_Disease[$j]->DiseaseID != $ID)
					{
						$graph->new_disease($Chemical_Disease[$j]->DiseaseID , $Chemical_Disease[$j]->DiseaseName);
						$graph->new_edges($Disease_Chemical[$i]->ChemicalID , $Chemical_Disease[$j]->DiseaseID , 'CD');
					}
				}
				
				$Chemical_Gene = $db->SQL_select(Chemical_Gene($Disease_Chemical[$i]->ChemicalID));
				for($j = 0 ; $j < $max && $j < count($Chemical_Gene) ; $j++)
				{
					if($Chemical_Gene[$j]->GeneID != $ID)
					{
						$graph->new_gene($Chemical_Gene[$j]->GeneID , $Chemical_Gene[$j]->GeneSymbol);
						$graph->new_edges($Disease_Chemical[$i]->ChemicalID , $Chemical_Gene[$j]->GeneID , 'CG');
					}
				}
				$graph->reset_origin();
			}
			$Disease_Gene = $db->SQL_select(Disease_Gene($ID));
			for($i = 0 ; $i < $max && $i < count($Disease_Gene) ; $i++)
			{
				$graph->new_gene($Disease_Gene[$i]->GeneID , $Disease_Gene[$i]->GeneSymbol);
				$graph->new_edges($Disease_Gene[$i]->GeneID , $ID , 'GD');
			
				$graph->chang_origin();
				$Gene_Disease = $db->SQL_select(Gene_Disease($Disease_Gene[$i]->GeneID));
				for($j = 0 ; $j < $max && $j < count($Gene_Disease) ; $j++)
				{
					if($Gene_Disease[$j]->DiseaseID != $ID)
					{
						$graph->new_disease($Gene_Disease[$j]->DiseaseID , $Gene_Disease[$j]->DiseaseName);
						$graph->new_edges($Disease_Gene[$i]->GeneID , $Gene_Disease[$j]->DiseaseID , 'GD');
					}
				}
				
				$Gene_Chemical = $db->SQL_select(Gene_Chemical($Disease_Gene[$i]->GeneID));
				for($j = 0 ; $j < $max && $j < count($Gene_Chemical) ; $j++)
				{
					if($Gene_Chemical[$j]->ChemicalID != $ID)
					{
						$graph->new_chemical($Gene_Chemical[$j]->ChemicalID , $Gene_Chemical[$j]->ChemicalName);
						$graph->new_edges($Gene_Chemical[$j]->ChemicalID , $Disease_Gene[$i]->GeneID , 'CG');
					}
				}
				$graph->reset_origin();
			}
			return $graph;
		}
		function Gene($graph,$db,$ID,$Name,$max)
		{
			$graph->reset_origin();
			$graph->new_gene($ID , $Name);
			
			$Gene_Chemical = $db->SQL_select(Gene_Chemical($ID));
			for($i = 0 ; $i < $max && $i < count($Gene_Chemical) ; $i++)
			{
				$graph->new_chemical($Gene_Chemical[$i]->ChemicalID , $Gene_Chemical[$i]->ChemicalName);
				$graph->new_edges($Gene_Chemical[$i]->ChemicalID , $ID , 'CG');
				
				$graph->chang_origin();
				$Chemical_Gene = $db->SQL_select(Chemical_Gene($Gene_Chemical[$i]->ChemicalID));
				for($j = 0 ; $j < $max && $j < count($Chemical_Gene) ; $j++)
				{
					if($Chemical_Gene[$j]->GeneID != $ID)
					{
						$graph->new_gene($Chemical_Gene[$j]->GeneID , $Chemical_Gene[$j]->GeneSymbol);
						$graph->new_edges($Gene_Chemical[$i]->ChemicalID , $Chemical_Gene[$j]->GeneID , 'CG');
					}
				}
				
				$Chemical_Disease = $db->SQL_select(Chemical_Disease($Gene_Chemical[$i]->ChemicalID));
				for($j = 0 ; $j < $max && $j < count($Chemical_Disease) ; $j++)
				{
					if($Chemical_Disease[$j]->DiseaseID != $ID)
					{
						$graph->new_disease($Chemical_Disease[$j]->DiseaseID , $Chemical_Disease[$j]->DiseaseName);
						$graph->new_edges($Gene_Chemical[$i]->ChemicalID , $Chemical_Disease[$j]->DiseaseID , 'CD');
					}
				}
				$graph->reset_origin();
			}
			
			$Gene_Disease = $db->SQL_select(Gene_Disease($ID));
			for($i = 0 ; $i < $max && $i < count($Gene_Disease) ; $i++)
			{
				$graph->new_disease($Gene_Disease[$i]->DiseaseID , $Gene_Disease[$i]->DiseaseName);
				$graph->new_edges($ID , $Gene_Disease[$i]->DiseaseID , 'GD');
				
				$graph->chang_origin();
				$Disease_Gene = $db->SQL_select(Disease_Gene($Gene_Disease[$i]->DiseaseID));
				for($j = 0 ; $j < $max && $j < count($Disease_Gene) ; $j++)
				{
					if($Disease_Gene[$j]->GeneID != $ID)
					{
						$graph->new_gene($Disease_Gene[$j]->GeneID , $Disease_Gene[$j]->GeneSymbol);
						$graph->new_edges($Disease_Gene[$j]->GeneID , $Gene_Disease[$i]->DiseaseID,'GD');
					}
				}
				
				$Disease_Chemical = $db->SQL_select(Disease_Chemical($Gene_Disease[$i]->DiseaseID));
				for($j = 0 ; $j < $max && $j < count($Disease_Chemical) ; $j++)
				{
					if($Disease_Chemical[$j]->ChemicalID != $ID)
					{
						$graph->new_chemical($Disease_Chemical[$j]->ChemicalID , $Disease_Chemical[$j]->ChemicalName);
						$graph->new_edges($Disease_Chemical[$j]->ChemicalID , $Gene_Disease[$i]->DiseaseID , 'CD');
					}
				}
				$graph->reset_origin();
			}
			return $graph;
		}
		
		if(isset($_POST[_search]) && $_POST[_search] != NULL)
		{
			include("db.class.php");
			include("object.php");
			$db = new DB();
			$graph = new graphe();
			$max = $_POST[Max];
			
			switch($_POST[sel])
			{
				case 'All': case 'Chemical':
					$search_Chemical = $db->SQL_select(search_Chemical($_POST[_search]));
					if(isset($search_Chemical[0]->ChemicalID) && $search_Chemical[0]->ChemicalID != NULL)
					{
						$graph = Chemical($graph,$db,$search_Chemical[0]->ChemicalID,$search_Chemical[0]->ChemicalName,$max);
					}//C000515
					else
					{
						$search_Disease = $db->SQL_select(Search_Disease($_POST[_search]));
						if(isset($search_Disease[0]->DiseaseID) && $search_Disease[0]->DiseaseID != NULL)
						{
							$graph = Disease($graph,$db,$search_Disease[0]->DiseaseID,$search_Disease[0]->DiseaseName,$max);
						}//MESH:C531795
						else
						{
							$search_Gene = $db->SQL_select(Search_Gene($_POST[_search]));
							if(isset($search_Gene[0]->GeneID) && $search_Gene[0]->GeneID != NULL)
							{
								$graph = Gene($graph,$db,$search_Gene[0]->GeneID,$search_Gene[0]->GeneSymbol,$max);
							}//1
						}
					}
					break;
				case 'Disease':
					$search_Disease = $db->SQL_select(Search_Disease($_POST[_search]));
					if(isset($search_Disease[0]->DiseaseID) && $search_Disease[0]->DiseaseID != NULL)
					{
						$graph = Disease($graph,$db,$search_Disease[0]->DiseaseID,$search_Disease[0]->DiseaseName,$max);
					}//MESH:C531795
					else
					{
						$search_Chemical = $db->SQL_select(search_Chemical($_POST[_search]));
						if(isset($search_Chemical[0]->ChemicalID) && $search_Chemical[0]->ChemicalID != NULL)
						{
							$graph = Chemical($graph,$db,$search_Chemical[0]->ChemicalID,$search_Chemical[0]->ChemicalName,$max);
						}//C000515
						else
						{
							$search_Gene = $db->SQL_select(Search_Gene($_POST[_search]));
							if(isset($search_Gene[0]->GeneID) && $search_Gene[0]->GeneID != NULL)
							{
								$graph = Gene($graph,$db,$search_Gene[0]->GeneID,$search_Gene[0]->GeneSymbol,$max);
							}//1
						}
					}
					break;
				case 'Gene':
					$search_Gene = $db->SQL_select(Search_Gene($_POST[_search]));
					if(isset($search_Gene[0]->GeneID) && $search_Gene[0]->GeneID != NULL)
					{
						$graph = Gene($graph,$db,$search_Gene[0]->GeneID,$search_Gene[0]->GeneSymbol,$max);
					}//1
					else
					{
						$search_Chemical = $db->SQL_select(search_Chemical($_POST[_search]));
						if(isset($search_Chemical[0]->ChemicalID) && $search_Chemical[0]->ChemicalID != NULL)
						{
							$graph = Chemical($graph,$db,$search_Chemical[0]->ChemicalID,$search_Chemical[0]->ChemicalName,$max);
						}//C000515
						else
						{
							$search_Disease = $db->SQL_select(Search_Disease($_POST[_search]));
							if(isset($search_Disease[0]->DiseaseID) && $search_Disease[0]->DiseaseID != NULL)
							{
								$graph = Disease($graph,$db,$search_Disease[0]->DiseaseID,$search_Disease[0]->DiseaseName,$max);
							}//MESH:C531795
						}
					}
					break;
			}
		}
		$temp = array("nodes"=>$graph->nodes,"edges"=>$graph->edges);
		echo json_encode($temp);
?>