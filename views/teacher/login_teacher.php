
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập cho giảng viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4">Đăng nhập cho giảng viên</h3>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" name="username" class="form-control" required autofocus value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-primary w-100" name="login">Đăng nhập</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="<?= BASE_URL ?>controllers/RegisterController.php">Chưa có tài khoản? Đăng ký ngay</a>
                    </div>
                    
                    <div class="text-center mt-2">
                        <a href="<?= BASE_URL ?>">Quay lại trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>