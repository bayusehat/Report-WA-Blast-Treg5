<?php
require 'init.php';
$db = DB::getInstance();

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '01';
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$periode = $tahun.'-'.$bulan;

$query = "
SELECT A.WITEL,";
for ($v=1; $v <= 31 ; $v++) { 
   if($v < 10){
       $v = '0'.$v;
   }else{
       $v = $v;
   }
   if($v === 31){
    $query .= "COALESCE(AGREE$v,0)A$v,COALESCE(DP$v,0)U$v";
   }else{
      $query .= "COALESCE(AGREE$v,0)A$v,COALESCE(DP$v,0)U$v,"; 
   }
}
$query.= "
FROM(
 SELECT WITEL,";

 for ($x=1; $x <= 31 ; $x++) { 
    if($x < 10){
        $x = '0'.$x;
    }else{
        $x = $x;
    }
    if($x === 31){
      $query .= "SUM(CASE WHEN DA = '$x' AND STATUS_OPLANG = 1 OR STATUS_OPLANG = 2 OR STATUS_OPLANG = 3 THEN 1 ELSE 0 END) AGREE$x";
    }else{
       $query .= "SUM(CASE WHEN DA = '$x' AND STATUS_OPLANG = 1 OR STATUS_OPLANG = 2 OR STATUS_OPLANG = 3 THEN 1 ELSE 0 END) AGREE$x,"; 
    }
 }

 $query .= "
 FROM(
  SELECT AREA WITEL, TO_CHAR(CREATED,'DD') DA, NOMOR_INET, STATUS_OPLANG
  FROM UPSPEED_NEW A
  LEFT JOIN AREAS B ON A.CWITEL = B.CWITEL
  WHERE CREATED::TEXT LIKE '%$periode%'
 )A
 GROUP BY WITEL 
 ORDER BY WITEL
) A LEFT JOIN (
 SELECT WITEL, ";
 for ($y=1; $y <= 31 ; $y++) { 
    if($y < 10){
        $y = '0'.$y;
    }else{
        $y = $y;
    }
    if($y === 31){
      $query .= "SUM(CASE WHEN DU = '$y' THEN 1 ELSE 0 END) DP$y";
    }else{
       $query .= "SUM(CASE WHEN DU = '$y' THEN 1 ELSE 0 END) DP$y,"; 
    }
 }    

 $query.= "
 FROM(
  SELECT AREA WITEL, TO_CHAR(CREATED_DAPROS,'DD') DU, ND_INTERNET
  FROM UPSPEED_UPLOAD A
  LEFT JOIN AREAS B ON A.CWITEL::INTEGER = B.CWITEL
  WHERE CREATED_DAPROS::TEXT LIKE '%$periode%'
 ) B 
 GROUP BY WITEL
 ORDER BY WITEL
) B ON A.WITEL = B.WITEL";

$data = $db->runQuery($query)->fetchAll();
?>

<!-- head -->
    <?php
        require 'view/head.php';
    ?>
    <style>
        .center{
            text-align:center;
        }
        .rght{
            text-align:right;
        }
    </style>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">

                <div class="container-fluid">
                    <div class="page-title-box">

                        <div class="row align-items-center ">
                            <div class="col-md-8">
                                <div class="page-title-box">
                                    <h4 class="page-title">REPORT</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="javascript:void(0);">CCare</a>
                                        </li>
                                        <li class="breadcrumb-item active">Report WA Pertanggal</li>
                                    </ol>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="float-right d-none d-md-block app-datepicker">
                                    <input type="text" class="form-control" data-date-format="MM dd, yyyy" readonly="readonly" id="datepicker">
                                    <i class="mdi mdi-chevron-down mdi-drop"></i>
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
                                        <form method="get" style="width:100%">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">TAHUN</label>
                                                    <select name="tahun" id="tahun" class="form-control">
                                                        <option value="2020" <?php if($tahun == '2020'){ echo 'selected';}else{echo '';}?>>2020</option>
                                                        <option value="2021" <?php if($tahun == '2021'){ echo 'selected';}else{echo '';}?>>2021</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                <label for="">BULAN</label>
                                                <select name="bulan" id="bulan" class="form-control">
                                                    <?php
                                                        for ($m=1; $m <= 12 ; $m++) { 
                                                            if($m<10){
                                                                $m = '0'.$m;
                                                            }else{
                                                                $m = $m;
                                                            }
                                                            if($bulan == $m){ $bs =  'selected';}else{ $bs = '';}
                                                            echo '<option value="'.$m.'" '.$bs.'>'.$m.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">SC</label>
                                                    <input type="submit" class="btn btn-success" value="SUMBIT">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <table class="table table-sm table-bordered table-striped" id="reportPertanggal">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="center">WITEL</th>
                                                        <?php
                                                            for ($i=1; $i <= 31; $i++) { 
                                                                echo '<th colspan="2" class="center">'.$i.'</th>';
                                                            }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                            for ($k=1; $k <= 31; $k++) {
                                                        ?>
                                                                <th>AGREE</th>
                                                                <th>DAPROS</th>
                                                        <?php 
                                                            }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach ($data as $q => $c) {
                                                    ?>
                                                    <tr>
                                                        <td><?= $c['witel'];?></td>
                                                        <?php
                                                            for ($b=1; $b <= 31 ; $b++) {
                                                                if($b<10){
                                                                    $b = '0'.$b;
                                                                }else{
                                                                    $b = $b;
                                                                } 
                                                        ?>
                                                            <td class="rght"><?= number_format($c['a'.$b]);?></td>
                                                            <td class="rght"><?= number_format($c['u'.$b]);?></td>
                                                        <?php
                                                            }
                                                        ?>
                                                    </tr>
                                                    <?php
                                                        }
                                                    ?>
                                                </tbody>
                                                <!-- <tfoot>
                                                    <tr>
                                                        <th colspan="2">TOTAL</th>
                                                        <th><?= number_format($tdapros);?></th>
                                                        <th><?= number_format($tagree);?></th>
                                                        <th><?= number_format($tsukses);?></th>
                                                        <th><?= number_format($tanomali);?></th>
                                                        <th><?= number_format($tgagal);?></th>
                                                        <th><?= number_format($tbelum);?></th>
                                                        <th><?= number_format($tach,2);?> %</th>
                                                    </tr>
                                                </tfoot> -->
                                            </table>
                                        </div>
                                    </div>
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

<!-- Foot -->
<?php
    require 'view/foot.php';
?>