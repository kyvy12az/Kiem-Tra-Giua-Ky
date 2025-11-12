<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Admin - Users</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="assets/img/Avt-Vy.jpg" />
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <div>
            <h2 style="margin:0;margin-bottom:8px">Quản lý người dùng</h2>
            <p style="margin:0;color:var(--muted);font-size:14px">Danh sách tất cả người dùng trong hệ thống</p>
        </div>
        <div>
            <a class="btn btn-primary" href="add.php">
                <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
                Thêm người dùng
            </a>
        </div>
    </div>

    <?php
    $users = getAllRecords($conn);
    if (!empty($_GET['message'])) {
        $type = $_GET['type'] ?? 'success';
        $bgColor = $type === 'success' ? '#ecfdf5' : '#fef2f2';
        $textColor = $type === 'success' ? '#065f46' : '#991b1b';
        echo '<div style="margin-bottom:16px;padding:12px 16px;border-radius:8px;background:'.$bgColor.';color:'.$textColor.';display:flex;align-items:center;gap:10px">
            <svg style="width:20px;height:20px" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
            <span>'.htmlspecialchars($_GET['message']).'</span>
        </div>';
    }
    ?>

    <div style="overflow:auto">
        <table class="table-users" role="table">
            <thead>
                <tr>
                    <th style="width:80px">
                        <svg style="width:14px;height:14px;display:inline-block;margin-right:4px" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 3h2v2H3zm4 0h14v2H7zM3 9h2v2H3zm4 0h14v2H7zM3 15h2v2H3zm4 0h14v2H7z"/>
                        </svg>
                        ID
                    </th>
                    <th>
                        <svg style="width:14px;height:14px;display:inline-block;margin-right:4px" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        Tên
                    </th>
                    <th>
                        <svg style="width:14px;height:14px;display:inline-block;margin-right:4px" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        Email
                    </th>
                    <th style="width:220px;text-align:center">
                        <svg style="width:14px;height:14px;display:inline-block;margin-right:4px" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94L14.4 2.81c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.07.62-.07.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                        </svg>
                        Hành động
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" style="text-align:center;padding:40px;color:var(--muted)">
                            <svg style="width:60px;height:60px;opacity:0.3;margin-bottom:12px" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                            </svg>
                            <div>Không có bản ghi nào.</div>
                        </td>
                    </tr>
                <?php else: foreach ($users as $u): ?>
                    <tr>
                        <td><strong>#<?php echo htmlspecialchars($u['admin_id']); ?></strong></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px">
                                <?php if (!empty($u['images']) && file_exists(__DIR__ . '/uploads/' . $u['images'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($u['images']); ?>" alt="Avatar" style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:2px solid var(--border)">
                                <?php else: ?>
                                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:12px">
                                        <?php echo strtoupper(substr($u['admin_name'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                <span><?php echo htmlspecialchars($u['admin_name']); ?></span>
                            </div>
                        </td>
                        <td>
                            <span style="color:var(--muted)"><?php echo htmlspecialchars($u['admin_email']); ?></span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;justify-content:center">
                                <a class="btn btn-outline" href="view.php?id=<?php echo urlencode($u['admin_id']); ?>" style="padding:6px 12px;font-size:13px">
                                    <svg style="width:14px;height:14px" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                    </svg>
                                    Xem
                                </a>
                                <a class="btn btn-outline" href="edit.php?id=<?php echo urlencode($u['admin_id']); ?>" style="padding:6px 12px;font-size:13px">
                                    <svg style="width:14px;height:14px" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                    </svg>
                                    Sửa
                                </a>
                                <a class="btn btn-danger" href="delete.php?id=<?php echo urlencode($u['admin_id']); ?>" data-delete data-name="<?php echo htmlspecialchars($u['admin_name']); ?>" style="padding:6px 12px;font-size:13px">
                                    <svg style="width:14px;height:14px" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                    </svg>
                                    Xóa
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($users)): ?>
        <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
            <span style="color:var(--muted);font-size:14px">
                <svg style="width:16px;height:16px;display:inline-block;margin-right:4px" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                Tổng số: <strong><?php echo count($users); ?></strong> người dùng
            </span>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>