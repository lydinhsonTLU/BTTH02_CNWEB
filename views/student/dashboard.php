
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .btn {
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .btn-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .content-area {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            min-height: 400px;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-title {
            color: #667eea;
            font-size: 2em;
            margin-bottom: 20px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }

        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .course-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .course-title {
            font-size: 1.3em;
            color: #333;
            margin-bottom: 10px;
        }

        .course-info {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 15px;
        }

        .progress-bar {
            background: #e0e0e0;
            border-radius: 10px;
            height: 20px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8em;
            font-weight: bold;
        }

        .icon {
            font-size: 1.2em;
        }

        .course-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-small {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

        .btn-detail {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-detail:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-register {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-register:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4);
        }

        .btn-registered {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            cursor: default;
        }

        .btn-continue {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-continue:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(240, 147, 251, 0.4);
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1>🎓 Hệ Thống Quản Lý Khóa Học</h1>
                <p>Chào mừng <strong><?= htmlspecialchars($student_name) ?></strong></p>
            </div>
            <a href="<?= BASE_URL ?>controllers/StudentController.php?action=logout" class="btn btn-danger">Đăng xuất</a>
        </div>
    </header>

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

    <div class="nav-buttons">
        <button class="btn btn-primary" onclick="showSection('all-courses')">
            <span class="icon">📚</span>
            Danh Sách Khóa Học
        </button>
        <button class="btn btn-success" onclick="showSection('my-courses')">
            <span class="icon">✅</span>
            Khóa Học Đã Đăng Ký (<?= count($enrolled_courses) ?>)
        </button>
        <button class="btn btn-info" onclick="showSection('progress')">
            <span class="icon">📊</span>
            Tiến Độ Học Tập
        </button>
    </div>

    <div class="content-area">
        <div id="all-courses" class="content-section active">
            <h2 class="section-title">📚 Danh Sách Khóa Học</h2>
            
            <!-- Bộ lọc và tìm kiếm -->
            <form method="GET" action="<?= BASE_URL ?>controllers/StudentController.php" class="mb-4">
                <input type="hidden" name="action" value="dashboard">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm khóa học..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="">-- Tất cả danh mục --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">🔍 Tìm</button>
                    </div>
                </div>
            </form>
            
            <div class="course-grid">
                <?php if (empty($all_courses)): ?>
                    <p class="text-center">Không tìm thấy khóa học nào.</p>
                <?php else: ?>
                    <?php foreach ($all_courses as $course): ?>
                        <div class="course-card">
                            <?php if ($course['image']): ?>
                                <img src="<?= BASE_URL . $course['image'] ?>" alt="<?= htmlspecialchars($course['title']) ?>" style="width:100%;height:150px;object-fit:cover;border-radius:10px;margin-bottom:10px;">
                            <?php endif; ?>
                            <h3 class="course-title"><?= htmlspecialchars($course['title']) ?></h3>
                            <p class="course-info">
                                📂 <?= htmlspecialchars($course['category_name']) ?> | 
                                👨‍🏫 <?= htmlspecialchars($course['instructor_name']) ?>
                            </p>
                            <p class="course-info">⏱️ <?= $course['duration_weeks'] ?> tuần | 👨‍🎓 <?= $course['total_students'] ?> học viên</p>
                            <p class="course-info"><?= htmlspecialchars(substr($course['description'], 0, 100)) ?>...</p>
                            <p class="course-info">💰 <?= number_format($course['price'], 0, ',', '.') ?> VNĐ</p>
                            <div class="course-actions">
                                <a href="<?= BASE_URL ?>controllers/StudentController.php?action=course_detail&id=<?= $course['id'] ?>" class="btn-small btn-detail">📖 Chi tiết</a>
                                <?php if (in_array($course['id'], $enrolled_course_ids)): ?>
                                    <button class="btn-small btn-registered">✅ Đã đăng ký</button>
                                <?php else: ?>
                                    <form method="POST" action="<?= BASE_URL ?>controllers/StudentController.php?action=enroll" style="flex:1;margin:0;">
                                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                        <button type="submit" class="btn-small btn-register w-100">📝 Đăng ký</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div id="my-courses" class="content-section">
            <h2 class="section-title">✅ Khóa Học Đã Đăng Ký</h2>
            <div class="course-grid">
                <?php if (empty($enrolled_courses)): ?>
                    <p class="text-center">Bạn chưa đăng ký khóa học nào.</p>
                <?php else: ?>
                    <?php foreach ($enrolled_courses as $enrolled): ?>
                        <div class="course-card">
                            <?php if ($enrolled['image']): ?>
                                <img src="<?= BASE_URL . $enrolled['image'] ?>" alt="<?= htmlspecialchars($enrolled['title']) ?>" style="width:100%;height:150px;object-fit:cover;border-radius:10px;margin-bottom:10px;">
                            <?php endif; ?>
                            <h3 class="course-title"><?= htmlspecialchars($enrolled['title']) ?></h3>
                            <p class="course-info">
                                📂 <?= htmlspecialchars($enrolled['category_name']) ?> | 
                                👨‍🏫 <?= htmlspecialchars($enrolled['instructor_name']) ?>
                            </p>
                            <p class="course-info">⏱️ <?= $enrolled['duration_weeks'] ?> tuần | 📊 Mức độ: <?= htmlspecialchars($enrolled['level']) ?></p>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= $enrolled['progress'] ?>%;"><?= $enrolled['progress'] ?>%</div>
                            </div>
                            <p class="course-info">📅 Ngày đăng ký: <?= date('d/m/Y', strtotime($enrolled['enrolled_date'])) ?></p>
                            <div class="course-actions">
                                <a href="<?= BASE_URL ?>controllers/StudentController.php?action=course_detail&id=<?= $enrolled['course_id'] ?>" class="btn-small btn-detail">📖 Chi tiết</a>
                                <a href="<?= BASE_URL ?>controllers/StudentController.php?action=course_detail&id=<?= $enrolled['course_id'] ?>" class="btn-small btn-continue">▶️ Tiếp tục học</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div id="progress" class="content-section">
            <h2 class="section-title">📊 Tiến Độ Học Tập</h2>
            <div style="margin-top: 30px;">
                <?php if (empty($enrolled_courses)): ?>
                    <p class="text-center">Bạn chưa đăng ký khóa học nào để theo dõi tiến độ.</p>
                <?php else: ?>
                    <?php 
                    $total_courses = count($enrolled_courses);
                    $total_duration = array_sum(array_column($enrolled_courses, 'duration_weeks'));
                    $avg_progress = $total_courses > 0 ? array_sum(array_column($enrolled_courses, 'progress')) / $total_courses : 0;
                    ?>
                    
                    <h3 style="color: #333; margin-bottom: 20px;">Thống Kê Chung</h3>
                    <div style="background: #f5f7fa; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                        <p style="font-size: 1.1em; margin-bottom: 10px;"><strong>Tổng số khóa học đã đăng ký:</strong> <?= $total_courses ?></p>
                        <p style="font-size: 1.1em; margin-bottom: 10px;"><strong>Tổng thời gian học:</strong> <?= $total_duration ?> tuần</p>
                        <p style="font-size: 1.1em; margin-bottom: 10px;"><strong>Tiến độ trung bình:</strong> <?= number_format($avg_progress, 1) ?>%</p>
                    </div>

                    <h3 style="color: #333; margin-bottom: 20px;">Chi Tiết Tiến Độ</h3>
                    <div style="background: #f5f7fa; padding: 20px; border-radius: 15px;">
                        <?php foreach ($enrolled_courses as $index => $enrolled): ?>
                            <div style="<?= $index < count($enrolled_courses) - 1 ? 'margin-bottom: 25px;' : '' ?>">
                                <p style="font-weight: bold; margin-bottom: 10px;"><?= htmlspecialchars($enrolled['title']) ?></p>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?= $enrolled['progress'] ?>%;"><?= $enrolled['progress'] ?>%</div>
                                </div>
                                <p style="font-size: 0.9em; color: #666; margin-top: 5px;">
                                    Ngày đăng ký: <?= date('d/m/Y', strtotime($enrolled['enrolled_date'])) ?> | 
                                    Trạng thái: <span class="badge bg-<?= $enrolled['status'] == 'active' ? 'success' : 'secondary' ?>"><?= htmlspecialchars($enrolled['status']) ?></span>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showSection(sectionId) {
        const sections = document.querySelectorAll('.content-section');
        sections.forEach(section => {
            section.classList.remove('active');
        });

        document.getElementById(sectionId).classList.add('active');
    }
</script>
</body>
</html>