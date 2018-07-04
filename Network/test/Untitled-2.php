<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
 $(function() {
  $("[data-toggle=popover]").popover({
    html: true,
    content: function() {
      var content = $(this).attr("data-popover-content");
      return $(content).children(".popover-body").html();
    },
  });
});

</script>
</head>

<body>
	
<!-- Popover #1 -->
<table border="1">
	<tr>
        <td><a href="#" class="btn btn-primary" tabindex="0" data-toggle="popover" data-trigger="focus" data-popover-content="#a1" data-placement="right">Popover Example</a></td>
	</tr>
</table>





<!-- Content for Popover #1 -->
<div id="a1" class="hidden">
	<div class="popover-body">
		<table style="width:100%" border="1">
            <tr>
                <td>Jill</td>
                <td>Smith</td>
                <td>50</td>
            </tr>
            <tr>
                <td><a href='https://www.google.com.tw' target='view_window'>123</a></td>
                <td>Jackson</td>
                <td>94</td>
      		</tr>
            <tr>
                <td>John</td>
                <td>Doe</td>
                <td>80</td>
            </tr>
    	</table>
  </div>
</div>
</body>
</html>