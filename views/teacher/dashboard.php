<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giảng viên Dashboard</title>
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
        .course-card {
            transition: transform 0.3s;
        }
        .course-card:hover {
            transform: translateY(-5px);
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
                <a class="nav-link active" href="<?= BASE_URL ?>controllers/TeacherController.php">
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

            <h2 class="mb-4">Dashboard Giảng Viên</h2>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-3x mb-3"></i>
                            <h5>Khóa học</h5>
                            <p class="display-4"><?= $stats['total_courses'] ?? 0 ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <h5>Học viên</h5>
                            <p class="display-4"><?= $stats['total_students'] ?? 0 ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="fas fa-file-alt fa-3x mb-3"></i>
                            <h5>Bài học</h5>
                            <p class="display-4"><?= $stats['total_lessons'] ?? 0 ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Courses -->
            <?php if (!empty($pending_courses)): ?>
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Khóa học chờ phê duyệt</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Thời lượng</th>
                                    <th>Giá</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pending_courses as $course): ?>
                                <tr>
                                    <td><?= htmlspecialchars($course['title']) ?></td>
                                    <td><?= $course['duration_weeks'] ?> tuần</td>
                                    <td><?= number_format($course['price'], 0, ',', '.') ?>đ</td>
                                    <td><?= date('d/m/Y', strtotime($course['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- My Courses -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-book"></i> Khóa học của tôi</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($my_courses)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <p class="text-muted">Bạn chưa có khóa học nào. Hãy tạo khóa học đầu tiên!</p>
                            <a href="<?= BASE_URL ?>controllers/TeacherController.php?action=create_course" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tạo khóa học mới
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($my_courses as $course): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card course-card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-folder"></i> <?= htmlspecialchars($course['category_name']) ?>
                                        </p>
                                        <p class="card-text small"><?= substr(htmlspecialchars($course['description']), 0, 100) ?>...</p>
                                        <div class="row text-center mb-3">
                                            <div class="col-4">
                                                <small class="text-muted">Học viên</small>
                                                <p class="mb-0 fw-bold"><?= $course['student_count'] ?></p>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted">Bài học</small>
                                                <p class="mb-0 fw-bold"><?= $course['lesson_count'] ?></p>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted">Giá</small>
                                                <p class="mb-0 fw-bold"><?= number_format($course['price'], 0, ',', '.') ?>đ</p>
                                            </div>
                                        </div>
                                        <div class="btn-group w-100" role="group">
                                            <a href="<?= BASE_URL ?>controllers/TeacherController.php?action=manage_lessons&course_id=<?= $course['id'] ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-book-open"></i> Bài học
                                            </a>
                                            <a href="<?= BASE_URL ?>controllers/TeacherController.php?action=view_students&course_id=<?= $course['id'] ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-users"></i> Học viên
                                            </a>
                                            <a href="<?= BASE_URL ?>controllers/TeacherController.php?action=edit_course&id=<?= $course['id'] ?>" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteCourse(<?= $course['id'] ?>)" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="POST" action="<?= BASE_URL ?>controllers/TeacherController.php?action=delete_course" style="display: none;">
    <input type="hidden" name="id" id="deleteId">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function deleteCourse(id) {
    if (confirm('Bạn có chắc chắn muốn xóa khóa học này? Tất cả bài học và tài liệu sẽ bị xóa!')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
</body>
</html>
