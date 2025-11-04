<?php
require_once 'config.php';

$page_title = 'ثبت رزرو جدید';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = (int)$_POST['member_id'];
    $item_id = (int)$_POST['item_id'];
    $priority = (int)$_POST['priority'];
    
    // Insert reservation
    $stmt = $conn->prepare("INSERT INTO reservation (member_id, item_id, priority, status) VALUES (?, ?, ?, 'active')");
    $stmt->bind_param("iii", $member_id, $item_id, $priority);
    $stmt->execute();
    
    header("Location: reservations.php?success=1");
    exit;
}

// Get all active members
$members = $conn->query("
    SELECT m.*, p.first_name, p.last_name
    FROM members m
    JOIN persons p ON m.member_id = p.person_id
    WHERE m.is_active = 1
    ORDER BY p.last_name, p.first_name
");

// Get all items
$items = $conn->query("
    SELECT * FROM library_items
    ORDER BY title
");

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-plus-circle"></i> ثبت رزرو جدید</h1>
    <p>اطلاعات رزرو را وارد کنید</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">فرم ثبت رزرو</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="member_id" class="form-label">عضو <span class="text-danger">*</span></label>
                            <select class="form-select" id="member_id" name="member_id" required>
                                <option value="">انتخاب عضو</option>
                                <?php while ($member = $members->fetch_assoc()): ?>
                                    <option value="<?php echo $member['member_id']; ?>">
                                        <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name'] . ' - ' . $member['membership_number']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <div class="invalid-feedback">لطفا عضو را انتخاب کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="item_id" class="form-label">آیتم کتابخانه <span class="text-danger">*</span></label>
                            <select class="form-select" id="item_id" name="item_id" required>
                                <option value="">انتخاب آیتم</option>
                                <?php while ($item = $items->fetch_assoc()): ?>
                                    <option value="<?php echo $item['item_id']; ?>">
                                        <?php echo htmlspecialchars($item['title'] . ' (' . $item['item_type'] . ')'); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <div class="invalid-feedback">لطفا آیتم را انتخاب کنید.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">اولویت <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="priority" name="priority" min="1" max="10" value="1" required>
                            <small class="text-muted">عدد کمتر = اولویت بیشتر (1 بالاترین اولویت)</small>
                            <div class="invalid-feedback">لطفا اولویت را وارد کنید.</div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> ثبت رزرو
                        </button>
                        <a href="reservations.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> انصراف
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

