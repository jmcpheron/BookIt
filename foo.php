<?
include("common.php");
include_once("session.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $site_title;?> </title>
<script type='text/javascript' src='<?echo $jquery_path;?>'></script>
<script type='text/javascript' src='<?echo $jquery_chosen;?>'></script>
<script type='text/javascript'>
$(document).ready(function() {
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({allow_single_deselect:true});
});
</script>
<?
echo $common_css;
?>
</head>
<body>
<div class="container">
<div class="content main">
<?
drawHeader($id);
?>
<div>
              <select data-placeholder="Choose a Country" class="chzn-select" multiple style="width:350px;" tabindex="4">
                <option value=""></option> 
                <option value="United States">United States</option> 
                <option value="United Kingdom">United Kingdom</option> 
                <option value="Afghanistan">Afghanistan</option> 
                <option value="Albania">Albania</option> 
                <option value="Algeria">Algeria</option> 
                <option value="American Samoa">American Samoa</option> 
                <option value="Andorra">Andorra</option> 
                <option value="Angola">Angola</option> 
                <option value="Anguilla">Anguilla</option> 
                <option value="Antarctica">Antarctica</option> 
                <option value="Antigua and Barbuda">Antigua and Barbuda</option> 
                <option value="Argentina">Argentina</option> 
                <option value="Armenia">Armenia</option> 
</select>

</div>
</div>
</body>
</html>
