<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($lesson['title']) ?> - Bài học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
        .lesson-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .lesson-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .lesson-sidebar {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
        }
        .lesson-list-item {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .lesson-list-item:hover {
            background: #f8f9fa;
            border-left-color: #667eea;
        }
        .lesson-list-item.active {
            background: #e7f3ff;
            border-left-color: #667eea;
            font-weight: bold;
        }
        .material-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .progress-slider {
            margin: 20px 0;
        }
    </style>
</head>
<body>
<div class="lesson-container">
    <div class="row">
        <div class="col-lg-9">
            <a href="<?= BASE_URL ?>controllers/StudentController.php?action=course_detail&id=<?= $course_id ?>" class="btn btn-secondary mb-3">← Quay lại khóa học</a>
            
            <div class="lesson-content">
                <h1 class="mb-3"><?= htmlspecialchars($lesson['title']) ?></h1>
                <p class="text-muted mb-4">Khóa học: <strong><?= htmlspecialchars($lesson['course_title']) ?></strong></p>
                
                <?php if ($lesson['video_url']): ?>
                    <div class="video-container">
                        <?php 
                        // Xử lý URL YouTube
                        $video_url = $lesson['video_url'];
                        if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                            // Lấy video ID từ URL
                            preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video_url, $matches);
                            $video_id = $matches[1] ?? '';
                            if ($video_id) {
                                $embed_url = "https://www.youtube.com/embed/" . $video_id;
                                echo '<iframe src="' . $embed_url . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                            }
                        } else {
                            echo '<iframe src="' . htmlspecialchars($video_url) . '" frameborder="0" allowfullscreen></iframe>';
                        }
                        ?>
                    </div>
                <?php endif; ?>
                
                <div class="mt-4">
                    <h3>📝 Nội dung bài học</h3>
                    <div class="mt-3">
                        <?= nl2br(htmlspecialchars($lesson['content'])) ?>
                    </div>
                </div>
                
                <?php if (!empty($materials)): ?>
                    <div class="mt-5">
                        <h3>📎 Tài liệu đính kèm</h3>
                        <?php foreach ($materials as $material): ?>
                            <div class="material-item">
                                <div>
                                    <strong>📄 <?= htmlspecialchars($material['filename']) ?></strong><br>
                                    <small class="text-muted">
                                        Loại: <?= htmlspecialchars($material['file_type']) ?> | 
                                        Tải lên: <?= date('d/m/Y H:i', strtotime($material['uploaded_at'])) ?>
                                    </small>
                                </div>
                                <a href="<?= BASE_URL . $material['file_path'] ?>" class="btn btn-primary btn-sm" download>
                                    ⬇️ Tải xuống
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="mt-5">
                    <h4>Cập nhật tiến độ học tập</h4>
                    <div class="progress-slider">
                        <label for="progressRange" class="form-label">Tiến độ hoàn thành: <span id="progressValue"><?= $enrollment['progress'] ?></span>%</label>
                        <input type="range" class="form-range" id="progressRange" min="0" max="100" value="<?= $enrollment['progress'] ?>" oninput="updateProgressDisplay(this.value)">
                        <button class="btn btn-success mt-2" onclick="saveProgress()">💾 Lưu tiến độ</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="lesson-sidebar">
                <h5 class="mb-3">📚 Danh sách bài học</h5>
                <?php foreach ($all_lessons as $index => $l): ?>
                    <div class="lesson-list-item <?= $l['id'] == $lesson_id ? 'active' : '' ?>" 
                         onclick="location.href='<?= BASE_URL ?>controllers/StudentController.php?action=view_lesson&lesson_id=<?= $l['id'] ?>&course_id=<?= $course_id ?>'">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted">Bài <?= $index + 1 ?></small>
                                <div><?= htmlspecialchars($l['title']) ?></div>
                            </div>
                            <?php if ($l['id'] == $lesson_id): ?>
                                <span class="badge bg-primary">▶️</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function updateProgressDisplay(value) {
    document.getElementById('progressValue').textContent = value;
}

function saveProgress() {
    const progress = document.getElementById('progressRange').value;
    const courseId = <?= $course_id ?>;
    
    fetch('<?= BASE_URL ?>controllers/StudentController.php?action=update_progress', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `course_id=${courseId}&progress=${progress}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Có lỗi xảy ra khi lưu tiến độ!');
        console.error('Error:', error);
    });
}
</script>
</body>
</html>
