<?php
require_once 'config.php';

$page_title = 'مدیریت رزروها';

// Handle cancel
if (isset($_GET['cancel'])) {
    $reservation_id = (int)$_GET['cancel'];
    $conn->query("UPDATE reservation SET status = 'cancelled' WHERE reservation_id = $reservation_id");
    header("Location: reservations.php?success=1");
    exit;
}

// Handle fulfill
if (isset($_GET['fulfill'])) {
    $reservation_id = (int)$_GET['fulfill'];
    $conn->query("UPDATE reservation SET status = 'fulfilled' WHERE reservation_id = $reservation_id");
    header("Location: reservations.php?success=1");
    exit;
}

// Get all reservations
$reservations = $conn->query("
    SELECT r.*, li.title, li.item_type, m.membership_number,
           p.first_name, p.last_name
    FROM reservation r
    JOIN library_items li ON r.item_id = li.item_id
    JOIN members m ON r.member_id = m.member_id
    JOIN persons p ON m.member_id = p.person_id
    ORDER BY r.reservation_date DESC
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
    <h1><i class="bi bi-calendar-check"></i> مدیریت رزروها</h1>
    <p>لیست تمام رزروهای کتابخانه</p>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="add_reservation.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> ثبت رزرو جدید
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">لیست رزروها</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>عضو</th>
                        <th>شماره عضویت</th>
                        <th>تاریخ رزرو</th>
                        <th>اولویت</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($reservations->num_rows > 0): ?>
                        <?php while ($reservation = $reservations->fetch_assoc()): ?>
                            <?php
                            $status_class = '';
                            $status_text = '';
                            switch($reservation['status']) {
                                case 'active':
                                    $status_class = 'bg-primary';
                                    $status_text = 'فعال';
                                    break;
                                case 'fulfilled':
                                    $status_class = 'bg-success';
                                    $status_text = 'تکمیل شده';
                                    break;
                                case 'cancelled':
                                    $status_class = 'bg-secondary';
                                    $status_text = 'لغو شده';
                                    break;
                            }
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reservation['title']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['membership_number']); ?></td>
                                <td><?php echo date('Y/m/d H:i', strtotime($reservation['reservation_date'])); ?></td>
                                <td>
                                    <span class="badge bg-info"><?php echo $reservation['priority']; ?></span>
                                </td>
                                <td>
                                    <span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                </td>
                                <td class="action-buttons">
                                    <?php if ($reservation['status'] == 'active'): ?>
                                        <a href="reservations.php?fulfill=<?php echo $reservation['reservation_id']; ?>" 
                                           class="btn btn-sm btn-success"
                                           title="تکمیل">
                                            <i class="bi bi-check-circle"></i>
                                        </a>
                                        <a href="reservations.php?cancel=<?php echo $reservation['reservation_id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           title="لغو">
                                            <i class="bi bi-x-circle"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="bi bi-calendar-check"></i>
                                    <h3>رزروی ثبت نشده است</h3>
                                    <p>برای شروع، رزرو جدیدی ثبت کنید.</p>
                                    <a href="add_reservation.php" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> ثبت رزرو
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

