<?php
require_once 'config.php';

$page_title = 'ویرایش مجله';

$item_id = (int)$_GET['id'];
$journal = $conn->query("
    SELECT li.*, j.journal_name, j.valume, j.issue, j.issn, j.field_of_study
    FROM library_items li
    JOIN journals j ON li.item_id = j.journal_id
    WHERE li.item_id = $item_id
")->fetch_assoc();

if (!$journal) {
    header("Location: journals.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $journal_name = $_POST['journal_name'];
    $publisher = $_POST['publisher'] ?? null;
    $publication_year = $_POST['publication_year'] ?? null;
    $isbn = $_POST['isbn'] ?? null;
    $valume = $_POST['valume'] ?? null;
    $issue = $_POST['issue'] ?? null;
    $issn = $_POST['issn'] ?? null;
    $field_of_study = $_POST['field_of_study'] ?? null;
    $total_copies = (int)$_POST['total_copies'];
    $available_copies = (int)$_POST['available_copies'];
    
    // Update library_items
    $stmt = $conn->prepare("UPDATE library_items SET title=?, publisher=?, publication_year=?, isbn=?, total_copies=?, available_copies=? WHERE item_id=?");
    $stmt->bind_param("ssisiii", $title, $publisher, $publication_year, $isbn, $total_copies, $available_copies, $item_id);
    $stmt->execute();
    
    // Update journals
    $stmt2 = $conn->prepare("UPDATE journals SET journal_name=?, valume=?, issue=?, issn=?, field_of_study=? WHERE journal_id=?");
    $stmt2->bind_param("sissis", $journal_name, $valume, $issue, $issn, $field_of_study, $item_id);
    $stmt2->execute();
    
    header("Location: journals.php?success=1");
    exit;
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-pencil"></i> ویرایش مجله</h1>
    <p>اطلاعات مجله را ویرایش کنید</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">فرم ویرایش مجله</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="journal_name" class="form-label">نام مجله <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="journal_name" name="journal_name" value="<?php echo htmlspecialchars($journal['journal_name']); ?>" required>
                            <div class="invalid-feedback">لطفا نام مجله را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">عنوان <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($journal['title']); ?>" required>
                            <div class="invalid-feedback">لطفا عنوان را وارد کنید.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="publisher" class="form-label">ناشر</label>
                            <input type="text" class="form-control" id="publisher" name="publisher" value="<?php echo htmlspecialchars($journal['publisher'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="publication_year" class="form-label">سال انتشار</label>
                            <input type="number" class="form-control" id="publication_year" name="publication_year" value="<?php echo htmlspecialchars($journal['publication_year'] ?? ''); ?>" min="1000" max="2099">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="issn" class="form-label">ISSN</label>
                            <input type="text" class="form-control" id="issn" name="issn" value="<?php echo htmlspecialchars($journal['issn'] ?? ''); ?>" placeholder="مثال: 1234-5678">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="isbn" class="form-label">شابک (ISBN)</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo htmlspecialchars($journal['isbn'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="valume" class="form-label">جلد</label>
                            <input type="number" class="form-control" id="valume" name="valume" value="<?php echo htmlspecialchars($journal['valume'] ?? ''); ?>" min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="issue" class="form-label">شماره</label>
                            <input type="number" class="form-control" id="issue" name="issue" value="<?php echo htmlspecialchars($journal['issue'] ?? ''); ?>" min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="field_of_study" class="form-label">رشته</label>
                            <input type="text" class="form-control" id="field_of_study" name="field_of_study" value="<?php echo htmlspecialchars($journal['field_of_study'] ?? ''); ?>" placeholder="مثال: علوم کامپیوتر">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="total_copies" class="form-label">تعداد کل نسخه <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="total_copies" name="total_copies" value="<?php echo $journal['total_copies']; ?>" min="1" required>
                            <div class="invalid-feedback">لطفا تعداد نسخه را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="available_copies" class="form-label">تعداد موجود <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="available_copies" name="available_copies" value="<?php echo $journal['available_copies']; ?>" min="0" max="<?php echo $journal['total_copies']; ?>" required>
                            <div class="invalid-feedback">تعداد موجود نمی‌تواند بیشتر از تعداد کل باشد.</div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> ذخیره تغییرات
                        </button>
                        <a href="journals.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> انصراف
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

