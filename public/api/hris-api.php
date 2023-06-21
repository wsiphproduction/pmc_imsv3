<?php

$conn_d['davao']['type'] = 'sqlsrv';
$conn_d['davao']['host'] = '192.155.86.62';
$conn_d['davao']['name'] = 'mssqlws1ph2_dimshris';
$conn_d['davao']['uname'] = 'mssqlws1ph2_dimshriss';
$conn_d['davao']['pword'] = '@Temp123!';

$conn_a['agusan']['type'] = 'sqlsrv';
$conn_a['agusan']['host'] = '192.155.86.62';
$conn_a['agusan']['name'] = 'mssqlws1ph2_dimshris';
$conn_a['agusan']['uname'] = 'mssqlws1ph2_dimshriss';
$conn_a['agusan']['pword'] = '@Temp123!';

$array_emp = array();

$pdoempdata = new PDO($conn_a['agusan']['type'].":server=".$conn_a['agusan']['host'].";Database=".$conn_a['agusan']['name'], $conn_a['agusan']['uname'], $conn_a['agusan']['pword']);
$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//$empdatasql = $pdoempdata->prepare("SELECT EmpID, FullName FROM ViewHREmpMaster WHERE Active = 1");

$empdatasql = $pdoempdata->prepare("SELECT e.EmpID, e.FullName,d.DeptDesc, d.deptid FROM ViewHREmpMaster e left join hrdepartment d on d.deptid=e.deptid WHERE e.Active = 1");

$empdatasql->execute();
$empdatasql->setFetchMode(PDO::FETCH_ASSOC);

for($i=0; $rowempdata = $empdatasql->fetch(); $i++){   
	$array_emp[] .= str_replace(',',' ',$rowempdata['FullName']).' : '.$rowempdata['DeptDesc'];
}


$pdoempdata = new PDO($conn_d['davao']['type'].":server=".$conn_d['davao']['host'].";Database=".$conn_d['davao']['name'], $conn_d['davao']['uname'], $conn_d['davao']['pword']);
$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$empdatasql = $pdoempdata->prepare("SELECT e.EmpID, e.FullName,d.DeptDesc, d.deptid FROM ViewHREmpMaster e left join hrdepartment d on d.deptid=e.deptid WHERE e.Active = 1");

$empdatasql->execute();
$empdatasql->setFetchMode(PDO::FETCH_ASSOC);

for($i=0; $rowempdata = $empdatasql->fetch(); $i++){     
	$array_emp[] .= str_replace(',',' ',$rowempdata['FullName']).' : '.$rowempdata['DeptDesc'];
}

	echo json_encode($array_emp);
?>

