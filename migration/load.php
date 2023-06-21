<?php
$serverName = "172.16.11.87\DEV";
$connectionInfo = array( "Database"=>"indent_db_clean", "UID"=>"sa", "PWD"=>"@Temp123!" );
$ms = sqlsrv_connect($serverName, $connectionInfo);
$my = mysqli_connect("172.16.20.48","root","root","indent_db");

function datec($i){
	if($i=='0000-00-00'){
		return NULL;
	}
	else{
		return $i;
	}
}

function datetimec($i){
	if($i=='0000-00-00 00:00:00'){
		return NULL;
	}
	else{
		return $i;
	}
}


/*
//Update Status
$poq = sqlsrv_query($ms,"select * from po");
while($p = sqlsrv_fetch_array($poq)){
	$payment = sqlsrv_fetch_array(sqlsrv_query($ms,"select sum(amount) as amt from payment_schedule where poId='".$p['id']."' and isPaid=1"));
	$dr = sqlsrv_fetch_array(sqlsrv_query($ms,"select sum(drrQty) as qty from drr where poNumber='".$p['id']."'"));

	$dx = $p['deliveryStatus'];
	$px = $p['paymentStatus'];
	$x = $p['status'];

	$ds = $p['deliveryStatus'];
	$ps = $p['paymentStatus'];
	$s = $p['status'];

	if($payment['amt'] >= $p['amount']){
		$ps = 'PAID';
	}

	if($dr['qty'] >= $p['qty']){
		$ds = 'DELIVERED';
	}

	if($ds == 'DELIVERED' && $ps == 'PAID'){
		$s = 'CLOSED';
	}

	if($dx <> $px){
		echo $p['id'];
	}
}

die();
*/

