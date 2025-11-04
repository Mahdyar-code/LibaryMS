<?php
require_once 'config.php';

$page_title = 'افزودن کتاب الکترونیکی جدید';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'] ?? null;
    $publication_year = $_POST['publication_year'] ?? null;
    $isbn = $_POST['isbn'] ?? null;
    $file_format = $_POST['file_format'] ?? null;
    $file_size = $_POST['file_size'] ? (int)$_POST['file_size'] : null;
    $download_link = $_POST['download_link'] ?? null;
    $drm_protect = isset($_POST['drm_protect']) ? 1 : 0;
    
    // Insert into library_items
    $stmt = $conn->prepare("INSERT INTO library_items (title, publisher, publication_year, isbn, item_type, total_copies, available_copies) VALUES (?, ?, ?, ?, 'Ebook', 1, 1)");
    $stmt->bind_param("ssis", $title, $publisher, $publication_year, $isbn);
    $stmt->execute();
    $item_id = $conn->insert_id;
    
    // Insert into ebook
    $stmt2 = $conn->prepare("INSERT INTO ebook (ebook_id, author, file_format, file_size, download_link, drm_protect) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("issisi", $item_id, $author, $file_format, $file_size, $download_link, $drm_protect);
    $stmt2->execute();
    
    header("Location: ebooks.php?success=1");
    exit;
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-plus-circle"></i> افزودن کتاب الکترونیکی جدید</h1>
    <p>اطلاعات کتاب الکترونیکی را وارد کنید</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">فرم افزودن کتاب الکترونیکی</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">عنوان <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                            <div class="invalid-feedback">لطفا عنوان را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="author" class="form-label">نویسنده <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="author" name="author" required>
                            <div class="invalid-feedback">لطفا نام نویسنده را وارد کنید.</div>
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
                            <label for="isbn" class="form-label">شابک (ISBN)</label>
                            <input type="text" class="form-control" id="isbn" name="isbn">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="file_format" class="form-label">فرمت فایل</label>
                            <select class="form-select" id="file_format" name="file_format">
                                <option value="">انتخاب کنید</option>
                                <option value="PDF">PDF</option>
                                <option value="EPUB">EPUB</option>
                                <option value="MOBI">MOBI</option>
                                <option value="AZW">AZW</option>
                                <option value="TXT">TXT</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="file_size" class="form-label">حجم فایل (بایت)</label>
                            <input type="number" class="form-control" id="file_size" name="file_size" min="0">
                            <small class="text-muted">برای مثال: 1048576 برای 1MB</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="download_link" class="form-label">لینک دانلود</label>
                            <input type="url" class="form-control" id="download_link" name="download_link" placeholder="https://...">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="drm_protect" name="drm_protect">
                            <label class="form-check-label" for="drm_protect">
                                محافظت DRM دارد
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> ذخیره
                        </button>
                        <a href="ebooks.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> انصراف
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

