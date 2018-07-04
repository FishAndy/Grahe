<?php
	set_time_limit(0);
	class graphe
	{
		public $nodes = array();
		public $edges = array();
		public $origin;
		public $chemical = array
		(
			"url" => "http://ctdbase.org/detail.go?type=chem&acc=",
			"backgorund" => "red"
		);
		public $disease = array
		(
			"url" => "http://ctdbase.org/detail.go?type=disease&acc=",
			"backgorund" => "yellow"
		);
		public $gene = array
		(
			"url" => "http://ctdbase.org/detail.go?type=gene&acc=",
			"backgorund" => "green"
		);
		public $nodes_number;
		public $edges_number;
		
		function __construct() //建構式
		{
			$this->nodes_number = -1;
			$this->edges_number = 0;
		}
		function chang_origin()
		{
			$this->origin = $this->nodes_number;
		}
		function reset_origin()
		{
			$this->origin = 0;
		}
		function new_chemical($id , $name)
		{
			$this->nodes_number++;
			$this->nodes[$this->nodes_number] = array
			(
				"id" => $this->nodes_number,
				"label" => $name,
				"url" => $this->chemical[url].$id,
				"color" => array
				(
					"background" => $this->chemical[backgorund]
				)
			);
		}
		function new_disease($id , $name)
		{
			$this->nodes_number++;
			$this->nodes[$this->nodes_number] = array
			(
				"id" => $this->nodes_number,
				"label" => $name,
				"url" => $this->disease[url].$id,
				"color" => array
				(
					"background" => $this->disease[backgorund]
				)
			);
		}
		function new_gene($id , $name)
		{
			$this->nodes_number++;
			$this->nodes[$this->nodes_number] = array
			(
				"id" => $this->nodes_number,
				"label" => $name,
				"url" => $this->gene[url].$id,
				"color" => array
				(
					"background" => $this->gene[backgorund]
				)
			);
		}
		function new_edges($search1 , $search2 , $case)
		{
			$this->edges[$this->edges_number] = array
			(
				"from" => $this->origin,
				"to" => $this->nodes_number,
				"url" => "graphe2.php?search1=$search1&search2=$search2&case=$case"
			);
			$this->edges_number++;
		}
	}
?>