<?php
require_once 'config.php';

$member_id = (int)$_GET['id'];
$member = $conn->query("
    SELECT m.*, p.first_name, p.last_name, p.email, p.phone, p.address
    FROM members m
    JOIN persons p ON m.member_id = p.person_id
    WHERE m.member_id = $member_id
")->fetch_assoc();

if (!$member) {
    header("Location: members.php");
    exit;
}

// Get active borrowings
$active_borrowings = $conn->query("
    SELECT COUNT(*) as count FROM borrowing 
    WHERE member_id = $member_id AND status = 'borrowed'
")->fetch_assoc()['count'];

$page_title = 'جزئیات عضو: ' . $member['first_name'] . ' ' . $member['last_name'];

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-person"></i> جزئیات عضو</h1>
    <p>اطلاعات کامل عضو</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>نام:</strong>
                        <p><?php echo htmlspecialchars($member['first_name']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>نام خانوادگی:</strong>
                        <p><?php echo htmlspecialchars($member['last_name']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>ایمیل:</strong>
                        <p><?php echo htmlspecialchars($member['email']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تلفن:</strong>
                        <p><?php echo htmlspecialchars($member['phone'] ?? '-'); ?></p>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>آدرس:</strong>
                        <p><?php echo htmlspecialchars($member['address'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>شماره عضویت:</strong>
                        <p><?php echo htmlspecialchars($member['membership_number']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>نوع عضویت:</strong>
                        <p>
                            <?php
                            $type_class = ['Student' => 'bg-info', 'Faculty' => 'bg-warning', 'Public' => 'bg-secondary'];
                            $type_text = ['Student' => 'دانشجو', 'Faculty' => 'استاد', 'Public' => 'عمومی'];
                            $type = $member['membership_type'];
                            ?>
                            <span class="badge <?php echo $type_class[$type]; ?>"><?php echo $type_text[$type]; ?></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تاریخ شروع عضویت:</strong>
                        <p><?php echo date('Y/m/d', strtotime($member['membership_start_date'])); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تاریخ پایان عضویت:</strong>
                        <p><?php echo date('Y/m/d', strtotime($member['membership_end_date'])); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>حداکثر تعداد امانت:</strong>
                        <p><span class="badge bg-secondary"><?php echo $member['max_borrow_limit']; ?></span></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>وضعیت:</strong>
                        <p>
                            <?php if ($member['is_active']): ?>
                                <span class="badge bg-success">فعال</span>
                            <?php else: ?>
                                <span class="badge bg-danger">غیرفعال</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-4">
                    <a href="edit_member.php?id=<?php echo $member['member_id']; ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> ویرایش
                    </a>
                    <a href="members.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-right"></i> بازگشت
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">آمار</h5>
            </div>
            <div class="card-body">
                <div class="stat-card info">
                    <i class="bi bi-book stat-icon"></i>
                    <div class="stat-value"><?php echo $active_borrowings; ?></div>
                    <div class="stat-label">امانت‌های فعال</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

