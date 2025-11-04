<?php
require_once 'config.php';

$page_title = 'ثبت امانت جدید';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = (int)$_POST['member_id'];
    $item_id = (int)$_POST['item_id'];
    $due_date = $_POST['due_date'];
    
    // Check if item is available
    $item = $conn->query("SELECT * FROM library_items WHERE item_id = $item_id")->fetch_assoc();
    if ($item['available_copies'] <= 0) {
        $error = "این آیتم در حال حاضر موجود نیست.";
    } else {
        // Check member borrow limit
        $member = $conn->query("SELECT * FROM members WHERE member_id = $member_id")->fetch_assoc();
        $active_borrowings = $conn->query("SELECT COUNT(*) as count FROM borrowing WHERE member_id = $member_id AND status = 'borrowed'")->fetch_assoc()['count'];
        
        if ($active_borrowings >= $member['max_borrow_limit']) {
            $error = "این عضو به حداکثر تعداد امانت مجاز رسیده است.";
        } else {
            // Insert borrowing
            $stmt = $conn->prepare("INSERT INTO borrowing (member_id, item_id, due_date, status) VALUES (?, ?, ?, 'borrowed')");
            $stmt->bind_param("iis", $member_id, $item_id, $due_date);
            $stmt->execute();
            
            // Update available copies
            $conn->query("UPDATE library_items SET available_copies = available_copies - 1 WHERE item_id = $item_id");
            
            header("Location: borrowing.php?success=1");
            exit;
        }
    }
}

// Get all active members
$members = $conn->query("
    SELECT m.*, p.first_name, p.last_name, p.email
    FROM members m
    JOIN persons p ON m.member_id = p.person_id
    WHERE m.is_active = 1
    ORDER BY p.last_name, p.first_name
");

// Get all available items
$items = $conn->query("
    SELECT * FROM library_items
    WHERE available_copies > 0
    ORDER BY title
");

include 'includes/header.php';
?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="page-header">
    <h1><i class="bi bi-plus-circle"></i> ثبت امانت جدید</h1>
    <p>اطلاعات امانت را وارد کنید</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">فرم ثبت امانت</h5>
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
                                        <?php echo htmlspecialchars($item['title'] . ' (' . $item['item_type'] . ')' . ' - موجود: ' . $item['available_copies']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <div class="invalid-feedback">لطفا آیتم را انتخاب کنید.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label">تاریخ سررسید <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="due_date" name="due_date" required>
                            <div class="invalid-feedback">لطفا تاریخ سررسید را وارد کنید.</div>
                            <small class="text-muted">تاریخ امانت به صورت خودکار ثبت می‌شود.</small>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> ثبت امانت
                        </button>
                        <a href="borrowing.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> انصراف
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default due date to 14 days from now
    const dueDateInput = document.getElementById('due_date');
    const now = new Date();
    now.setDate(now.getDate() + 14);
    const dueDate = now.toISOString().slice(0, 16);
    dueDateInput.value = dueDate;
});
</script>

<?php include 'includes/footer.php'; ?>

