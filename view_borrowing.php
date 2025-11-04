<?php
require_once 'config.php';

$borrow_id = (int)$_GET['id'];
$borrow = $conn->query("
    SELECT b.*, li.title, li.item_type, li.isbn, li.publisher, li.publication_year,
           m.membership_number, m.membership_type,
           p.first_name, p.last_name, p.email, p.phone
    FROM borrowing b
    JOIN library_items li ON b.item_id = li.item_id
    JOIN members m ON b.member_id = m.member_id
    JOIN persons p ON m.member_id = p.person_id
    WHERE b.borrow_id = $borrow_id
")->fetch_assoc();

if (!$borrow) {
    header("Location: borrowing.php");
    exit;
}

$page_title = 'جزئیات امانت';

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-arrow-left-right"></i> جزئیات امانت</h1>
    <p>اطلاعات کامل امانت</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">اطلاعات امانت</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>عنوان:</strong>
                        <p><?php echo htmlspecialchars($borrow['title']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>نوع:</strong>
                        <p><span class="badge bg-info"><?php echo htmlspecialchars($borrow['item_type']); ?></span></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>عضو:</strong>
                        <p><?php echo htmlspecialchars($borrow['first_name'] . ' ' . $borrow['last_name']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>شماره عضویت:</strong>
                        <p><?php echo htmlspecialchars($borrow['membership_number']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تاریخ امانت:</strong>
                        <p><?php echo date('Y/m/d H:i', strtotime($borrow['borrow_date'])); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تاریخ سررسید:</strong>
                        <p><?php echo date('Y/m/d H:i', strtotime($borrow['due_date'])); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تاریخ بازگشت:</strong>
                        <p><?php echo $borrow['return_date'] ? date('Y/m/d H:i', strtotime($borrow['return_date'])) : '<span class="text-muted">هنوز بازگردانده نشده</span>'; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>وضعیت:</strong>
                        <p>
                            <?php
                            $status = $borrow['status'];
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
                            <span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                        </p>
                    </div>
                    <?php if ($borrow['fine_amount'] > 0): ?>
                        <div class="col-md-6 mb-3">
                            <strong>مبلغ جریمه:</strong>
                            <p><span class="badge bg-warning"><?php echo number_format($borrow['fine_amount'], 0); ?> تومان</span></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">اطلاعات عضو</h5>
            </div>
            <div class="card-body">
                <p><strong>نام:</strong><br><?php echo htmlspecialchars($borrow['first_name'] . ' ' . $borrow['last_name']); ?></p>
                <p><strong>ایمیل:</strong><br><?php echo htmlspecialchars($borrow['email']); ?></p>
                <p><strong>تلفن:</strong><br><?php echo htmlspecialchars($borrow['phone'] ?? '-'); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <?php if ($borrow['status'] == 'borrowed'): ?>
        <a href="borrowing.php?return=<?php echo $borrow['borrow_id']; ?>" class="btn btn-success">
            <i class="bi bi-check-circle"></i> بازگرداندن
        </a>
    <?php endif; ?>
    <a href="borrowing.php" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> بازگشت
    </a>
</div>

<?php include 'includes/footer.php'; ?>

