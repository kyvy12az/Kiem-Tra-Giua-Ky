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
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Chi tiết người dùng — Admin</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="assets/img/Avt-Vy.jpg" />
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card" style="max-width:760px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <h2 style="margin:0;display:flex;align-items:center;gap:12px;font-size:1.5rem">
            <svg style="width:24px;height:24px" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
            </svg>
            Chi tiết người dùng #<?php echo $record['admin_id']; ?>
        </h2>
        <div style="display:flex;gap:8px">
            <a class="btn btn-outline" href="edit.php?id=<?php echo urlencode($id); ?>" style="font-size:14px;padding:8px 16px">
                <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                </svg>
                Sửa
            </a>
            <a class="btn btn-outline" href="index.php" style="font-size:14px;padding:8px 16px">
                <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Quay về
            </a>
        </div>
    </div>

    <!-- Avatar Circle Large -->
    <div style="text-align:center;margin:40px 0">
        <?php if (!empty($record['images']) && file_exists(__DIR__ . '/uploads/' . $record['images'])): ?>
            <div style="width:120px;height:120px;border-radius:50%;margin:0 auto;overflow:hidden;border:4px solid white;box-shadow:0 10px 30px rgba(102,126,234,0.3)">
                <img src="uploads/<?php echo htmlspecialchars($record['images']); ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover">
            </div>
        <?php else: ?>
            <div style="width:120px;height:120px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:50%;margin:0 auto;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 30px rgba(102,126,234,0.3)">
                <span style="font-size:60px;font-weight:700;color:white"><?php echo strtoupper(substr($record['admin_name'], 0, 1)); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Details Card -->
    <div style="background:white;padding:0;border-radius:12px;border:1px solid var(--border);overflow:hidden">
        <div style="display:grid;grid-template-columns:auto 1fr;gap:0;margin:0">
            <!-- ID Row -->
            <div style="padding:20px 24px;background:var(--gray-50);border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px">
                <svg style="width:18px;height:18px;color:var(--muted)" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 3h2v2H3zm4 0h14v2H7zM3 9h2v2H3zm4 0h14v2H7zM3 15h2v2H3zm4 0h14v2H7z"/>
                </svg>
                <span style="color:var(--muted);font-weight:600;font-size:14px;text-transform:uppercase">ID</span>
            </div>
            <div style="padding:20px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center">
                <strong style="font-size:16px;color:var(--dark)">#<?php echo htmlspecialchars($record['admin_id']); ?></strong>
            </div>

            <!-- Name Row -->
            <div style="padding:20px 24px;background:var(--gray-50);border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px">
                <svg style="width:18px;height:18px;color:var(--muted)" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span style="color:var(--muted);font-weight:600;font-size:14px;text-transform:uppercase">TÊN</span>
            </div>
            <div style="padding:20px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center">
                <strong style="font-size:16px;color:var(--dark)"><?php echo htmlspecialchars($record['admin_name']); ?></strong>
            </div>

            <!-- Email Row -->
            <div style="padding:20px 24px;background:var(--gray-50);display:flex;align-items:center;gap:8px">
                <svg style="width:18px;height:18px;color:var(--muted)" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                <span style="color:var(--muted);font-weight:600;font-size:14px;text-transform:uppercase">EMAIL</span>
            </div>
            <div style="padding:20px 24px;display:flex;align-items:center">
                <strong style="font-size:16px;color:var(--dark)"><?php echo htmlspecialchars($record['admin_email']); ?></strong>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="display:flex;justify-content:space-between;margin-top:24px;gap:10px;padding-top:20px;border-top:1px solid var(--border)">
        <a class="btn btn-outline" href="index.php" style="font-size:14px;padding:10px 20px">
            <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            Quay về
        </a>
        <a class="btn btn-danger" href="delete.php?id=<?php echo urlencode($id); ?>" data-delete data-name="<?php echo htmlspecialchars($record['admin_name']); ?>" style="font-size:14px;padding:10px 20px">
            <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor">
                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
            </svg>
            Xóa người dùng
        </a>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>