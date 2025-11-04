<?php
require_once 'config.php';

$page_title = 'مدیریت امانت‌ها';

// Handle return
if (isset($_GET['return'])) {
    $borrow_id = (int)$_GET['return'];
    $return_date = date('Y-m-d H:i:s');
    
    // Get borrowing info
    $borrow = $conn->query("SELECT * FROM borrowing WHERE borrow_id = $borrow_id")->fetch_assoc();
    
    // Update borrowing
    $conn->query("UPDATE borrowing SET status = 'returned', return_date = '$return_date' WHERE borrow_id = $borrow_id");
    
    // Update available copies
    $conn->query("UPDATE library_items SET available_copies = available_copies + 1 WHERE item_id = {$borrow['item_id']}");
    
    header("Location: borrowing.php?success=1");
    exit;
}

// Get all borrowings
$borrowings = $conn->query("
    SELECT b.*, li.title, li.item_type, m.membership_number, 
           p.first_name, p.last_name,
           CASE 
               WHEN b.status = 'borrowed' AND b.due_date < NOW() THEN 'overdue'
               ELSE b.status
           END as actual_status
    FROM borrowing b
    JOIN library_items li ON b.item_id = li.item_id
    JOIN members m ON b.member_id = m.member_id
    JOIN persons p ON m.member_id = p.person_id
    ORDER BY b.borrow_date DESC
");

include 'includes/header.php';
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> عملیات با موفقیت انجام شد.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="page-header">
    <h1><i class="bi bi-arrow-left-right"></i> مدیریت امانت‌ها</h1>
    <p>لیست تمام امانت‌های کتابخانه</p>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="borrow_item.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> ثبت امانت جدید
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">لیست امانت‌ها</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>عضو</th>
                        <th>شماره عضویت</th>
                        <th>تاریخ امانت</th>
                        <th>تاریخ سررسید</th>
                        <th>تاریخ بازگشت</th>
                        <th>وضعیت</th>
                        <th>جریمه</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($borrowings->num_rows > 0): ?>
                        <?php while ($borrow = $borrowings->fetch_assoc()): ?>
                            <?php
                            $status = $borrow['actual_status'];
                            $status_class = '';
                            $status_text = '';
                            switch($status) {
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
                            <tr>
                                <td><?php echo htmlspecialchars($borrow['title']); ?></td>
                                <td><?php echo htmlspecialchars($borrow['first_name'] . ' ' . $borrow['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($borrow['membership_number']); ?></td>
                                <td><?php echo date('Y/m/d', strtotime($borrow['borrow_date'])); ?></td>
                                <td><?php echo date('Y/m/d', strtotime($borrow['due_date'])); ?></td>
                                <td><?php echo $borrow['return_date'] ? date('Y/m/d', strtotime($borrow['return_date'])) : '-'; ?></td>
                                <td>
                                    <span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                </td>
                                <td>
                                    <?php if ($borrow['fine_amount'] > 0): ?>
                                        <span class="badge bg-warning"><?php echo number_format($borrow['fine_amount'], 0); ?> تومان</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-buttons">
                                    <?php if ($status == 'borrowed' || $status == 'overdue'): ?>
                                        <a href="borrowing.php?return=<?php echo $borrow['borrow_id']; ?>" 
                                           class="btn btn-sm btn-success btn-return"
                                           title="بازگرداندن">
                                            <i class="bi bi-check-circle"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="view_borrowing.php?id=<?php echo $borrow['borrow_id']; ?>" 
                                       class="btn btn-sm btn-info"
                                       title="مشاهده جزئیات">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="empty-state">
                                    <i class="bi bi-arrow-left-right"></i>
                                    <h3>امانتی ثبت نشده است</h3>
                                    <p>برای شروع، امانت جدیدی ثبت کنید.</p>
                                    <a href="borrow_item.php" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> ثبت امانت
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

