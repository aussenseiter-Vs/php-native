<?php
require_once 'config/koneksi.php';

// Query ambil data
$query = "SELECT * FROM tb_absensi ORDER BY id DESC";
$result = $conn->query($query);

// Cek error query
if (!$result) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">

    <div class="max-w-6xl mx-auto">
        <!-- Header Area -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <i class="fas fa-clipboard-user text-indigo-600"></i>
                    Student Attendance
                </h1>
                <p class="text-slate-500 text-sm mt-1">Manage and monitor daily student records</p>
            </div>
            
            <a href="tambah.php" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-200 transition-all transform active:scale-95 gap-2">
                <i class="fas fa-plus text-sm"></i>
                Add New Record
            </a>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">No</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Student Name</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Class</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Date</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                            // Logic for status badge colors
                            $status = htmlspecialchars($row['keterangan']);
                            $badgeColor = "bg-slate-100 text-slate-600"; // Default
                            
                            if($status == 'Hadir') $badgeColor = "bg-emerald-100 text-emerald-700";
                            elseif($status == 'Izin') $badgeColor = "bg-amber-100 text-amber-700";
                            elseif($status == 'Sakit') $badgeColor = "bg-blue-100 text-blue-700";
                            elseif($status == 'Alfa') $badgeColor = "bg-red-100 text-red-700";
                        ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-500 font-medium"><?= $no++; ?></td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-slate-700"><?= htmlspecialchars($row['nama_siswa']); ?></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600"><?= htmlspecialchars($row['kelas']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?= $badgeColor ?>">
                                    <?= $status ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                <i class="far fa-calendar-alt mr-1"></i>
                                <?= date('d M Y', strtotime($row['tanggal'])); ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    <a href="hapus.php?id=<?= $row['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this record?')"
                                       class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                        <i class="fas fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>

                        <?php if($result->num_rows == 0): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-slate-400">
                                    <i class="fas fa-folder-open text-4xl mb-3"></i>
                                    <p>No attendance records found.</p>
                                </div>
                                
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer -->
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
                <p class="text-xs text-slate-500">Total Records: <span class="font-bold"><?= $result->num_rows ?></span></p>
            </div>
        </div>
    </div>

</body>
</html>