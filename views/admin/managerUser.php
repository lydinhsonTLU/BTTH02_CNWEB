<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
                <a class="nav-link active" href="<?= BASE_URL ?>controllers/AdminController.php">
                    <i class="fas fa-users"></i> Người dùng
                </a>
                <a class="nav-link" href="<?= BASE_URL ?>controllers/CourseController.php">
                    <i class="fas fa-folder"></i> Danh mục khóa học
                </a>
                <a class="nav-link" href="#">
                    <i class="fas fa-chart-bar"></i> Thống kê
                </a>
                <a class="nav-link" href="#">
                    <i class="fas fa-check-circle"></i> Phê duyệt khóa học
                </a>

                <hr class="bg-white">

                <a class="nav-link" href="<?= BASE_URL ?>">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>


            <h2>Tài khoản đang hoạt động</h2>
            <div class="card">
            <table class="table table-hover">
                <tr>
                    <th>Tài khoản</th><th>Email</th><th>Tên</th><th>Vai trò</th><th>Ngày tạo</th><th>Trạng thái</th><th>Hành động</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['fullname']) ?></td>
                        <td>
                            <?php if ($user['role'] == 0): ?>
                                <span class="badge bg-primary">Học viên</span>
                            <?php elseif ($user['role'] == 1): ?>
                                <span class="badge bg-success">Giảng viên</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td><span class="status-badge status-active">Hoạt động</span></td>
                        <td>
                            <div style="display: flex">
                                <form action="<?= BASE_URL ?>controllers/AdminController.php" method="POST">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                    <button title="Vô hiệu hóa" type="submit" class="btn btn-outline-danger" name="vo_hieu_hoa" onclick="return confirm('Bạn chắc chắn muốn vô hiệu hóa?')"><i class="fas fa-ban"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table> <!-- ĐÓNG TABLE Ở ĐÂY -->
            </div>

            <h2>Tài khoản đang chờ duyệt</h2> <!-- BÂY GIỜ H2 MỚI Ở ĐÚNG CHỖ -->
            <div class="card">
            <table class="table table-hover">
                <tr>
                    <th>Tài khoản</th><th>Email</th><th>Tên</th><th>Vai trò</th><th>Ngày tạo</th><th>Trạng thái</th><th>Hành động</th>
                </tr>
                <?php foreach ($users_cho_duyet as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['fullname']) ?></td>
                        <td>
                            <?php if ($user['role'] == 0): ?>
                                <span class="badge bg-primary">Học viên</span>
                            <?php elseif ($user['role'] == 1): ?>
                                <span class="badge bg-success">Giảng viên</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td><span class="status-badge status-pending">Chờ duyệt</span></td>
                        <td>
                            <div style="display: flex">
                                <form action="<?= BASE_URL ?>controllers/AdminController.php" method="get">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                    <button title="Kích hoạt" onclick="return confirm('Bạn muốn kích hoạt?')" style="margin-right: 10px" type="submit" class="btn btn-outline-primary" name="kich_hoat"><i class="fas fa-check"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table> <!-- ĐÓNG TABLE THỨ HAI -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
</script>
</body>
</html>