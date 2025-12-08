<?php

require_once __DIR__ . '/../config/configRoute.php';
require_once __DIR__ . '/../config/ThongKeDB.php';

$db = new ConnectDb();
$thongke = new ThongKeManager($db);

$tongkhoahoc = $thongke->getTongKhoaHoc();
$tonghocvien = $thongke->getTongHocVien();
$tonggiangvien = $thongke->getTongGiangvien();
$doanhthu = $thongke->getDoanhthu();

$table_khoaHoc_phobien = $thongke->getKhoahocPhobien();



include __DIR__ . '/../views/admin/thongke_view.php';