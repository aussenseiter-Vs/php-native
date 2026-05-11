<?php
require_once 'config/koneksi.php';

$error_msg = "";

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_siswa'];
    $kls  = $_POST['kelas'];
    $ket  = $_POST['keterangan'];
    $tgl  = $_POST['tanggal'];

    // Secure Insert using Prepared Statements
    $stmt = $conn->prepare("INSERT INTO tb_absensi (nama_siswa, kelas, keterangan, tanggal) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $kls, $ket, $tgl);

    if ($stmt->execute()) {
        header("Location: index.php?status=added");
        exit;
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Attendance Record</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <!-- Breadcrumb / Back Link -->
        <a href="index.php" class="inline-flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors mb-4 group">
            <i class="fas fa-arrow-left mr-2 transition-transform group-hover:-translate-x-1"></i>
            Back to Attendance List
        </a>

        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 overflow-hidden border border-slate-100">
            <!-- Header Section -->
            <div class="bg-indigo-600 px-8 py-6 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-white/20 rounded-full mb-3">
                    <i class="fas fa-plus text-white text-xl"></i>
                </div>
                <h2 class="text-white text-xl font-bold">New Attendance Entry</h2>
                <p class="text-indigo-100 text-sm mt-1 opacity-80">Add a new student record to the system</p>
            </div>

            <!-- Form Section -->
            <form method="POST" class="p-8 space-y-5">
                
                <?php if($error_msg): ?>
                <div class="bg-rose-50 text-rose-600 p-3 rounded-xl text-sm flex items-center gap-2 border border-rose-100">
                    <i class="fas fa-circle-exclamation"></i> <?= $error_msg ?>
                </div>
                <?php endif; ?>

                <!-- Student Name -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Student Full Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-user-tag"></i>
                        </span>
                        <input type="text" name="nama_siswa" 
                            placeholder="Enter full name..."
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700 placeholder:text-slate-300"
                            required>
                    </div>
                </div>

                <!-- Class -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Class / Group</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-school"></i>
                        </span>
                        <input type="text" name="kelas" 
                            placeholder="e.g., Grade 10-B"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700 placeholder:text-slate-300"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Attendance Status</label>
                        <div class="relative">
                            <select name="keterangan" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-700 appearance-none cursor-pointer">
                                <option value="Hadir">Hadir</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alfa">Alfa</option>
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Date</label>
                        <input type="date" name="tanggal" 
                            value="<?= date('Y-m-d'); ?>"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-700">
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-6">
                    <button type="submit" name="simpan" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-indigo-100 transition-all transform active:scale-[0.98] flex items-center justify-center gap-3">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Save Attendance
                    </button>
                    <p class="text-center text-[10px] text-slate-400 mt-4 uppercase tracking-widest">
                        Verify data before submitting
                    </p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>