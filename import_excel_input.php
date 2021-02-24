<?php
require 'init.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$db = DB::getInstance();

if(isset($_POST['submit'])){
    $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    
    if(isset($_FILES['file']['name']) && in_array($_FILES['file']['type'],$file_mimes)){
        $arr_file = explode('.',$_FILES['file']['name']);
        $extension = end($arr_file);

        if('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }

        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        for($i = 1;$i < count($sheetData);$i++)
        {
            $periode = $sheetData[$i]['0'];
            $nd      = $sheetData[$i]['1'];
            $cd      = $sheetData[$i]['2'];
            $dd      = $sheetData[$i]['3'];
            if($nd){
                $query = "INSERT INTO UPSPEED_UPLOAD (PERIODE_DAPROS,ND_INTERNET,CWITEL,CREATED_DAPROS) 
                VALUES ('$periode','$nd','$cd','$dd')";
                $db->runQuery($query);
            }
        }
        header("Location: import_excel.php?m=1"); 
    }else{
        echo '<script>
                windows.alert("Terjadi kesalahan, sila coba lagi!");
            </script>';
        header("Location: import_excel_input.php?m=2"); 
    }
}
?>
<?php
require 'view/head.php';
?>
    <div class="content-page">
        <!-- Start content -->
        <div class="content">

            <div class="container-fluid">
                <div class="page-title-box">

                    <div class="row align-items-center ">
                        <div class="col-md-8">
                            <div class="page-title-box">
                                <h4 class="page-title">Import Excel List</h4>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="javascript:void(0);">CCare</a>
                                    </li>
                                    <li class="breadcrumb-item active">Import Excel List</li>
                                </ol>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="float-right d-none d-md-block">
                                <a href="import_excel.php" class="btn btn-danger"><i class="dripicons-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page-title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <form method="post" id="formImport" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xl-12">
                                                    <div class="form-group">
                                                        <label for="">Input File</label>
                                                        <input type="file" name="file" id="file" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="submit" name="submit" id="submit" class="btn btn-success" value="Submit File">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- end row -->

                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- content -->
<?php
require 'view/foot.php';
?>