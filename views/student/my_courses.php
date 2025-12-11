<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khóa học của tôi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        .course-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .course-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .progress {
            height: 25px;
            border-radius: 10px;
        }
        .progress-bar {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container-custom">
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>📚 Khóa học của tôi</h1>
                <p class="text-muted mb-0">Quản lý và theo dõi các khóa học đã đăng ký</p>
            </div>
            <div>
                <a href="<?= BASE_URL ?>controllers/StudentController.php" class="btn btn-primary me-2">← Trang chủ</a>
                <a href="<?= BASE_URL ?>controllers/StudentController.php?action=logout" class="btn btn-danger">Đăng xuất</a>
            </div>
        </div>
    </div>
    
    <?php if (empty($enrolled_courses)): ?>
        <div class="alert alert-info text-center">
            <h4>📚 Bạn chưa đăng ký khóa học nào</h4>
            <p>Hãy khám phá và đăng ký các khóa học phù hợp với bạn!</p>
            <a href="<?= BASE_URL ?>controllers/StudentController.php" class="btn btn-primary">Khám phá khóa học</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($enrolled_courses as $course): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="course-card">
                        <?php if ($course['image']): ?>
                            <img src="<?= BASE_URL . $course['image'] ?>" alt="<?= htmlspecialchars($course['title']) ?>" class="course-image">
                        <?php else: ?>
                            <div class="course-image bg-secondary d-flex align-items-center justify-content-center text-white">
                                <h3>📚</h3>
                            </div>
                        <?php endif; ?>
                        
                        <h4><?= htmlspecialchars($course['title']) ?></h4>
                        
                        <p class="text-muted small mb-2">
                            <span class="badge bg-info"><?= htmlspecialchars($course['category_name']) ?></span>
                            <span class="badge bg-secondary"><?= htmlspecialchars($course['level']) ?></span>
                        </p>
                        
                        <p class="text-muted small">
                            👨‍🏫 <?= htmlspecialchars($course['instructor_name']) ?><br>
                            📅 Đăng ký: <?= date('d/m/Y', strtotime($course['enrolled_date'])) ?><br>
                            ⏱️ Thời lượng: <?= $course['duration_weeks'] ?> tuần
                        </p>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Tiến độ học tập</small>
                                <small><strong><?= $course['progress'] ?>%</strong></small>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-<?= $course['progress'] < 30 ? 'danger' : ($course['progress'] < 70 ? 'warning' : 'success') ?>" 
                                     role="progressbar" 
                                     style="width: <?= $course['progress'] ?>%" 
                                     aria-valuenow="<?= $course['progress'] ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <?= $course['progress'] ?>%
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="<?= BASE_URL ?>controllers/StudentController.php?action=course_detail&id=<?= $course['course_id'] ?>" 
                               class="btn btn-primary">
                                ▶️ Tiếp tục học
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
