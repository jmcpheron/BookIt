<?
$query = $_GET['q'];
$og_query = $query;
$query = str_replace(" ", "*", $query);
@include "raw_ldap_search.php";
echo json_encode( $info );
?>
