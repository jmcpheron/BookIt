<?
include("common.php");
include_once("session.php");
include("permission_functions.php");
$date = fixString( $_GET['date'] );
$time = fixString( $_GET['time'] );

if($time == ""){
  $all_day = " checked ";
}

if($_POST){
  echo "Process request";
  exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $site_title;?> </title>
<script type='text/javascript' src='<?echo $jquery_path;?>'></script>
<script type="text/javascript" src="<?echo $jquery_color_picker_path;?>"></script> 
<link rel='stylesheet' type='text/css' href='<?echo $jquery_color_picker_css;?>' />
<script type='text/javascript'>

$(document).ready(function() {
$('.jquery-colour-picker-example select').colourPicker({
    ico:    '<?echo $jquery_color_picker_gif;?>',
    title:    false
});
  $(".jquery-colour-picker-example").change(function() {
    var this_ou_role = $(this).children().attr("name");
    var this_color = $(this).children().val();
    $.post("update_user_settings.php", { "key": 'calendar_color', "value": this_ou_role, "sub_value": this_color },
      function(data){
      if(data.status == 'error'){
        window.location = "<?echo $site_root;?>";
      }
      if(data.status == 'success'){
        //Reshow if previously hidden
        $("div#top_notifications").show();
        $("div#top_notifications").html("Prefrences saved. \"" + data.key + "\" set to \"" + data.value + "\"" + " -- " + data.sub_value );
        $("div#top_notifications").addClass("notify");
        $("div#top_notifications").addClass("alert-message");
        $("div#top_notifications").delay(3000).fadeOut(500);
      }
     }, "json");
        //return false;
  });

  //For tabs
  $(".tab_content").hide();
  $("ul.tabs li:first").addClass("active").show(); //Activate first tab
  $(".tab_content:first").show(); //Show first tab content

  //On Click Event
  $("ul.tabs li").click(function() {

    $("ul.tabs li").removeClass("active"); //Remove any "active" class
    $(this).addClass("active"); //Add "active" class to selected tab
    $(".tab_content").hide(); //Hide all tab content

    var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
    $(activeTab).fadeIn(); //Fade in the active ID content
    //return false;
  });

  //On load
  if(window.location.hash){
    var this_hash = window.location.hash + '-tab';

    $("ul.tabs li").removeClass("active"); //Remove any "active" class
    $(this_hash).addClass("active"); //Add "active" class to selected tab
    $(".tab_content").hide(); //Hide all tab content

    var activeTab = $(this_hash).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
    $(activeTab).fadeIn(); //Fade in the active ID content
    //return false;
  }
  //For radio buttons
  $("input:radio").click(function() {
    var full_role = $(this).attr('id');
    var css_key = $(this).attr('name');
    $.post("update_user_settings.php", { "key": css_key, "value": full_role },
      function(data){
      if(data.status == 'error'){
        window.location = "<?echo $site_root;?>";
      }
      if(data.status == 'success'){
        //Reshow if previously hidden
        $("div#top_notifications").show();
        $("div#top_notifications").html("Prefrences saved. \"" + data.key + "\" set to \"" + data.value + "\"" );
        $("div#top_notifications").addClass("notify");
        $("div#top_notifications").addClass("alert-message");
        $("div#top_notifications").delay(3000).fadeOut(500);
      }
     }, "json");
  });

  //For Select Options (drop-downs)
  $("select#start_hour").change(function() {
    var full_role = $(this).val();
    var css_key = $(this).attr('name');
    $.post("update_user_settings.php", { "key": css_key, "value": full_role },
      function(data){
      if(data.status == 'error'){
        window.location = "<?echo $site_root;?>";
      }
      if(data.status == 'success'){
        //Reshow if previously hidden
        $("div#top_notifications").show();
        $("div#top_notifications").html("Prefrences saved. \"" + data.key + "\" set to \"" + data.value + "\"" );
        $("div#top_notifications").addClass("notify");
        $("div#top_notifications").addClass("alert-message");
        $("div#top_notifications").delay(3000).fadeOut(500);
      }
     }, "json");
  });


});
</script>
<?
echo $common_css;
?>
<link rel='stylesheet' type='text/css' href='css/form_override.css'>
</head>
<body>
<div class="container">
<div class="content main">
<?
drawHeader($id);
//TODO Turn this into a more generic function
//Get current value
echo "\n";

echo "<div class=\"blah span14\">\n";

?>
<ul class="tabs">
  <li id="settings-tab"><a href="#settings">Settings</a></li>
  <li id="roles-tab"><a href="#roles">Roles</a><li>
  <li id="admin-tab"><a href="#admin">Admin</a><li>
</ul>
<?

echo "  <div class=\"span5 tab_content\" id=\"settings\">\n";
echo "<form class=\"form-stacked\">\n";
//Default View
echo "<h2>Default View</h2>";
$current_default_view = getUserSettingValue($id, 'default_view');
  
  foreach($view_array as $view){
    $full_role = $item['ou_code']."/".$role['role'];
    //echo "<span class=\"roles\" id=\"".$item['ou_code']."/".$role['role']."\">+</span>";
    echo "<label>";
    echo "<input type=\"radio\" name=\"default_view\" value=\"$view\" id=\"$view\" ";
      if($view == $current_default_view){
        echo "CHECKED";
      }
    echo ">\n";
    echo "<span>$view</span>";
    echo "</label>\n";
    //echo "<br />\n";
  }


//Default Time Slot Size
echo "<h2>Default Time Slot Size</h2>";
$current_default_view = getUserSettingValue($id, 'slot_size');
  
  foreach($slot_array as $slot){
    $full_role = $item['ou_code']."/".$role['role'];
    //echo "<span class=\"roles\" id=\"".$item['ou_code']."/".$role['role']."\">+</span>";
    echo "<label>";
    echo "<input type=\"radio\" name=\"slot_size\" value=\"$slot\" id=\"$slot\" ";
      if($slot == $current_default_view){
        echo "CHECKED";
      }
    echo ">\n";
    echo "<span>$slot</span>";
    echo "</label>\n";
    //echo "<br />\n";
  }

//Default Start Hour
echo "<h2>Default Start Hour</h2>";
$current_default_view = getUserSettingValue($id, 'start_hour');
  
  echo "<select name=\"start_hour\" id=\"start_hour\" >";
  $c = 1;
  while($c <= 24){
    if($c > 12){
      $display_hour = ($c - 12).":00 PM";
    }
    if($c < 12){
      $display_hour = $c.":00 AM";
    }
    if($c == 12){
      $display_hour = $c.":00 noon";
    }
    if($c == 24){
      $display_hour = ($c - 12).":00 AM";
    }

    $full_role = $item['ou_code']."/".$role['role'];
    echo "<option value=\"$c\" ";
      if($c == $current_default_view){
        echo "SELECTED ";
      }
    echo ">$display_hour\n";
    $c++;
  }
  echo "</select>";
?>
<br /><br />
</form>
  </div>

  <div class="span8 tab_content" id="roles">
<?
echo "<form class=\"form-stacked\" id=\"roles\">\n";
echo "<h2>Default Role</h2>\n";
$current_default_role = getUserSettingValue($id, 'default_role');

$ous = getOus($id);

if($ous){
foreach($ous as $item){
  $ou_long_name[$item['ou_code']] = $item['long_name'];
  $roles = getRoles($id, $item['ou_code']);
  echo "<h3>".$item['long_name'];
  echo "</h3>\n";  
  echo "<br />\n";
  
  foreach($roles as $role){
    $full_role = $item['ou_code']."/".$role['role'];
    echo "<div class=\"row \">";
    $color = getUserSettingValue($id, 'calendar_color', $full_role);
    echo "<input type=\"radio\" name=\"default_role\" value=\"$full_role\" class=\"roles\" id=\"$full_role\" ";
      if($full_role == $current_default_role){
        echo "CHECKED ";
      }
    echo "/>\n";
    echo $role['long_name'];
    echo "<label for=\"$full_role\">\n";
    echo "</label>";
    echo "<span class=\"help-inline\"><a href=\"role_settings.php?ou=".$item['ou_code']."&role=".$role['role']."\">Advanced Settings</a></span>\n";
echo '
<div class="jquery-colour-picker-example">
        <select name="'.$full_role.'" id="'.$full_role.'" class="picker">
';
if($color){
           echo  "<option value=\"$color\" selected=\"selected\">#$color</option>";
}
echo '
            <option value="333399">#333399</option>
            <option value="ffccc9">#ffccc9</option>
            <option value="ffce93">#ffce93</option>
            <option value="fffc9e">#fffc9e</option>
            <option value="ffffc7">#ffffc7</option>
            <option value="9aff99">#9aff99</option>
            <option value="96fffb">#96fffb</option>
            <option value="cdffff">#cdffff</option>
            <option value="cbcefb">#cbcefb</option>
            <option value="cfcfcf">#cfcfcf</option>
            <option value="fd6864">#fd6864</option>
            <option value="fe996b">#fe996b</option>
            <option value="fffe65">#fffe65</option>
            <option value="fcff2f">#fcff2f</option>
            <option value="67fd9a">#67fd9a</option>
            <option value="38fff8">#38fff8</option>
            <option value="68fdff">#68fdff</option>
            <option value="9698ed">#9698ed</option>
            <option value="c0c0c0">#c0c0c0</option>
            <option value="fe0000">#fe0000</option>
            <option value="f8a102">#f8a102</option>
            <option value="ffcc67">#ffcc67</option>
            <option value="f8ff00">#f8ff00</option>
            <option value="34ff34">#34ff34</option>
            <option value="68cbd0">#68cbd0</option>
            <option value="34cdf9">#34cdf9</option>
            <option value="6665cd">#6665cd</option>
            <option value="9b9b9b">#9b9b9b</option>
            <option value="cb0000">#cb0000</option>
            <option value="f56b00">#f56b00</option>
            <option value="ffcb2f">#ffcb2f</option>
            <option value="ffc702">#ffc702</option>
            <option value="32cb00">#32cb00</option>
            <option value="00d2cb">#00d2cb</option>
            <option value="3166ff">#3166ff</option>
            <option value="6434fc">#6434fc</option>
            <option value="656565">#656565</option>
            <option value="9a0000">#9a0000</option>
            <option value="ce6301">#ce6301</option>
            <option value="cd9934">#cd9934</option>
            <option value="999903">#999903</option>
            <option value="009901">#009901</option>
            <option value="329a9d">#329a9d</option>
            <option value="3531ff">#3531ff</option>
            <option value="6200c9">#6200c9</option>
            <option value="343434">#343434</option>
            <option value="680100">#680100</option>
            <option value="963400">#963400</option>
            <option value="986536">#986536</option>
            <option value="646809">#646809</option>
            <option value="036400">#036400</option>
            <option value="34696d">#34696d</option>
            <option value="00009b">#00009b</option>
            <option value="303498">#303498</option>
            <option value="000000">#000000</option>
            <option value="330001">#330001</option>
            <option value="643403">#643403</option>
            <option value="663234">#663234</option>
            <option value="013300">#013300</option>
            <option value="003532">#003532</option>
            <option value="010066">#010066</option>
            <option value="340096">#340096</option>
        </select>
</div>
';
    echo "</div>";
    echo "<br />\n";
  }
}
}
echo "</form>";
?>
  </div>

  <div class="span8 tab_content" id="admin">
<?
$results = listOusWithPermission($id, 'admin');
  foreach($results as $ou){
    echo "<h3>".$ou_long_name[$ou['ou']]."</h3>";

    $roles = listRolesInOu($ou['ou']);
    foreach($roles as $role){
      echo "<h4>".$role['long_name']."</h4>";
      echo "<a href=\"permissions.php?ou=".$ou['ou']."&role=".$role['role']."\">Permissions</a> | ";
      echo "<a href=\"users.php?ou=".$ou['ou']."&role=".$role['role']."\">Users</a> | ";
    }
  }
?> 
  </div>

  <div class="row span14">
  <a href="index.php" class="btn primary">Done</a>
  </div>
</div>

</div>
</div>
</body>
</html>
