<?php
require_once 'config.php';

$page_title = 'داشبورد - سیستم مدیریت کتابخانه';

// Get statistics
$stats = [];

// Total books
$result = $conn->query("SELECT COUNT(*) as total FROM library_items WHERE item_type = 'Book'");
$stats['total_books'] = $result->fetch_assoc()['total'];

// Total members
$result = $conn->query("SELECT COUNT(*) as total FROM members WHERE is_active = 1");
$stats['total_members'] = $result->fetch_assoc()['total'];

// Active borrowings
$result = $conn->query("SELECT COUNT(*) as total FROM borrowing WHERE status = 'borrowed'");
$stats['active_borrowings'] = $result->fetch_assoc()['total'];

// Overdue borrowings
$result = $conn->query("SELECT COUNT(*) as total FROM borrowing WHERE status = 'overdue'");
$stats['overdue_borrowings'] = $result->fetch_assoc()['total'];

// Total fines pending
$result = $conn->query("SELECT COALESCE(SUM(fine_amount - paid_amount), 0) as total FROM fines WHERE status = 'pending'");
$stats['pending_fines'] = $result->fetch_assoc()['total'];

// Available books
$result = $conn->query("SELECT SUM(available_copies) as total FROM library_items WHERE item_type = 'Book'");
$stats['available_books'] = $result->fetch_assoc()['total'] ?? 0;

// Total ebooks
$result = $conn->query("SELECT COUNT(*) as total FROM library_items WHERE item_type = 'Ebook'");
$stats['total_ebooks'] = $result->fetch_assoc()['total'];

// Total journals
$result = $conn->query("SELECT COUNT(*) as total FROM library_items WHERE item_type = 'Journal'");
$stats['total_journals'] = $result->fetch_assoc()['total'];

// Active reservations
$result = $conn->query("SELECT COUNT(*) as total FROM reservation WHERE status = 'active'");
$stats['active_reservations'] = $result->fetch_assoc()['total'];

// Recent borrowings
$recent_borrowings = $conn->query("
    SELECT b.*, li.title, m.membership_number, p.first_name, p.last_name
    FROM borrowing b
    JOIN library_items li ON b.item_id = li.item_id
    JOIN members m ON b.member_id = m.member_id
    JOIN persons p ON m.member_id = p.person_id
    ORDER BY b.borrow_date DESC
    LIMIT 10
");

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-speedometer2"></i> داشبورد</h1>
    <p>خلاصه آمار و اطلاعات سیستم</p>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <i class="bi bi-book stat-icon"></i>
            <div class="stat-value"><?php echo $stats['total_books']; ?></div>
            <div class="stat-label">کل کتاب‌ها</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card success">
            <i class="bi bi-people stat-icon"></i>
            <div class="stat-value"><?php echo $stats['total_members']; ?></div>
            <div class="stat-label">کل اعضا</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card warning">
            <i class="bi bi-arrow-left-right stat-icon"></i>
            <div class="stat-value"><?php echo $stats['active_borrowings']; ?></div>
            <div class="stat-label">امانت‌های فعال</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card danger">
            <i class="bi bi-exclamation-triangle stat-icon"></i>
            <div class="stat-value"><?php echo $stats['overdue_borrowings']; ?></div>
            <div class="stat-label">امانت‌های معوق</div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card info">
            <i class="bi bi-bookmark-check stat-icon"></i>
            <div class="stat-value"><?php echo $stats['available_books']; ?></div>
            <div class="stat-label">کتاب‌های موجود</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card success">
            <i class="bi bi-file-earmark-pdf stat-icon"></i>
            <div class="stat-value"><?php echo $stats['total_ebooks']; ?></div>
            <div class="stat-label">کتاب‌های الکترونیکی</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card warning">
            <i class="bi bi-journal-text stat-icon"></i>
            <div class="stat-value"><?php echo $stats['total_journals']; ?></div>
            <div class="stat-label">مجلات</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <i class="bi bi-cash-coin stat-icon"></i>
            <div class="stat-value"><?php echo number_format($stats['pending_fines'], 0); ?> تومان</div>
            <div class="stat-label">جریمه‌های پرداخت نشده</div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6 col-sm-6">
        <div class="stat-card info">
            <i class="bi bi-calendar-check stat-icon"></i>
            <div class="stat-value"><?php echo $stats['active_reservations']; ?></div>
            <div class="stat-label">رزروهای فعال</div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="stat-card">
            <i class="bi bi-inbox stat-icon"></i>
            <div class="stat-value"><?php echo $stats['total_books'] + $stats['total_ebooks'] + $stats['total_journals']; ?></div>
            <div class="stat-label">کل آیتم‌های کتابخانه</div>
        </div>
    </div>
</div>

<!-- Recent Borrowings -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> آخرین امانت‌ها</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>عنوان کتاب</th>
                                <th>عضو</th>
                                <th>تاریخ امانت</th>
                                <th>تاریخ سررسید</th>
                                <th>وضعیت</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_borrowings->num_rows > 0): ?>
                                <?php while ($row = $recent_borrowings->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                        <td><?php echo date('Y/m/d', strtotime($row['borrow_date'])); ?></td>
                                        <td><?php echo date('Y/m/d', strtotime($row['due_date'])); ?></td>
                                        <td>
                                            <?php
                                            $status_class = '';
                                            $status_text = '';
                                            switch($row['status']) {
                                                case 'borrowed':
                                                    $status_class = 'bg-primary';
                                                    $status_text = 'امانت گرفته شده';
                                                    break;
                                                case 'returned':
                                                    $status_class = 'bg-success';
                                                    $status_text = 'بازگردانده شده';
                                                    break;
                                                case 'overdue':
                                                    $status_class = 'bg-danger';
                                                    $status_text = 'معوق';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">هیچ امانتی ثبت نشده است</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

