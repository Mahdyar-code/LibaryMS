<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'سیستم مدیریت کتابخانه'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-book"></i> سیستم مدیریت کتابخانه
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="bi bi-house-door"></i> داشبورد
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="books.php">
                            <i class="bi bi-book"></i> کتاب‌ها
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="members.php">
                            <i class="bi bi-people"></i> اعضا
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="borrowing.php">
                            <i class="bi bi-arrow-left-right"></i> امانت
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reservations.php">
                            <i class="bi bi-calendar-check"></i> رزروها
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="fines.php">
                            <i class="bi bi-cash-coin"></i> جریمه‌ها
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ebooks.php">
                            <i class="bi bi-file-earmark-pdf"></i> کتاب‌های الکترونیکی
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="journals.php">
                            <i class="bi bi-journal-text"></i> مجلات
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">

