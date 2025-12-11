<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid white;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card .display-4 {
            font-size: 2.5rem;
        }
        .table-actions button {
            margin: 0 2px;
        }
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-0">
            <div class="p-4">
                <h4><i class="fas fa-graduation-cap"></i> Admin Panel</h4>
                <hr class="bg-white">
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= BASE_URL ?>controllers/AdminController.php">
                    <i class="fas fa-users"></i> Người dùng
                </a>
                <a class="nav-link" href="<?= BASE_URL ?>controllers/CategoryController.php">
                    <i class="fas fa-folder"></i> Danh mục khóa học
                </a>
                <a class="nav-link active" href="<?= BASE_URL ?>controllers/ThongkeController.php">
                    <i class="fas fa-chart-bar"></i> Thống kê
                </a>
                <a class="nav-link" href="<?= BASE_URL ?>controllers/PheduyetCourseController.php">
                    <i class="fas fa-check-circle"></i> Phê duyệt khóa học
                </a>

                <hr class="bg-white">

                <a class="nav-link" href="<?= BASE_URL ?>">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4" style="margin-top: 35px">
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-book fa-3x mb-3"></i>
                                <h5>Tổng khóa học</h5>
                                <p class="display-4"><?= $tongkhoahoc ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-user-graduate fa-3x mb-3"></i>
                                <h5>Học viên</h5>
                                <p class="display-4"><?= $tonghocvien ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
                                <h5>Giảng viên</h5>
                                <p class="display-4"><?= $tonggiangvien ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-dollar-sign fa-3x mb-3"></i>
                                <h5>Doanh thu tháng</h5>
                                <p class="display-4"><?= $doanhthu ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Khóa học phổ biến nhất</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Khóa học</th>
                                    <th>Giá</th>
                                    <th>Level</th>
                                    <th>Số học viên</th>
                                    <th>Giảng viên</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($table_khoaHoc_phobien as $khoahoc): ?>
                                    <tr>
                                        <td><?= $khoahoc['id'] ?></td>
                                        <td><?= $khoahoc['title'] ?></td>
                                        <td><?= $khoahoc['price'] ?></td>
                                        <td>
                                            <?php if ($khoahoc['level'] == 'Beginner'): ?>
                                                <span class="status-badge status-active">Beginner</span>
                                            <?php elseif ($khoahoc['level'] == 'Intermediate'): ?>
                                                <span class="status-badge status-pending">Intermediate</span>
                                            <?php elseif ($khoahoc['level'] == 'Advanced'): ?>
                                                <span class="status-badge status-inactive">Advanced</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><i style="margin-right: 25px" class="bi bi-cart4"></i><?= $khoahoc['so_hocvien'] ?></td>
                                        <td><i class="bi bi-person-fill"></i>  <?= $khoahoc['giang_vien'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
</script>
</body>
</html>