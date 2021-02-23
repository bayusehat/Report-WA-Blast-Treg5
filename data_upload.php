<?php
require 'init.php';
$db = DB::getInstance();
$requestData = $_REQUEST;
$columns = array( 
	0 => 'id_upspeed_upload', 
	1 => 'periode_dapros',
	2 => 'created_upload'
);
$searchVal = $requestData['search']['value'];
$total = "SELECT * FROM UPSPEED_UPLOAD";
$hasil_total = $db->runQuery($total)->fetchAll();
$totalData = count($hasil_total);
$totalFiltered = $totalData;

if( !empty($requestData['search']['value']) ) {
    $query = "SELECT * FROM UPSPEED_UPLOAD
        WHERE ND_INTERNET LIKE '%$searchVal%' 
        OR PERIODE_DAPROS LIKE '%$searchVal%'
        ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".intval($requestData['length'])." OFFSET ".intval($requestData['start'])."   ";
    $hasil = $db->runQuery($query)->fetchAll();
    // $totalFiltered = count($hasil);
}else{
    $query = "SELECT * FROM UPSPEED_UPLOAD
        ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".intval($requestData['length'])." OFFSET ".intval($requestData['start'])."   ";
    $hasil = $db->runQuery($query)->fetchAll();
    // $totalFiltered = $totalData;
}

$data = [];
foreach ($hasil as $i => $v) {
    $nestedData = [];
    $nestedData[] = ++$requestData['start'];
    $nestedData[] = $v['nd_internet'];
    $nestedData[] = $v['periode_dapros'];

    $data[] = $nestedData;
}

$json_data = [
    'draw' => intval($requestData['draw']),
    'recordsTotal' => intval($totalData),
    'recordsFiltered' => intval($totalFiltered),
    'data' => $data 
];

echo json_encode($json_data);
?>