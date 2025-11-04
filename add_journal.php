<?php
require_once 'config.php';

$page_title = 'افزودن مجله جدید';

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
    
    // Insert into library_items
    $stmt = $conn->prepare("INSERT INTO library_items (title, publisher, publication_year, isbn, item_type, total_copies, available_copies) VALUES (?, ?, ?, ?, 'Journal', ?, ?)");
    $stmt->bind_param("ssisii", $title, $publisher, $publication_year, $isbn, $total_copies, $total_copies);
    $stmt->execute();
    $item_id = $conn->insert_id;
    
    // Insert into journals
    $stmt2 = $conn->prepare("INSERT INTO journals (journal_id, journal_name, valume, issue, issn, field_of_study) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("isiiss", $item_id, $journal_name, $valume, $issue, $issn, $field_of_study);
    $stmt2->execute();
    
    header("Location: journals.php?success=1");
    exit;
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-plus-circle"></i> افزودن مجله جدید</h1>
    <p>اطلاعات مجله را وارد کنید</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">فرم افزودن مجله</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="journal_name" class="form-label">نام مجله <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="journal_name" name="journal_name" required>
                            <div class="invalid-feedback">لطفا نام مجله را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">عنوان <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                            <div class="invalid-feedback">لطفا عنوان را وارد کنید.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="publisher" class="form-label">ناشر</label>
                            <input type="text" class="form-control" id="publisher" name="publisher">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="publication_year" class="form-label">سال انتشار</label>
                            <input type="number" class="form-control" id="publication_year" name="publication_year" min="1000" max="2099">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="issn" class="form-label">ISSN</label>
                            <input type="text" class="form-control" id="issn" name="issn" placeholder="مثال: 1234-5678">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="isbn" class="form-label">شابک (ISBN)</label>
                            <input type="text" class="form-control" id="isbn" name="isbn">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="valume" class="form-label">جلد</label>
                            <input type="number" class="form-control" id="valume" name="valume" min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="issue" class="form-label">شماره</label>
                            <input type="number" class="form-control" id="issue" name="issue" min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="field_of_study" class="form-label">رشته</label>
                            <input type="text" class="form-control" id="field_of_study" name="field_of_study" placeholder="مثال: علوم کامپیوتر">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="total_copies" class="form-label">تعداد نسخه <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="total_copies" name="total_copies" min="1" value="1" required>
                            <div class="invalid-feedback">لطفا تعداد نسخه را وارد کنید.</div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> ذخیره
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

