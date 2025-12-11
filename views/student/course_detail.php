<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết khóa học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <?php if (empty($course) || !is_array($course)): ?>
                <div class="alert alert-warning">Khóa học không tồn tại hoặc chưa được tải.</div>
            <?php else: ?>
                <h2 class="text-primary mb-3"><?= htmlspecialchars($course['title'] ?? '---') ?></h2>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="text-muted">Mô tả</h5>
                        <p><?= nl2br(htmlspecialchars($course['description'] ?? 'Chưa có mô tả.')) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Danh sách bài học</h5>
                    <?php if (empty($lessons) || !is_array($lessons)): ?>
                        <p class="text-muted">Chưa có bài học.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($lessons as $l): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($l['title']) ?></strong>
                                        <div class="small text-muted">Thứ tự: <?= htmlspecialchars($l['order']) ?></div>
                                    </div>
                                    <div>
                                        <a href="?url=student/viewLesson/<?= $l['id'] ?>" class="btn btn-sm btn-outline-primary">Mở bài học</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card sticky-top" style="top:20px;">
                <div class="card-body text-center">
                    <h5 class="text-muted">Tiến độ học</h5>
                    <?php $progress = intval($progress ?? 0); ?>
                    <h3 class="text-primary mb-3"><?= $progress ?>%</h3>
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <?php if (!empty($course) && is_array($course)): ?>
                        <a href="?url=course/detail/<?= $course['id'] ?>" class="btn btn-outline-secondary btn-sm">Về trang khóa học</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