//supplier
$delete_supplier = sqlsrv_query($ms,"delete from supplier");
$turn_on = sqlsrv_query($ms,"SET IDENTITY_INSERT [supplier] ON;");
$supplier_q = mysqli_query($my,"select * from supplier");
while($supplier = mysqli_fetch_array($supplier_q)){
	$insert_supplier = sqlsrv_query($ms,"insert into supplier (id,name,contact,address,LTO_validity,Contact_Person,Supplier_Code,addedBy,addedDate) VALUES 
		('".$supplier['id']."','".$supplier['name']."','".$supplier['contact']."','".$supplier['address']."','".$supplier['LTO_validity']."','".$supplier['Contact_Person']."','".$supplier['Supplier_Code']."','MIGRATION',GETDATE())");
	
}
$turn_off = sqlsrv_query($ms,"SET IDENTITY_INSERT [supplier] OFF;");


//drr
$delete_drr = sqlsrv_query($ms,"delete from drr");
$drr_q = mysqli_query($my,"select * from drr");
while($drr = mysqli_fetch_array($drr_q)){
	$insert_drr = sqlsrv_query($ms,"insert into drr (poNumber,drr,drrAmount,drrQty,drrDate,invoice,[file],addedBy,addedDate) VALUES 
		('".$drr['poNumber']."','".$drr['drr']."','".$drr['drrAmount']."','".$drr['drrQty']."','".datec($drr['drrDate'])."','".$drr['invoice']."','".$drr['file']."','MIGRATION',GETDATE())");
	
}
$upd1 = sqlsrv_query($ms,"update drr set drrDate=NULL where drrDate='1900-01-01'");
//logistics
$delete_logistics = sqlsrv_query($ms,"delete from logistics");
$delete_remarks = sqlsrv_query($ms,"delete from remarks");
$logistics_q = mysqli_query($my,"select * from logistics");
while($logistics = mysqli_fetch_array($logistics_q)){
	$insert_logistics = "insert into logistics (expectedDeliveryDate,portArrivalDate,customClearedDate,poId,waybill,status,customStartDate,actualDeliveryDate) VALUES 
		('".datec($logistics['expectedDeliveryDate'])."','".datec($logistics['portArrivalDate'])."','".datec($logistics['customReleaseDate'])."','".$logistics['poId']."','".$logistics['waybill']."','','".datec($logistics['customReleaseDate'])."',NULL); SELECT SCOPE_IDENTITY()";	

		$resource=sqlsrv_query($ms, $insert_logistics); 
   		sqlsrv_next_result($resource); 
   		sqlsrv_fetch($resource); 
   		$lastins=sqlsrv_get_field($resource, 0); 

   	$remarks_q = mysqli_query($my,"select * from remarks where logisticId='".$logistics['id']."'");
	while($remarks = mysqli_fetch_array($remarks_q)){
		$insert_remarks = sqlsrv_query($ms,"insert into remarks (poId,remarks,logisticsId,addedBy,addedDate,created_at,updated_at) VALUES 
			('".$logistics['poId']."','".$remarks['remarks']."','".$lastins."','".$remarks['addedBy']."',
			'".$remarks['addedDate']."','".$remarks['addedDate']."','".$remarks['addedDate']."')");
		
	}

}
$upd1 = sqlsrv_query($ms,"update logistics set expectedDeliveryDate=NULL where expectedDeliveryDate='1900-01-01'");
$upd2 = sqlsrv_query($ms,"update logistics set portArrivalDate=NULL where portArrivalDate='1900-01-01'");
$upd3 = sqlsrv_query($ms,"update logistics set customClearedDate=NULL where customClearedDate='1900-01-01'");
$upd4 = sqlsrv_query($ms,"update logistics set customStartDate=NULL where customStartDate='1900-01-01'");

//payment schedules
$delete_payment = sqlsrv_query($ms,"delete from payment_schedule");
$payment_q = mysqli_query($my,"select * from payment_schedule");
while($payment = mysqli_fetch_array($payment_q)){
	$insert_payment = sqlsrv_query($ms,"insert into payment_schedule (
			[paymentDate]
           ,[amount]
           ,[poId]
           ,[isPaid]
           ,[actualPaymentDate]
           ,[remarks]
           ,[origPaymentDate]
           ,[files]
           ,[addedBy]
           ,[addedDate]) VALUES 
		('".datec($payment['paymentDate'])."','".$payment['amount']."','".$payment['poId']."','".$payment['isPaid']."','".datec($payment['actualPaymentDate'])."','".$payment['remarks']."','".datec($payment['origPaymentDate'])."','".$payment['files']."','MIGRATION',GETDATE())");
	
}
$upd1 = sqlsrv_query($ms,"update payment_schedule set paymentDate=NULL where paymentDate='1900-01-01'");
$upd2 = sqlsrv_query($ms,"update payment_schedule set actualPaymentDate=NULL where actualPaymentDate='1900-01-01'");
$upd3 = sqlsrv_query($ms,"update payment_schedule set origPaymentDate=NULL where origPaymentDate='1900-01-01'");

//po
$delete_po = sqlsrv_query($ms,"delete from po");
$po_q = mysqli_query($my,"select * from po");
$turn_on = sqlsrv_query($ms,"SET IDENTITY_INSERT [po] ON;");
while($po = mysqli_fetch_array($po_q)){

	$insert_po = sqlsrv_query($ms,"insert into po (id,
			[poNumber],[orderDate],[supplier],[itemCommodity],[currency],[amount],[terms],[status]
           ,[expectedCompletionDate],[expectedDeliveryDate],[closedDate],[closedBy],[poAmount],[rq]
           ,[qty],[addedBy],[addedDate],[updatedBy],[updateDate],[incoterms],[paymentStatus],[deliveryStatus]
           ,[origin],[destination_port],[suppliers_lead_time]
		) VALUES 
		('".$po['id']."',
		'".$po['poNumber']."','".datec($po['orderDate'])."','".$po['supplier']."','".$po['itemCommodity']."','".$po['currency']."','".$po['amount']."','".$po['terms']."',
		'".$po['status']."','".datec($po['expectedCompletionDate'])."','".datec($po['expectedDeliveryDate'])."','".datetimec($po['closedDate'])."','".$po['closedBy']."','".$po['poAmount']."','".$po['rq']."',
		'".$po['qty']."','".$po['addedBy']."','".$po['addedDate']."','".$po['addedBy']."','".$po['addedDate']."','".$po['incoTerm']."','UNPAID',
		'UNDELIVERED','".$po['countryOrigin']."',NULL,NULL
		)");
}
$turn_on = sqlsrv_query($ms,"SET IDENTITY_INSERT [po] OFF;");


//Update Status
$poq = sqlsrv_query($ms,"select * from po");
while($p = sqlsrv_fetch_array($poq)){
	$payment = sqlsrv_fetch_array(sqlsrv_query($ms,"select sum(amount) as amt from payment_schedule where poId='".$p['id']."' and isPaid=1"));
	$dr = sqlsrv_fetch_array(sqlsrv_query($ms,"select sum(drrQty) as qty from drr where poNumber='".$p['id']."'"));

	$ds = $p['deliveryStatus'];
	$ps = $p['paymentStatus'];
	$s = $p['status'];

	if($payment['amt'] >= $p['amount']){
		$ps = 'PAID';
	}

	if($dr['qty'] >= $p['qty']){
		$ds = 'DELIVERED';
	}

	if($ds == 'DELIVERED' && $ps == 'PAID'){
		$s = 'CLOSED';
	}

	$upd = sqlsrv_query($ms, "update po set paymentStatus='".$ps."',deliveryStatus='".$ds."',status='".$s."' where id='".$p['id']."'");
}

echo "succcess";

