<?php
require_once 'config.php';

$page_title = 'افزودن عضو جدید';

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
    
    // Insert into persons
    $stmt = $conn->prepare("INSERT INTO persons (first_name, last_name, email, phone, address, person_type) VALUES (?, ?, ?, ?, ?, 'member')");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $address);
    $stmt->execute();
    $person_id = $conn->insert_id;
    
    // Insert into members
    $stmt2 = $conn->prepare("INSERT INTO members (member_id, membership_number, membership_type, membership_start_date, membership_end_date, max_borrow_limit) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("issssi", $person_id, $membership_number, $membership_type, $membership_start_date, $membership_end_date, $max_borrow_limit);
    $stmt2->execute();
    
    header("Location: members.php?success=1");
    exit;
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-plus-circle"></i> افزودن عضو جدید</h1>
    <p>اطلاعات عضو را وارد کنید</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">فرم افزودن عضو</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <h6 class="mb-3 text-primary">اطلاعات شخصی</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">نام <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                            <div class="invalid-feedback">لطفا نام را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">نام خانوادگی <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                            <div class="invalid-feedback">لطفا نام خانوادگی را وارد کنید.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">ایمیل <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">لطفا ایمیل معتبر وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">تلفن</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">آدرس</label>
                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                    </div>
                    
                    <hr class="my-4">
                    <h6 class="mb-3 text-primary">اطلاعات عضویت</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="membership_number" class="form-label">شماره عضویت <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="membership_number" name="membership_number" required>
                            <div class="invalid-feedback">لطفا شماره عضویت را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="membership_type" class="form-label">نوع عضویت <span class="text-danger">*</span></label>
                            <select class="form-select" id="membership_type" name="membership_type" required>
                                <option value="">انتخاب کنید</option>
                                <option value="Student">دانشجو</option>
                                <option value="Faculty">استاد</option>
                                <option value="Public">عمومی</option>
                            </select>
                            <div class="invalid-feedback">لطفا نوع عضویت را انتخاب کنید.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="membership_start_date" class="form-label">تاریخ شروع عضویت <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="membership_start_date" name="membership_start_date" required>
                            <div class="invalid-feedback">لطفا تاریخ شروع را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="membership_end_date" class="form-label">تاریخ پایان عضویت <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="membership_end_date" name="membership_end_date" required>
                            <div class="invalid-feedback">لطفا تاریخ پایان را وارد کنید.</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="max_borrow_limit" class="form-label">حداکثر تعداد امانت <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="max_borrow_limit" name="max_borrow_limit" min="1" value="5" required>
                        <div class="invalid-feedback">لطفا حداکثر تعداد امانت را وارد کنید.</div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> ذخیره
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

