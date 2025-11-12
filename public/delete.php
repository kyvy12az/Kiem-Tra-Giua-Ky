<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // pass $conn as first argument
    if (deleteRecord($conn, $id)) {
        // return success message + type for toast
        header('Location: index.php?message=' . urlencode('Đã xóa bản ghi thành công') . '&type=success');
        exit();
    } else {
        // return error message
        header('Location: index.php?message=' . urlencode('Xóa bản ghi thất bại') . '&type=error');
        exit();
    }
} else {
    header('Location: index.php?message=' . urlencode('Lỗi') . '&type=error');
    exit();
}
?>