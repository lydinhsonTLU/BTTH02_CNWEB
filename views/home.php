<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Hệ Thống Quản Lý</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .welcome-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 50px 40px;
            max-width: 900px;
            width: 100%;
        }

        .header-section {
            text-align: center;
            margin-bottom: 50px;
        }

        .header-section h1 {
            color: #00695c;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .header-section p {
            color: #546e7a;
            font-size: 1.1rem;
        }

        .action-card {
            background: linear-gradient(135deg, #80cbc4 0%, #4db6ac 100%);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            border: none;
            color: white;
            height: 100%;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .action-card i {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .action-card h3 {
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .action-card p {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .btn-action {
            background: white;
            color: #00695c;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-action:hover {
            background: #00695c;
            color: white;
            transform: scale(1.05);
        }

        .register-section {
            background: linear-gradient(135deg, #26a69a 0%, #00897b 100%);
        }

        .login-section {
            background: linear-gradient(135deg, #4db6ac 0%, #26a69a 100%);
        }

        .divider {
            margin: 40px 0;
            text-align: center;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #b2dfdb;
        }

        .divider span {
            background: white;
            padding: 0 20px;
            position: relative;
            color: #00695c;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="main-container">
    <div class="welcome-card">
        <div class="header-section">
            <h1><i class="fas fa-graduation-cap"></i> Hệ Thống Quản Lý</h1>
            <p>Chào mừng bạn đến với hệ thống quản lý giảng viên và sinh viên</p>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="action-card register-section">
                    <i class="fas fa-user-plus"></i>
                    <h3>Đăng Ký Tài Khoản</h3>
                    <p>Dành cho giảng viên và sinh viên chưa có tài khoản</p>
                    <a class="btn btn-action" href=" <?= BASE_URL ?>controllers/RegisterController.php">
                        Đăng Ký Ngay
                    </a>
                </div>
            </div>
        </div>

        <div class="divider">
            <span>HOẶC ĐĂNG NHẬP</span>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="action-card login-section">
                    <i class="fas fa-user-shield"></i>
                    <h3>Admin</h3>
                    <p>Quản trị hệ thống</p>
                    <a class="btn btn-action" href="<?= BASE_URL ?>controllers/LoginController_admin.php">
                        Đăng Nhập
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="action-card login-section">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>Giảng Viên</h3>
                    <p>Dành cho giảng viên</p>
                    <a class="btn btn-action" href= " <?= BASE_URL ?>controllers/LoginController_giangvien.php">
                        Đăng Nhập
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="action-card login-section">
                    <i class="fas fa-user-graduate"></i>
                    <h3>Sinh Viên</h3>
                    <p>Dành cho sinh viên</p>
                    <a class="btn btn-action" href="<?= BASE_URL ?>controllers/LoginController_student.php">
                        Đăng Nhập
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>