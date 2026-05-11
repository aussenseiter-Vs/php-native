<?php
require_once'config/koneksi.php';

$id=$_GET['id'];

$query="DELETE FROM tb_absensi WHERE id='$id'";

if ($conn->query($query)) {
header("Location: index.php");
}else {
echo"Gagal hapus";
}