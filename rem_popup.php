<?
include("common.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Reports</title>
<?
echo $common_css;
?>
<script type='text/javascript' src='<?echo $jquery_path;?>'></script>
<script type="text/javascript">
function notify_desktop(title, info){

  n = window.webkitNotifications.createNotification('favicon.ico', title, info);

  n.show();

};

function showSomething(){
  notify_desktop('Jason McPheron has just clocked in', 'He is 3 minutes early for his appointment');
};
function showSomethingElse(){
  notify_desktop('Fred Rocha has just clocked in', 'He is 2 hours late for his appointment');
};


$(document).ready(function() {


  if(window.webkitNotifications){
    if(window.webkitNotifications.checkPermission() != 0){
      $("#notify").append("<button class='btn' id='allow-notify'>Allow Desktop Notifications</button>"); 
    }
  }

$("#allow-notify").click( function(){
  window.webkitNotifications.requestPermission();
  $("#notify").hide();
});

$("#show").click( function(){
 notify_desktop('4', '3') ;
});

});
</script>
</head>
<body>

<div class="row">

  <div class="span8 offset4">
  <h1>HTML5 Notification Example Browser Permission</h1>
  <div id="notify"></div>
<h3><a href="rem_show.php">Sample</a></h3>
  </div>
</div>
</body>
</html>
