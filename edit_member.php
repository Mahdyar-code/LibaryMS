<?php
require_once 'config.php';

$page_title = 'ویرایش عضو';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? null;
    $address = $_POST['address'] ?? null;
    $membership_number = $_POST['membership_number'];
    $membership_type = $_POST['membership_type'];
    $membership_start_date = $_POST['membership_start_date'];
    $membership_end_date = $_POST['membership_end_date'];
    $max_borrow_limit = (int)$_POST['max_borrow_limit'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Update persons
    $stmt = $conn->prepare("UPDATE persons SET first_name=?, last_name=?, email=?, phone=?, address=? WHERE person_id=?");
    $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone, $address, $member_id);
    $stmt->execute();
    
    // Update members
    $stmt2 = $conn->prepare("UPDATE members SET membership_number=?, membership_type=?, membership_start_date=?, membership_end_date=?, max_borrow_limit=?, is_active=? WHERE member_id=?");
    $stmt2->bind_param("ssssiii", $membership_number, $membership_type, $membership_start_date, $membership_end_date, $max_borrow_limit, $is_active, $member_id);
    $stmt2->execute();
    
    header("Location: members.php?success=1");
    exit;
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-pencil"></i> ویرایش عضو</h1>
    <p>اطلاعات عضو را ویرایش کنید</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">فرم ویرایش عضو</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <h6 class="mb-3 text-primary">اطلاعات شخصی</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">نام <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($member['first_name']); ?>" required>
                            <div class="invalid-feedback">لطفا نام را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">نام خانوادگی <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($member['last_name']); ?>" required>
                            <div class="invalid-feedback">لطفا نام خانوادگی را وارد کنید.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">ایمیل <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>
                            <div class="invalid-feedback">لطفا ایمیل معتبر وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">تلفن</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($member['phone'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">آدرس</label>
                        <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($member['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <hr class="my-4">
                    <h6 class="mb-3 text-primary">اطلاعات عضویت</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="membership_number" class="form-label">شماره عضویت <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="membership_number" name="membership_number" value="<?php echo htmlspecialchars($member['membership_number']); ?>" required>
                            <div class="invalid-feedback">لطفا شماره عضویت را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="membership_type" class="form-label">نوع عضویت <span class="text-danger">*</span></label>
                            <select class="form-select" id="membership_type" name="membership_type" required>
                                <option value="Student" <?php echo $member['membership_type'] == 'Student' ? 'selected' : ''; ?>>دانشجو</option>
                                <option value="Faculty" <?php echo $member['membership_type'] == 'Faculty' ? 'selected' : ''; ?>>استاد</option>
                                <option value="Public" <?php echo $member['membership_type'] == 'Public' ? 'selected' : ''; ?>>عمومی</option>
                            </select>
                            <div class="invalid-feedback">لطفا نوع عضویت را انتخاب کنید.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="membership_start_date" class="form-label">تاریخ شروع عضویت <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="membership_start_date" name="membership_start_date" value="<?php echo $member['membership_start_date']; ?>" required>
                            <div class="invalid-feedback">لطفا تاریخ شروع را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="membership_end_date" class="form-label">تاریخ پایان عضویت <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="membership_end_date" name="membership_end_date" value="<?php echo $member['membership_end_date']; ?>" required>
                            <div class="invalid-feedback">لطفا تاریخ پایان را وارد کنید.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="max_borrow_limit" class="form-label">حداکثر تعداد امانت <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="max_borrow_limit" name="max_borrow_limit" value="<?php echo $member['max_borrow_limit']; ?>" min="1" required>
                            <div class="invalid-feedback">لطفا حداکثر تعداد امانت را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">وضعیت</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" <?php echo $member['is_active'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_active">فعال</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> ذخیره تغییرات
                        </button>
                        <a href="members.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> انصراف
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

