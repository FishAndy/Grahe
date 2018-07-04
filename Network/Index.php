<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../Class/vis.js"></script>
        <script src="networkJS.js"></script>
        <title>物件關聯網絡圖</title>
    </head>
    <style>
	#mynetwork {
	  width: 1980px;
	  height: 1020px;
	  border: 1px solid lightgray;
	}
	</style>
    <body>
        <h1><font face="DFKai-sb">生醫物件之視覺化探索</font></h1>
        <ul class="nav nav-tabs">
            <li><a href="../104專題生.html">Home</a></li>
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">搜尋方式<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="">物件關聯網絡圖</a></li>
                    <li><a href="../Histogram/scatter3.php">文獻分布關聯圖</a></li>
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
        <form>
            ID、Name:<input type="text" id="search" />&nbsp;&nbsp;&nbsp;
            優先度搜尋:<select id="sel1">
                <option value="All">All</option>
                <option value="Chemical">Chemical</option>
                <option value="Disease">Disease</option>
                <option value="Gene">Gene</option>
            </select>&nbsp;&nbsp;&nbsp;
            節點個數:<select id="sel2">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5" SELECTED>5</option>
            </select>&nbsp;&nbsp;&nbsp;
            <input type="button" value="search" onclick="get_value()"/>
        </form>
        <div id="mynetwork"></div>
    </body>
</html>
<?
	if(isset($_GET[_return]) && $_GET[_return] != NULL)
	{
		echo $_GET[_return];
		echo "<script>network('$_GET[_return]','All',5)</script>";
	}
?>