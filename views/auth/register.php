
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4">Đăng ký tài khoản</h3>
                        
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" id="registerForm">
                            <div class="mb-3">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="fullname" class="form-control" required autofocus value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" required minlength="3" pattern="[a-zA-Z0-9_]+" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                                <small class="text-muted">Chỉ chấp nhận chữ cái, số và dấu gạch dưới</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                                <select name="role" class="form-select" required>
                                    <option value="">-- Chọn vai trò --</option>
                                    <option value="0" <?= (isset($_POST['role']) && $_POST['role'] == '0') ? 'selected' : '' ?>>Sinh viên</option>
                                    <option value="1" <?= (isset($_POST['role']) && $_POST['role'] == '1') ? 'selected' : '' ?>>Giáo viên</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" required minlength="6">
                                <small class="text-muted">Tối thiểu 6 ký tự</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required minlength="6">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                        </form>
                        
                        <div class="text-center mt-3">
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