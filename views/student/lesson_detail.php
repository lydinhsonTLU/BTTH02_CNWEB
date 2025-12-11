<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card">
        <div class="card-body">
            <?php if (empty($lesson) || !is_array($lesson)): ?>
                <div class="alert alert-warning mb-0">Bài học không tồn tại hoặc chưa được tải.</div>
            <?php else: ?>
                <h3 class="mb-3 text-primary"><?= htmlspecialchars($lesson['title'] ?? 'Untitled') ?></h3>
                <?php if (!empty($lesson['content'])): ?>
                    <div class="mb-4"><?= nl2br(htmlspecialchars($lesson['content'])) ?></div>
                <?php endif; ?>
            <?php endif; ?>

            <h5 class="mb-3">Tài liệu</h5>
            <?php if (empty($materials)): ?>
                <p class="text-muted">Chưa có tài liệu cho bài học này.</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($materials as $m): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= htmlspecialchars($m['title'] ?? $m['filename']) ?></strong>
                                <div class="small text-muted"><?= htmlspecialchars($m['type'] ?? '') ?></div>
                            </div>
                            <div>
                                <?php if (!empty($m['url'])): ?>
                                    <a href="<?= htmlspecialchars($m['url']) ?>" class="btn btn-sm btn-primary" target="_blank">Mở</a>
                                <?php else: ?>
                                    <a href="/uploads/<?= htmlspecialchars($m['filename']) ?>" class="btn btn-sm btn-primary" target="_blank">Tải xuống</a>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
