<?
include("common.php");
include_once("session.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo strip_tags($site_title);?> </title>
<?echo $common_js;?>
<script type='text/javascript'>
$(document).ready(function() {
<?echo $common_jquery;?>

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

<div class="row">
  <div class="span12">
  <?
  $sql = "
  SELECT long_name as name, ou_code as ou
  from ous
  ";
  $results = db_query($sql);
  
  $options = array(
  'links' => array(
    'name' => array('page' =>'ou.php',
                    'gets' => array('ou')
                   ),
  )
  );


  echo drawTableArray($results, $options );

?>
  </div>
</div>

</div>
</div>
</body>
</html>
