<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .table-actions button {
            margin: 0 2px;
        }
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-0">
            <div class="p-4">
                <h4><i class="fas fa-graduation-cap"></i> Admin Panel</h4>
                <hr class="bg-white">
            </div>
            <nav class="nav flex-column">
                <a class="nav-link active" href="../../controllers/AdminController.php" >
                    <i class="fas fa-users"></i> Người dùng
                </a>
                <a class="nav-link" href="#" >
                    <i class="fas fa-folder"></i> Danh mục khóa học
                </a>
                <a class="nav-link" href="#" >
                    <i class="fas fa-chart-bar"></i> Thống kê
                </a>
                <a class="nav-link" href="#" >
                    <i class="fas fa-check-circle"></i> Phê duyệt khóa học
                </a>

                <hr class="bg-white">
                <a class="nav-link" href="../../index.php">
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
            
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh mục khóa học</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal" onclick="openAddModal()"><i class="fas fa-plus"></i> Thêm danh mục</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên danh mục</th>
                                    <th>Mô tả</th>
                                    <th>Ngày tạo</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($category['id']) ?></td>
                                        <td><?= htmlspecialchars($category['name']) ?></td>
                                        <td><?= htmlspecialchars($category['description']) ?></td>
                                        <td><?= htmlspecialchars($category['created_at']) ?></td>
                                        <td><span class="status-badge status-active">Hoạt động</span></td>
                                        <td>
                                            <div style="display: flex">
                                                <button onclick="openEditModal(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($category['description'], ENT_QUOTES) ?>')" style="margin-right: 10px" type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#categoryModal"><i class="fas fa-edit"></i></button>

                                                <form method="post" action="../controllers/CourseController.php" style="display: inline;">
                                                    <input type="hidden" name="id" value="<?= htmlspecialchars($category['id']) ?>">
                                                    <button onclick="return confirm('Bạn chắc chắn muốn xóa?')" style="margin-right: 10px" type="submit" class="btn btn-outline-danger" name="delete_category"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Thêm danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="../controllers/CourseController.php" id="categoryForm">
                <div class="modal-body">
                    <input type="hidden" name="category_id" id="category_id" value="">
                    <input type="hidden" name="action" id="action" value="add">
                    
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="category_description" name="category_description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Mở modal thêm danh mục
    function openAddModal() {
        document.getElementById('categoryModalLabel').innerText = 'Thêm danh mục';
        document.getElementById('category_id').value = '';
        document.getElementById('action').value = 'add';
        document.getElementById('category_name').value = '';
        document.getElementById('category_description').value = '';
        document.getElementById('submitBtn').innerText = 'Thêm';
    }

    // Mở modal chỉnh sửa danh mục
    function openEditModal(id, name, description) {
        document.getElementById('categoryModalLabel').innerText = 'Chỉnh sửa danh mục';
        document.getElementById('category_id').value = id;
        document.getElementById('action').value = 'edit';
        document.getElementById('category_name').value = name;
        document.getElementById('category_description').value = description;
        document.getElementById('submitBtn').innerText = 'Cập nhật';
    }
</script>
</body>
</html>