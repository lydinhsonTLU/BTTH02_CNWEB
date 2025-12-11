<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách học viên</title>
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
        .progress { height: 25px; }
        .student-card {
            border-left: 4px solid #28a745;
            transition: all 0.3s;
        }
        .student-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-users"></i> Danh sách học viên</h2>
                    <p class="text-muted mb-0">Khóa học: <strong><?= htmlspecialchars($course['title']) ?></strong></p>
                </div>
                <a href="<?= BASE_URL ?>controllers/TeacherController.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3><?= count($students) ?></h3>
                            <p class="mb-0">Tổng học viên</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3><?= count(array_filter($students, fn($s) => $s['progress'] >= 70)) ?></h3>
                            <p class="mb-0">Tiến độ ≥ 70%</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3><?= count($students) > 0 ? round(array_sum(array_column($students, 'progress')) / count($students)) : 0 ?>%</h3>
                            <p class="mb-0">Tiến độ trung bình</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Danh sách chi tiết (<?= count($students) ?> học viên)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($students)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-user-slash fa-4x mb-3"></i>
                            <p>Chưa có học viên nào đăng ký khóa học này.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">Họ tên</th>
                                        <th width="20%">Email</th>
                                        <th width="15%">Ngày đăng ký</th>
                                        <th width="30%">Tiến độ</th>
                                        <th width="10%">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $index => $student): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($student['fullname']) ?></strong><br>
                                            <small class="text-muted">@<?= htmlspecialchars($student['username']) ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($student['email']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($student['enrolled_date'])) ?></td>
                                        <td>
                                            <div class="progress">
                                                <?php
                                                $progress = $student['progress'];
                                                $progressClass = 'bg-danger';
                                                if ($progress >= 70) $progressClass = 'bg-success';
                                                elseif ($progress >= 40) $progressClass = 'bg-warning';
                                                ?>
                                                <div class="progress-bar <?= $progressClass ?>" 
                                                     role="progressbar" 
                                                     style="width: <?= $progress ?>%"
                                                     aria-valuenow="<?= $progress ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    <?= $progress ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($student['status'] == 'active'): ?>
                                                <span class="badge bg-success">Đang học</span>
                                            <?php elseif ($student['status'] == 'completed'): ?>
                                                <span class="badge bg-primary">Hoàn thành</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?= $student['status'] ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Detailed Cards View (Alternative) -->
                        <div class="mt-4">
                            <h5 class="mb-3">Chi tiết học viên</h5>
                            <div class="row">
                                <?php foreach ($students as $student): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card student-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="mb-1">
                                                        <i class="fas fa-user-circle"></i> 
                                                        <?= htmlspecialchars($student['fullname']) ?>
                                                    </h5>
                                                    <p class="text-muted small mb-0">
                                                        <i class="fas fa-envelope"></i> <?= htmlspecialchars($student['email']) ?>
                                                    </p>
                                                </div>
                                                <?php if ($student['status'] == 'active'): ?>
                                                    <span class="badge bg-success">Đang học</span>
                                                <?php elseif ($student['status'] == 'completed'): ?>
                                                    <span class="badge bg-primary">Hoàn thành</span>
                                                <?php endif; ?>
                                            </div>
                                            <hr>
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <small class="text-muted">Đăng ký</small>
                                                    <p class="mb-0 fw-bold"><?= date('d/m/Y', strtotime($student['enrolled_date'])) ?></p>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Tiến độ</small>
                                                    <p class="mb-0 fw-bold text-primary"><?= $student['progress'] ?>%</p>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="progress">
                                                    <?php
                                                    $progress = $student['progress'];
                                                    $progressClass = 'bg-danger';
                                                    if ($progress >= 70) $progressClass = 'bg-success';
                                                    elseif ($progress >= 40) $progressClass = 'bg-warning';
                                                    ?>
                                                    <div class="progress-bar <?= $progressClass ?>" 
                                                         role="progressbar" 
                                                         style="width: <?= $progress ?>%">
                                                        <?= $progress ?>%
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
