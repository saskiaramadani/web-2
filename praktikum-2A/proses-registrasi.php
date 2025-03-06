<?php 

require_once "data-form-regis.php";

$nim = isset($_POST['nim']) ? $_POST['nim'] : '';
$nama = isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : '';
$jk = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '';
$prodi = isset($_POST['program_studi']) ? $_POST['program_studi'] : '';
$skill_pilihan = isset($_POST['skills']) ? $_POST['skills'] : [];
$email = isset($_POST['email']) ? $_POST['email'] : '';
$domisili = isset($_POST['domisili']) ? $_POST['domisili'] : '';


$skor_skill = skor_skill($skill_pilihan, $ar_skill);
$kategori_skill = kategori_skill($skor_skill);

function skor_skill($skill_pilihan, $ar_skill){
    $skor = 0;
    foreach ($skill_pilihan as $skill){
        if(isset($ar_skill[$skill])){
            $skor += $ar_skill[$skill];
        }
    }
    return $skor;
}
function kategori_skill($skor_skill = 0){
    if ($skor_skill == 0) {
        return "Tidak Ada";
    } elseif ($skor_skill <= 40) {
        return "Kurang";
    } elseif ($skor_skill <= 60) {
        return "Cukup";
    } elseif ($skor_skill <= 100) {
        return "Baik";
    } elseif ($skor_skill <= 150) {
        return "Sangat Baik";
    } else {
        return "Tidak Terkategori";
    }
}
?>