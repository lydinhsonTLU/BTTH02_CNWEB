<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tài liệu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
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
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid white;
        }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        .material-item {
            border-left: 4px solid #17a2b8;
            transition: all 0.3s;
        }
        .material-item:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
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
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-paperclip"></i> Quản lý tài liệu</h2>
                    <p class="text-muted mb-0">Bài học: <strong><?= htmlspecialchars($lesson['title']) ?></strong></p>
                    <p class="text-muted small mb-0">Khóa học: <?= htmlspecialchars($course['title']) ?></p>
                </div>
                <a href="<?= BASE_URL ?>controllers/TeacherController.php?action=manage_lessons&course_id=<?= $course_id ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại bài học
                </a>
            </div>

            <!-- Add Material -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-plus"></i> Thêm tài liệu mới</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>controllers/TeacherController.php?action=add_material">
                        <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">
                        <input type="hidden" name="course_id" value="<?= $course_id ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tên file <span class="text-danger">*</span></label>
                                    <input type="text" name="filename" class="form-control" placeholder="document.pdf" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Đường dẫn file <span class="text-danger">*</span></label>
                                    <input type="text" name="file_path" class="form-control" placeholder="/uploads/materials/document.pdf" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Loại file <span class="text-danger">*</span></label>
                                    <select name="file_type" class="form-select" required>
                                        <option value="">-- Chọn loại --</option>
                                        <option value="pdf">PDF</option>
                                        <option value="doc">Word (DOC/DOCX)</option>
                                        <option value="ppt">PowerPoint (PPT/PPTX)</option>
                                        <option value="zip">ZIP/RAR</option>
                                        <option value="image">Image (JPG/PNG)</option>
                                        <option value="video">Video (MP4/AVI)</option>
                                        <option value="other">Khác</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i> <strong>Lưu ý:</strong> Hiện tại chưa hỗ trợ upload file trực tiếp. Vui lòng nhập đường dẫn file đã upload sẵn.
                        </div>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-save"></i> Thêm tài liệu
                        </button>
                    </form>
                </div>
            </div>

            <!-- Materials List -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Danh sách tài liệu (<?= count($materials) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($materials)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-4x mb-3"></i>
                            <p>Chưa có tài liệu nào. Hãy thêm tài liệu đầu tiên!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($materials as $material): ?>
                        <div class="card material-item mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <?php
                                        $icon = 'fa-file';
                                        $color = 'text-secondary';
                                        switch($material['file_type']) {
                                            case 'pdf': $icon = 'fa-file-pdf'; $color = 'text-danger'; break;
                                            case 'doc': $icon = 'fa-file-word'; $color = 'text-primary'; break;
                                            case 'ppt': $icon = 'fa-file-powerpoint'; $color = 'text-warning'; break;
                                            case 'zip': $icon = 'fa-file-archive'; $color = 'text-dark'; break;
                                            case 'image': $icon = 'fa-file-image'; $color = 'text-info'; break;
                                            case 'video': $icon = 'fa-file-video'; $color = 'text-success'; break;
                                        }
                                        ?>
                                        <i class="fas <?= $icon ?> fa-3x <?= $color ?>"></i>
                                    </div>
                                    <div class="col-md-9">
                                        <h5 class="mb-1"><?= htmlspecialchars($material['filename']) ?></h5>
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-folder"></i> <?= htmlspecialchars($material['file_path']) ?>
                                            | <i class="fas fa-tag"></i> <?= strtoupper($material['file_type']) ?>
                                            | <i class="fas fa-calendar"></i> <?= date('d/m/Y H:i', strtotime($material['uploaded_at'])) ?>
                                        </p>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button class="btn btn-sm btn-danger" onclick="deleteMaterial(<?= $material['id'] ?>)">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="<?= BASE_URL ?>controllers/TeacherController.php?action=delete_material" style="display: none;">
    <input type="hidden" name="course_id" value="<?= $course_id ?>">
    <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">
    <input type="hidden" name="material_id" id="delete_material_id">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function deleteMaterial(id) {
    if (confirm('Bạn có chắc chắn muốn xóa tài liệu này?')) {
        document.getElementById('delete_material_id').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
</body>
</html>
