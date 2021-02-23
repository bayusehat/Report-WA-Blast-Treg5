<?php
require 'init.php';
$db = DB::getInstance();

$tgl = isset($_GET['tgl']) ? $_GET['tgl'] : null;

if($tgl){
    $tgl_report = "Y.CREATED::TEXT LIKE '%$tgl%'";
}else{
    $tgl_report = "1=1";
}

$query = "
    SELECT X.AREA,
    SUM(CASE WHEN Y.STATUS_OPLANG = 1 THEN 1 ELSE 0 END) SUKSES,
    SUM(CASE WHEN Y.STATUS_OPLANG = 2 THEN 1 ELSE 0 END) ANOMALI,
    SUM(CASE WHEN Y.STATUS_OPLANG = 3 THEN 1 ELSE 0 END) GAGAL,
    SUM(CASE WHEN Y.STATUS_OPLANG = 0 THEN 1 ELSE 0 END) BELUM_INPUT,
    COUNT(*) DAPROS
    FROM(
        SELECT A.*, B.PENAWARAN, C.SPEED_S UP_SPEED, B.PRICE + A.HARGA_ADDON - A.ABONEMEN HARGA, SUBSTRING(A.PERIODE_TAG,5,6) BULAN_TAGIHAN FROM(
        SELECT A.*,B.*,C.ADDON, CASE WHEN C.PRICE IS NOT NULL THEN C.PRICE ELSE 0 END HARGA_ADDON,D.AREA 
            FROM HP A 
            LEFT JOIN UPSPEED_MASTER B ON A.ND_INTERNET = B.ND_INTERNET
            LEFT JOIN ADDONS C ON B.ADDON_ID = C.ID
            LEFT JOIN AREAS D ON  B.CWITEL = D.CWITEL 
            WHERE B.USER_CALL = 56
    ) A LEFT JOIN OFFERS B ON A.OFFER_ID = B.ID
        LEFT JOIN SPEEDS C ON B.SPEED_ID = C.ID) X
    LEFT JOIN 
    UPSPEED_NEW Y ON X.HP = Y.NOMOR_HP
    WHERE $tgl_report
    GROUP BY X.AREA
    ORDER BY X.AREA
";

$data = $db->runQuery($query)->fetchAll();
?>

<!-- head -->
    <?php
        require 'view/head.php';
    ?>
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
                                        <li class="breadcrumb-item active">Report WA Blast</li>
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
                                        <div class="col-md-12 col-sm-12">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>WITEL</th>
                                                        <th>DAPROS</th>
                                                        <th>AGREE</th>
                                                        <th>SUKSES</th>
                                                        <th>ANOMALI</th>
                                                        <th>GAGAL</th>
                                                        <th>BELUM INPUT</th>                                                       
                                                        <th>ACH</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $tdapros = $tagree = $tsukses = $tanomali = $tgagal = $tbelum = $tach = 0;
                                                        foreach($data as $i => $v){
                                                        $agree = $v['sukses'] + $v['anomali'] + $v['gagal'];
                                                    ?>
                                                        <tr>
                                                            <td><?= ++$i; ?></td>
                                                            <td><?= $v['area'];?></td>
                                                            <td><?= number_format($v['dapros']);?></td>
                                                            <td><?= number_format($agree);?></td>
                                                            <td><?= number_format($v['sukses']); ?></td>
                                                            <td><?= number_format($v['anomali']); ?></td>
                                                            <td><?= number_format($v['gagal']); ?></td>
                                                            <td><?= number_format($v['belum_input']); ?></td>
                                                            <td><?= number_format($agree / (float) $v['dapros'] * 100,2);?> %</td>
                                                        </tr>
                                                    <?php
                                                        $tdapros += $v['dapros'];
                                                        $tagree += $agree;
                                                        $tsukses += $v['sukses'];
                                                        $tanomali += $v['anomali'];
                                                        $tgagal += $v['gagal'];
                                                        $tbelum += $v['belum_input'];
                                                        $tach += number_format($agree / (float) $v['dapros'] * 100,2);
                                                        }
                                                    ?>
                                                </tbody>
                                                <tfoot>
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
                                                </tfoot>
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