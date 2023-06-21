<?php
$my = mysqli_connect("172.16.20.48","root","root","indent_db");
$id = $_GET['id'];


if($_GET['d'] == 'po'){
	$po = mysqli_fetch_array(mysqli_query($my,"select * from po where id='".$id."'"));
	$images = explode("|",$po['files']);
	$data='';
	foreach($images as $i){
		$data.='<tr><td><a href="http://172.16.20.48/indent/images/files/'.$i.'">'.$i.'</a></td></tr>';
	}
	echo $data;
}

?>