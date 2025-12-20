<?php
// Test script: insert -> update -> delete riwayat_pelanggan
require_once __DIR__ . '/../../../configurasi/koneksi.php';
$mysqli = $GLOBALS["___mysqli_ston"];

echo "Starting riwayat test...\n";

// 1) Insert test pelanggan
$nm = 'TEST_PEL_' . time();
$tlp = '0812345678';
$alamat = 'Alamat test';
$ket = 'Ket test';
$q = "INSERT INTO pelanggan (nm_pelanggan, tlp_pelanggan, alamat_pelanggan, ket_pelanggan) VALUES ('" . mysqli_real_escape_string($mysqli, $nm) . "', '" . mysqli_real_escape_string($mysqli, $tlp) . "', '" . mysqli_real_escape_string($mysqli, $alamat) . "', '" . mysqli_real_escape_string($mysqli, $ket) . "')";
if (!mysqli_query($mysqli, $q)) {
    echo "Failed to insert pelanggan: " . mysqli_error($mysqli) . "\n";
    exit(1);
}
$id_p = mysqli_insert_id($mysqli);
echo "Inserted pelanggan id: $id_p\n";

// 2) Insert riwayat
$tgl = date('Y-m-d');
$diagnosa = 'Diagnosis test';
$tindakan = 'Tindakan test';
$followup = 'Followup test';
$q = "INSERT INTO riwayat_pelanggan (id_pelanggan, tgl, diagnosa, tindakan, followup, created_at) VALUES ($id_p, '$tgl', '" . mysqli_real_escape_string($mysqli, $diagnosa) . "', '" . mysqli_real_escape_string($mysqli, $tindakan) . "', '" . mysqli_real_escape_string($mysqli, $followup) . "', NOW())";
if (!mysqli_query($mysqli, $q)) {
    echo "Failed to insert riwayat: " . mysqli_error($mysqli) . "\n";
    // cleanup pelanggan
    mysqli_query($mysqli, "DELETE FROM pelanggan WHERE id_pelanggan='$id_p'");
    exit(1);
}
$id_r = mysqli_insert_id($mysqli);
echo "Inserted riwayat id: $id_r\n";

// 3) Read inserted riwayat
$res = mysqli_query($mysqli, "SELECT * FROM riwayat_pelanggan WHERE id='$id_r'");
$row = mysqli_fetch_assoc($res);
if ($row) {
    echo "Riwayat row:\n";
    print_r($row);
} else {
    echo "Inserted riwayat not found.\n";
}

// 4) Update riwayat
$new_diag = 'Diagnosis updated';
if (!mysqli_query($mysqli, "UPDATE riwayat_pelanggan SET diagnosa='" . mysqli_real_escape_string($mysqli, $new_diag) . "' WHERE id='$id_r'")) {
    echo "Failed to update riwayat: " . mysqli_error($mysqli) . "\n";
}
$res = mysqli_query($mysqli, "SELECT diagnosa FROM riwayat_pelanggan WHERE id='$id_r'");
$row = mysqli_fetch_assoc($res);
echo "After update diagnosa: " . ($row['diagnosa'] ?? '(not found)') . "\n";

// 5) Delete riwayat
mysqli_query($mysqli, "DELETE FROM riwayat_pelanggan WHERE id='$id_r'");
$res = mysqli_query($mysqli, "SELECT count(*) as cnt FROM riwayat_pelanggan WHERE id='$id_r'");
$cnt = mysqli_fetch_assoc($res)['cnt'];
echo "Deleted riwayat exists count: $cnt\n";

// Cleanup test pelanggan
mysqli_query($mysqli, "DELETE FROM pelanggan WHERE id_pelanggan='$id_p'");
echo "Cleanup done. Test completed successfully.\n";
exit(0);
