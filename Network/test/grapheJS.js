function get_value()
{
	var id  = document.getElementById('search').value;
	var sel = document.getElementById('sel1').value;
	var Max = document.getElementById('sel2').value;
	graphe(id,sel,Max);
}

function graphe(id,sel,Max)
{
		$.ajax(
		{
			url : "graphePHP.php",
			type : "POST",
			data :
			{
				_search : id,
				sel : sel,
				Max : Max
			},
			dataType:"json",
			success : function(data)
			{
				var nodes = new vis.DataSet(data.nodes);
				var edges = new vis.DataSet(data.edges);    
				// create a network
				var container = document.getElementById('mynetwork');
				var data = {
					nodes: nodes,
					edges: edges
				};
				var options = {
				};
				var network = new vis.Network(container, data, options);
				
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
			},
			error : function()
			{
				window.alert("錯誤");
			}
		})
}