<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bài học</title>
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
        .lesson-item {
            border-left: 4px solid #667eea;
            transition: all 0.3s;
        }
        .lesson-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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
                    <h2><i class="fas fa-book-open"></i> Quản lý bài học</h2>
                    <p class="text-muted mb-0">Khóa học: <strong><?= htmlspecialchars($course['title']) ?></strong></p>
                </div>
                <a href="<?= BASE_URL ?>controllers/TeacherController.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <!-- Add New Lesson -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus"></i> Thêm bài học mới</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>controllers/TeacherController.php?action=create_lesson">
                        <input type="hidden" name="course_id" value="<?= $course_id ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tiêu đề bài học <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Thứ tự <span class="text-danger">*</span></label>
                                    <input type="number" name="order" class="form-control" min="0" value="<?= count($lessons) + 1 ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Video URL</label>
                                    <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=...">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung bài học</label>
                            <textarea name="content" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Thêm bài học
                        </button>
                    </form>
                </div>
            </div>

            <!-- Lessons List -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Danh sách bài học (<?= count($lessons) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($lessons)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-4x mb-3"></i>
                            <p>Chưa có bài học nào. Hãy thêm bài học đầu tiên!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($lessons as $index => $lesson): ?>
                        <div class="card lesson-item mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <h4 class="text-muted mb-0"><?= $lesson['order'] ?></h4>
                                    </div>
                                    <div class="col-md-7">
                                        <h5 class="mb-1"><?= htmlspecialchars($lesson['title']) ?></h5>
                                        <p class="text-muted small mb-0">
                                            <?= $lesson['video_url'] ? '<i class="fas fa-video text-primary"></i> Có video' : '<i class="fas fa-file-alt text-secondary"></i> Không có video' ?>
                                            | <i class="fas fa-paperclip"></i> <?= $lesson['material_count'] ?> tài liệu
                                            | <i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($lesson['created_at'])) ?>
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <a href="<?= BASE_URL ?>controllers/TeacherController.php?action=manage_materials&lesson_id=<?= $lesson['id'] ?>&course_id=<?= $course_id ?>" 
                                           class="btn btn-sm btn-info" title="Quản lý tài liệu">
                                            <i class="fas fa-paperclip"></i> Tài liệu
                                        </a>
                                        <button class="btn btn-sm btn-warning" onclick="editLesson(<?= htmlspecialchars(json_encode($lesson)) ?>)" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteLesson(<?= $lesson['id'] ?>)" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php if ($lesson['content']): ?>
                                <div class="mt-2">
                                    <small class="text-muted"><?= nl2br(htmlspecialchars(substr($lesson['content'], 0, 200))) ?><?= strlen($lesson['content']) > 200 ? '...' : '' ?></small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Lesson Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>controllers/TeacherController.php?action=edit_lesson">
                <input type="hidden" name="course_id" value="<?= $course_id ?>">
                <input type="hidden" name="lesson_id" id="edit_lesson_id">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Chỉnh sửa bài học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thứ tự <span class="text-danger">*</span></label>
                            <input type="number" name="order" id="edit_order" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Video URL</label>
                            <input type="url" name="video_url" id="edit_video_url" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nội dung</label>
                        <textarea name="content" id="edit_content" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="<?= BASE_URL ?>controllers/TeacherController.php?action=delete_lesson" style="display: none;">
    <input type="hidden" name="course_id" value="<?= $course_id ?>">
    <input type="hidden" name="lesson_id" id="delete_lesson_id">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const editModal = new bootstrap.Modal(document.getElementById('editModal'));

function editLesson(lesson) {
    document.getElementById('edit_lesson_id').value = lesson.id;
    document.getElementById('edit_title').value = lesson.title;
    document.getElementById('edit_order').value = lesson.order;
    document.getElementById('edit_video_url').value = lesson.video_url || '';
    document.getElementById('edit_content').value = lesson.content || '';
    editModal.show();
}

function deleteLesson(id) {
    if (confirm('Bạn có chắc chắn muốn xóa bài học này? Tất cả tài liệu liên quan cũng sẽ bị xóa!')) {
        document.getElementById('delete_lesson_id').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
</body>
</html>
