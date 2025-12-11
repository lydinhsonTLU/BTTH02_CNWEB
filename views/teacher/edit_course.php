<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa khóa học</title>
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
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-0">
            <div class="p-4">
                <h4><i class="fas fa-chalkboard-teacher"></i> Giảng viên</h4>
                <hr class="bg-white">
                <p class="small mb-0"><?= htmlspecialchars($teacher_name) ?></p>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link" href="<?= BASE_URL ?>controllers/TeacherController.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a class="nav-link" href="<?= BASE_URL ?>controllers/TeacherController.php?action=create_course">
                    <i class="fas fa-plus-circle"></i> Tạo khóa học mới
                </a>
                <hr class="bg-white">
                <a class="nav-link" href="<?= BASE_URL ?>">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-edit"></i> Chỉnh sửa khóa học</h2>
                <a href="<?= BASE_URL ?>controllers/TeacherController.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>controllers/TeacherController.php?action=edit_course&id=<?= $course['id'] ?>">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Tiêu đề khóa học <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($course['title']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mô tả <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($course['description']) ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select" required>
                                            <option value="">-- Chọn danh mục --</option>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $course['category_id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($cat['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Cấp độ <span class="text-danger">*</span></label>
                                        <select name="level" class="form-select" required>
                                            <option value="Beginner" <?= $course['level'] == 'Beginner' ? 'selected' : '' ?>>Beginner (Người mới)</option>
                                            <option value="Intermediate" <?= $course['level'] == 'Intermediate' ? 'selected' : '' ?>>Intermediate (Trung cấp)</option>
                                            <option value="Advanced" <?= $course['level'] == 'Advanced' ? 'selected' : '' ?>>Advanced (Nâng cao)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                                        <input type="number" name="price" class="form-control" min="0" step="1000" value="<?= $course['price'] ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Thời lượng (tuần) <span class="text-danger">*</span></label>
                                        <input type="number" name="duration_weeks" class="form-control" min="1" value="<?= $course['duration_weeks'] ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Hình ảnh (URL hoặc tên file)</label>
                                    <input type="text" name="image" class="form-control" value="<?= htmlspecialchars($course['image']) ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Thông tin khóa học</h6>
                                        <ul class="small mb-0">
                                            <li><strong>Ngày tạo:</strong> <?= date('d/m/Y', strtotime($course['created_at'])) ?></li>
                                            <li><strong>Cập nhật:</strong> <?= date('d/m/Y', strtotime($course['updated_at'])) ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="<?= BASE_URL ?>controllers/TeacherController.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
