<?php
require_once 'config/koneksi.php';

// 1. Handle potential missing ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$error_msg = "";

// 2. Fetch Data Securely using Prepared Statements
$stmt = $conn->prepare("SELECT * FROM tb_absensi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Data not found!");
}

// 3. Handle Update Logic
if (isset($_POST['update'])) {
    $nama = $_POST['nama_siswa'];
    $kls  = $_POST['kelas'];
    $ket  = $_POST['keterangan'];
    $tgl  = $_POST['tanggal'];

    // Update using Prepared Statement
    $update_stmt = $conn->prepare("UPDATE tb_absensi SET nama_siswa=?, kelas=?, keterangan=?, tanggal=? WHERE id=?");
    $update_stmt->bind_param("ssssi", $nama, $kls, $ket, $tgl, $id);

    if ($update_stmt->execute()) {
        // Redirect with a success flag
        header("Location: index.php?status=updated");
        exit;
    } else {
        $error_msg = "Failed to update data. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Attendance - Modern System</title>
    <!-- Tailwind CSS for Modern UI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <!-- Back Button -->
        <a href="index.php" class="inline-flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors mb-4 group">
            <i class="fas fa-arrow-left mr-2 transition-transform group-hover:-translate-x-1"></i>
            Back to Dashboard
        </a>

        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 overflow-hidden border border-slate-100">
            <!-- Header Section -->
            <div class="bg-indigo-600 px-8 py-6">
                <h2 class="text-white text-xl font-bold flex items-center gap-3">
                    <i class="fas fa-user-pen"></i>
                    Edit Attendance Record
                </h2>
                <p class="text-indigo-100 text-sm mt-1 opacity-80">Update details for student ID: #<?= htmlspecialchars($id) ?></p>
            </div>

            <!-- Form Section -->
            <form method="POST" class="p-8 space-y-6">
                
                <?php if($error_msg): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm flex items-center gap-2">
                    <i class="fas fa-circle-exclamation"></i> <?= $error_msg ?>
                </div>
                <?php endif; ?>

                <!-- Student Name -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Student Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="nama_siswa" 
                            value="<?= htmlspecialchars($data['nama_siswa']); ?>" 
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700"
                            required>
                    </div>
                </div>

                <!-- Class -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Class / Grade</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-graduation-cap"></i>
                        </span>
                        <input type="text" name="kelas" 
                            value="<?= htmlspecialchars($data['kelas']); ?>" 
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-slate-700"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Status</label>
                        <select name="keterangan" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-700 appearance-none cursor-pointer">
                            <?php 
                            $options = ['Hadir', 'Izin', 'Sakit', 'Alfa'];
                            foreach($options as $opt) {
                                $selected = ($data['keterangan'] == $opt) ? 'selected' : '';
                                echo "<option value='$opt' $selected>$opt</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Date</label>
                        <input type="date" name="tanggal" 
                            value="<?= $data['tanggal']; ?>" 
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-700">
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-4">
                    <button name="update" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-indigo-200 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-4 italic">
                        Last record update: <?= date('d M Y') ?>
                    </p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>