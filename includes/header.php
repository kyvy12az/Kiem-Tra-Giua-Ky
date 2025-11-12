<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Nhân Sự</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="assets/img/Avt-Vy.jpg" />
</head>
<body>
<header class="admin-topbar">
    <div class="brand">
        <a href="index.php" class="brand-link">
            <img src="assets/img/Avt-Vy.jpg" alt="Logo" class="brand-logo">
            <span class="brand-text">Admin Panel</span>
        </a>
    </div>

    <div class="topbar-actions">
        <button id="sidebar-toggle" class="hamburger" aria-label="Toggle sidebar" aria-expanded="true">&#9776;</button>

        <div class="search">
            <input id="top-search" type="search" placeholder="Tìm kiếm...">
        </div>

        <div class="user">
            <img src="assets/img/avatar-V.png" alt="User" class="avatar">
            <span class="username">Kỳ Vỹ DEV</span>
        </div>
    </div>
</header>

<aside class="admin-sidebar" id="admin-sidebar" aria-label="Sidebar">
    <nav>
        <ul>
            <li>
                <a href="index.php" class="nav-link">
                    <span class="nav-icon">
                        <!-- home icon -->
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                            <path d="M3 11.5L12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V11.5z"></path>
                        </svg>
                    </span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="index.php" class="nav-link active">
                    <span class="nav-icon">
                        <!-- users icon -->
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                            <path d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5S14.343 11 16 11zM8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11zM8 13c-2.21 0-6 1.12-6 3.33V19h12v-2.67C14 14.12 10.21 13 8 13zm8 0c-.29 0-.58.02-.86.05 1.16.82 1.86 1.98 1.86 3.28V19h6v-2.67C24 14.12 20.21 13 18 13h-2z"></path>
                        </svg>
                    </span>
                    <span class="nav-text">Người dùng</span>
                </a>
            </li>
            <li>
                <a href="add.php" class="nav-link">
                    <span class="nav-icon">
                        <!-- plus icon -->
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                            <path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"></path>
                        </svg>
                    </span>
                    <span class="nav-text">Thêm người dùng</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<main class="admin-main" id="admin-main">
<?php
?>