<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int) $_GET['id'];
$record = getRecordById($conn, $id);

if (!$record) {
    header('Location: index.php?message=' . urlencode('Không tìm thấy bản ghi.') . '&type=error');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['admin_name'] ?? '');
    $email = trim($_POST['admin_email'] ?? '');
    $password = trim($_POST['admin_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    // Validation
    if ($name === '' || $email === '') {
        $error = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ.';
    } elseif (emailExists($conn, $email, $id)) {
        $error = 'Email này đã được sử dụng bởi người dùng khác.';
    } elseif (!empty($password) && strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } elseif (!empty($password) && $password !== $confirmPassword) {
        $error = 'Mật khẩu xác nhận không khớp.';
    } else {
        // Handle image upload
        $imageName = $record['images']; // Keep existing image by default
        $deleteOldImage = false;
        
        if (isset($_FILES['images']) && $_FILES['images']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadImage($_FILES['images']);
            if ($uploadResult['success']) {
                $deleteOldImage = !empty($imageName); // Mark old image for deletion
                $imageName = $uploadResult['filename'];
            } else {
                $error = $uploadResult['message'];
            }
        } elseif (isset($_POST['remove_image']) && $_POST['remove_image'] === '1') {
            $deleteOldImage = !empty($imageName);
            $imageName = null;
        }

        if (!isset($error)) {
            $data = [
                'admin_name' => $name,
                'admin_email' => $email,
                'admin_password' => !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : '',
                'images' => $imageName
            ];

            if (updateRecord($conn, $id, $data)) {
                // Delete old image if new one was uploaded
                if ($deleteOldImage && !empty($record['images'])) {
                    deleteImage($record['images']);
                }
                header('Location: index.php?message=' . urlencode('Cập nhật người dùng thành công') . '&type=success');
                exit;
            } else {
                $error = 'Không thể cập nhật bản ghi. Email có thể đã tồn tại.';
                // Delete newly uploaded image if update failed
                if (isset($uploadResult) && $uploadResult['success']) {
                    deleteImage($uploadResult['filename']);
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Chỉnh sửa người dùng — Admin</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="assets/img/Avt-Vy.jpg" />
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card" style="max-width:760px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <div>
            <h2 style="margin:0;margin-bottom:8px">
                <svg style="width:28px;height:28px;display:inline-block;vertical-align:middle;margin-right:8px" viewBox="0 0 24 24" fill="var(--warning)">
                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                </svg>
                Chỉnh sửa người dùng #<?php echo $record['admin_id']; ?>
            </h2>
            <p style="margin:0;color:var(--muted);font-size:14px">Cập nhật thông tin người dùng trong hệ thống.</p>
        </div>
        <div style="display:flex;gap:8px">
            <a class="btn btn-outline" href="view.php?id=<?php echo urlencode($id); ?>">
                <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                </svg>
                Xem
            </a>
            <a class="btn btn-outline" href="index.php">
                <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Quay về
            </a>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div style="margin-bottom:16px;padding:12px 16px;border-radius:8px;background:#fef2f2;color:#991b1b;border-left:4px solid var(--danger);display:flex;align-items:center;gap:10px">
            <svg style="width:20px;height:20px" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" class="form-card" enctype="multipart/form-data">
        <!-- Avatar Upload -->
        <div class="form-group">
            <label>
                <svg style="width:16px;height:16px;display:inline-block;vertical-align:middle;margin-right:4px" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                </svg>
                Ảnh đại diện
            </label>
            <div style="display:flex;align-items:center;gap:20px">
                <div id="preview-container" style="width:100px;height:100px;border-radius:50%;overflow:hidden;border:2px solid var(--border)">
                    <?php if (!empty($record['images']) && file_exists(__DIR__ . '/uploads/' . $record['images'])): ?>
                        <img id="image-preview" src="uploads/<?php echo htmlspecialchars($record['images']); ?>" style="width:100%;height:100%;object-fit:cover">
                    <?php else: ?>
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center">
                            <img id="image-preview" src="" style="width:100%;height:100%;object-fit:cover;display:none">
                            <svg id="placeholder-icon" style="width:50px;height:50px" viewBox="0 0 24 24" fill="white">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div style="flex:1">
                    <input type="file" id="images" name="images" accept="image/*" style="display:none">
                    <input type="hidden" id="remove_image" name="remove_image" value="0">
                    <div style="display:flex;gap:8px;flex-wrap:wrap">
                        <button type="button" class="btn btn-outline" onclick="document.getElementById('images').click()" style="font-size:13px;padding:8px 16px">
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 7v2.99s-1.99.01-2 0V7h-3s.01-1.99 0-2h3V2h2v3h3v2h-3zm-3 4V8h-3V5H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8h-3zM5 19l3-4 2 3 3-4 4 5H5z"/>
                            </svg>
                            Đổi ảnh
                        </button>
                        <?php if (!empty($record['images'])): ?>
                            <button type="button" class="btn btn-danger" onclick="removeImage()" style="font-size:13px;padding:8px 16px">
                                <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                </svg>
                                Xóa ảnh
                            </button>
                        <?php endif; ?>
                    </div>
                    <p style="margin:8px 0 0 0;font-size:12px;color:var(--muted)">JPG, PNG, GIF hoặc WEBP. Tối đa 5MB</p>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="admin_name">
                <svg style="width:16px;height:16px;display:inline-block;vertical-align:middle;margin-right:4px" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                Họ và Tên <span style="color:var(--danger)">*</span>
            </label>
            <input id="admin_name" name="admin_name" type="text" value="<?php echo htmlspecialchars($record['admin_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="admin_email">
                <svg style="width:16px;height:16px;display:inline-block;vertical-align:middle;margin-right:4px" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                Email <span style="color:var(--danger)">*</span>
            </label>
            <input id="admin_email" name="admin_email" type="email" value="<?php echo htmlspecialchars($record['admin_email']); ?>" required>
        </div>

        <div style="background:var(--gray-50);padding:16px;border-radius:8px;margin-bottom:20px">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
                <svg style="width:18px;height:18px;color:var(--info)" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                </svg>
                <strong style="color:var(--dark)">Đổi mật khẩu</strong>
            </div>
            <p style="margin:0 0 16px 0;font-size:13px;color:var(--muted)">Để trống nếu không muốn thay đổi mật khẩu</p>
            
            <div class="form-group" style="margin-bottom:16px">
                <label for="admin_password">
                    <svg style="width:16px;height:16px;display:inline-block;vertical-align:middle;margin-right:4px" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                    </svg>
                    Mật khẩu mới
                </label>
                <input id="admin_password" name="admin_password" type="password" placeholder="Tối thiểu 6 ký tự">
            </div>

            <div class="form-group" style="margin-bottom:0">
                <label for="confirm_password">
                    <svg style="width:16px;height:16px;display:inline-block;vertical-align:middle;margin-right:4px" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                    </svg>
                    Xác nhận mật khẩu mới
                </label>
                <input id="confirm_password" name="confirm_password" type="password" placeholder="Nhập lại mật khẩu">
            </div>
        </div>

        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:24px;padding-top:20px;border-top:1px solid var(--border)">
            <a class="btn btn-outline" href="index.php">
                <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
                Hủy
            </a>
            <button class="btn btn-success" type="submit">
                <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                Lưu thay đổi
            </button>
        </div>
    </form>
</div>

<script>
    // Image preview
    document.getElementById('images').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                const placeholder = document.getElementById('placeholder-icon');
                const container = document.getElementById('preview-container');
                
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
                
                // Update container to show image
                container.innerHTML = '<img id="image-preview" src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover">';
                
                // Reset remove flag
                document.getElementById('remove_image').value = '0';
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove image function
    function removeImage() {
        const container = document.getElementById('preview-container');
        container.innerHTML = `
            <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center">
                <img id="image-preview" src="" style="width:100%;height:100%;object-fit:cover;display:none">
                <svg id="placeholder-icon" style="width:50px;height:50px" viewBox="0 0 24 24" fill="white">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
        `;
        document.getElementById('remove_image').value = '1';
        document.getElementById('images').value = '';
    }

    // Password validation
    const password = document.getElementById('admin_password');
    const confirmPassword = document.getElementById('confirm_password');

    confirmPassword.addEventListener('input', function() {
        if (password.value && this.value !== password.value) {
            this.setCustomValidity('Mật khẩu không khớp');
        } else {
            this.setCustomValidity('');
        }
    });

    password.addEventListener('input', function() {
        if (confirmPassword.value && confirmPassword.value !== this.value) {
            confirmPassword.setCustomValidity('Mật khẩu không khớp');
        } else {
            confirmPassword.setCustomValidity('');
        }
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>