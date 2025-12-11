<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khóa học của tôi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="mb-4 text-primary">Khóa học đã đăng ký</h2>

    <div class="card">
        <div class="card-body">
            <?php if (empty($courses)): ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-3">Bạn chưa đăng ký khóa học nào.</p>
                </div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($courses as $c): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><?= htmlspecialchars($c['title']) ?></h5>
                                <p class="mb-1 text-muted small"><?= htmlspecialchars($c['description'] ?? '') ?></p>
                                <small class="text-muted">Đăng ký: <?= htmlspecialchars($c['enrolled_date']) ?></small>
                            </div>
                            <div class="text-end">
                                <div class="mb-2">
                                    <span class="badge bg-success">Tiến độ: <?= intval($c['progress'] ?? 0) ?>%</span>
                                </div>
                                <a href="?url=student/viewCourse/<?= $c['id'] ?>" class="btn btn-sm btn-primary">Chi tiết</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
