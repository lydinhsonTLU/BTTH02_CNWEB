<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['title']) ?> - Chi tiết khóa học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .course-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .course-header {
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .course-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .lesson-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        .lesson-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        .badge-custom {
            font-size: 0.9em;
            padding: 8px 15px;
        }
    </style>
</head>
<body>
<div class="course-detail-container">
    <a href="<?= BASE_URL ?>controllers/StudentController.php" class="btn btn-secondary mb-3">← Quay lại</a>
    
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
    
    <div class="course-header">
        <h1 class="text-primary"><?= htmlspecialchars($course['title']) ?></h1>
        <p class="lead text-muted"><?= htmlspecialchars($course['description']) ?></p>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <?php if ($course['image']): ?>
                <img src="<?= BASE_URL . $course['image'] ?>" alt="<?= htmlspecialchars($course['title']) ?>" class="course-image">
            <?php endif; ?>
            
            <h3 class="mt-4 mb-3">📚 Danh sách bài học</h3>
            
            <?php if (empty($lessons)): ?>
                <p class="text-muted">Khóa học chưa có bài học nào.</p>
            <?php else: ?>
                <?php foreach ($lessons as $index => $lesson): ?>
                    <div class="lesson-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Bài <?= $index + 1 ?>: <?= htmlspecialchars($lesson['title']) ?></h5>
                                <?php if ($lesson['video_url']): ?>
                                    <small class="text-muted">🎥 Video bài giảng</small>
                                <?php endif; ?>
                            </div>
                            <?php if ($is_enrolled): ?>
                                <a href="<?= BASE_URL ?>controllers/StudentController.php?action=view_lesson&lesson_id=<?= $lesson['id'] ?>&course_id=<?= $course['id'] ?>" class="btn btn-sm btn-primary">Xem bài học</a>
                            <?php else: ?>
                                <span class="badge bg-secondary">🔒 Đăng ký để xem</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Thông tin khóa học</h5>
                    
                    <div class="mb-3">
                        <strong>📂 Danh mục:</strong><br>
                        <span class="badge bg-info badge-custom"><?= htmlspecialchars($course['category_name']) ?></span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>👨‍🏫 Giảng viên:</strong><br>
                        <?= htmlspecialchars($course['instructor_name']) ?>
                    </div>
                    
                    <div class="mb-3">
                        <strong>⏱️ Thời lượng:</strong><br>
                        <?= $course['duration_weeks'] ?> tuần
                    </div>
                    
                    <div class="mb-3">
                        <strong>📊 Mức độ:</strong><br>
                        <span class="badge bg-<?= $course['level'] == 'beginner' ? 'success' : ($course['level'] == 'intermediate' ? 'warning' : 'danger') ?> badge-custom">
                            <?= ucfirst($course['level']) ?>
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>💰 Giá:</strong><br>
                        <h4 class="text-primary"><?= number_format($course['price'], 0, ',', '.') ?> VNĐ</h4>
                    </div>
                    
                    <div class="mb-3">
                        <strong>👨‍🎓 Học viên:</strong><br>
                        <?= $course['total_students'] ?> người đã đăng ký
                    </div>
                    
                    <div class="mb-3">
                        <strong>📅 Ngày tạo:</strong><br>
                        <?= date('d/m/Y', strtotime($course['created_at'])) ?>
                    </div>
                    
                    <hr>
                    
                    <?php if ($is_enrolled): ?>
                        <div class="alert alert-success mb-3">
                            <strong>✅ Đã đăng ký</strong><br>
                            Tiến độ: <?= $is_enrolled['progress'] ?>%
                            <div class="progress mt-2">
                                <div class="progress-bar" role="progressbar" style="width: <?= $is_enrolled['progress'] ?>%" aria-valuenow="<?= $is_enrolled['progress'] ?>" aria-valuemin="0" aria-valuemax="100"><?= $is_enrolled['progress'] ?>%</div>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100" onclick="scrollToLessons()">▶️ Tiếp tục học</button>
                    <?php else: ?>
                        <form method="POST" action="<?= BASE_URL ?>controllers/StudentController.php?action=enroll">
                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                            <button type="submit" class="btn btn-success w-100 btn-lg">📝 Đăng ký ngay</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function scrollToLessons() {
    document.querySelector('.lesson-item').scrollIntoView({ behavior: 'smooth' });
}
</script>
</body>
</html>
