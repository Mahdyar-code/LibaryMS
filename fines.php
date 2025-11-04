<?php
require_once 'config.php';

$page_title = 'مدیریت جریمه‌ها';

// Handle payment
if (isset($_GET['pay'])) {
    $fine_id = (int)$_GET['pay'];
    $fine = $conn->query("SELECT * FROM fines WHERE fine_id = $fine_id")->fetch_assoc();
    
    // Update fine to paid
    $conn->query("UPDATE fines SET status = 'paid', paid_amount = fine_amount WHERE fine_id = $fine_id");
    
    header("Location: fines.php?success=1");
    exit;
}

// Handle waive
if (isset($_GET['waive'])) {
    $fine_id = (int)$_GET['waive'];
    $fine = $conn->query("SELECT * FROM fines WHERE fine_id = $fine_id")->fetch_assoc();
    
    // Update fine to waived
    $conn->query("UPDATE fines SET status = 'waived' WHERE fine_id = $fine_id");
    
    header("Location: fines.php?success=1");
    exit;
}

// Get all fines
$fines = $conn->query("
    SELECT f.*, li.title, m.membership_number,
           p.first_name, p.last_name
    FROM fines f
    JOIN borrowing b ON f.borrow_id = b.borrow_id
    JOIN library_items li ON b.item_id = li.item_id
    JOIN members m ON f.member_id = m.member_id
    JOIN persons p ON m.member_id = p.person_id
    ORDER BY f.fine_date DESC
");

// Calculate totals
$total_pending = $conn->query("SELECT COALESCE(SUM(fine_amount - paid_amount), 0) as total FROM fines WHERE status = 'pending'")->fetch_assoc()['total'];
$total_paid = $conn->query("SELECT COALESCE(SUM(paid_amount), 0) as total FROM fines WHERE status = 'paid'")->fetch_assoc()['total'];

include 'includes/header.php';
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> عملیات با موفقیت انجام شد.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="page-header">
    <h1><i class="bi bi-cash-coin"></i> مدیریت جریمه‌ها</h1>
    <p>لیست تمام جریمه‌های کتابخانه</p>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="stat-card warning">
            <i class="bi bi-exclamation-triangle stat-icon"></i>
            <div class="stat-value"><?php echo number_format($total_pending, 0); ?> تومان</div>
            <div class="stat-label">جریمه‌های پرداخت نشده</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card success">
            <i class="bi bi-check-circle stat-icon"></i>
            <div class="stat-value"><?php echo number_format($total_paid, 0); ?> تومان</div>
            <div class="stat-label">جریمه‌های پرداخت شده</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">لیست جریمه‌ها</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>عضو</th>
                        <th>شماره عضویت</th>
                        <th>مبلغ جریمه</th>
                        <th>مبلغ پرداخت شده</th>
                        <th>باقیمانده</th>
                        <th>تاریخ جریمه</th>
                        <th>تاریخ سررسید</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($fines->num_rows > 0): ?>
                        <?php while ($fine = $fines->fetch_assoc()): ?>
                            <?php
                            $remaining = $fine['fine_amount'] - $fine['paid_amount'];
                            $status_class = '';
                            $status_text = '';
                            switch($fine['status']) {
                                case 'pending':
                                    $status_class = 'bg-warning';
                                    $status_text = 'در انتظار پرداخت';
                                    break;
                                case 'paid':
                                    $status_class = 'bg-success';
                                    $status_text = 'پرداخت شده';
                                    break;
                                case 'waived':
                                    $status_class = 'bg-secondary';
                                    $status_text = 'بخشیده شده';
                                    break;
                            }
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fine['title']); ?></td>
                                <td><?php echo htmlspecialchars($fine['first_name'] . ' ' . $fine['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($fine['membership_number']); ?></td>
                                <td><strong><?php echo number_format($fine['fine_amount'], 0); ?> تومان</strong></td>
                                <td><?php echo number_format($fine['paid_amount'], 0); ?> تومان</td>
                                <td>
                                    <?php if ($remaining > 0): ?>
                                        <span class="badge bg-danger"><?php echo number_format($remaining, 0); ?> تومان</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('Y/m/d', strtotime($fine['fine_date'])); ?></td>
                                <td><?php echo date('Y/m/d', strtotime($fine['due_date'])); ?></td>
                                <td>
                                    <span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                </td>
                                <td class="action-buttons">
                                    <?php if ($fine['status'] == 'pending'): ?>
                                        <a href="fines.php?pay=<?php echo $fine['fine_id']; ?>" 
                                           class="btn btn-sm btn-success"
                                           title="پرداخت">
                                            <i class="bi bi-check-circle"></i>
                                        </a>
                                        <a href="fines.php?waive=<?php echo $fine['fine_id']; ?>" 
                                           class="btn btn-sm btn-secondary"
                                           title="بخشش">
                                            <i class="bi bi-x-octagon"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">
                                <div class="empty-state">
                                    <i class="bi bi-cash-coin"></i>
                                    <h3>جریمه‌ای ثبت نشده است</h3>
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

