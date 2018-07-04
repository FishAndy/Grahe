<?php
	set_time_limit(0);
	class DB
	{
		public $db_ip='203.64.84.94';
		public $ID='Fish';
		public $password='851217';
		public $database='projectctd';
		function __construct() //建構式
		{
			$this->SQL_connect();
			$this->SQL_database();
			$this->SQL_encode();
		}
		function SQL_connect() //設定連線
		{
			return mysql_connect($this->db_ip,$this->ID,$this->password);
		}
		function SQL_database() //選擇資料庫
		{
			return mysql_select_db($this->database);
		}
		function SQL_encode() //選擇資料庫語系
		{
			return mysql_query("SET NAMES utf-8");
		}
		function SQL_select($SQLstring) //搜尋方法
		{
			$result=mysql_query($SQLstring);
			if($result!=false)
			{
				$query=array();
				while($row=mysql_fetch_object($result))
				{
					$query[]=$row;
				}
				return $query;
			}
		}
		function SQL_query($SQLstring)
		{
			$result=mysql_query($SQLstring);
		}
	}
?>