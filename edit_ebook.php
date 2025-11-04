<?php
require_once 'config.php';

$page_title = 'ویرایش کتاب الکترونیکی';

$item_id = (int)$_GET['id'];
$ebook = $conn->query("
    SELECT li.*, e.author, e.file_format, e.file_size, e.download_link, e.drm_protect
    FROM library_items li
    JOIN ebook e ON li.item_id = e.ebook_id
    WHERE li.item_id = $item_id
")->fetch_assoc();

if (!$ebook) {
    header("Location: ebooks.php");
    exit;
}

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
    
    // Update library_items
    $stmt = $conn->prepare("UPDATE library_items SET title=?, publisher=?, publication_year=?, isbn=? WHERE item_id=?");
    $stmt->bind_param("ssisi", $title, $publisher, $publication_year, $isbn, $item_id);
    $stmt->execute();
    
    // Update ebook
    $stmt2 = $conn->prepare("UPDATE ebook SET author=?, file_format=?, file_size=?, download_link=?, drm_protect=? WHERE ebook_id=?");
    $stmt2->bind_param("ssisii", $author, $file_format, $file_size, $download_link, $drm_protect, $item_id);
    $stmt2->execute();
    
    header("Location: ebooks.php?success=1");
    exit;
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-pencil"></i> ویرایش کتاب الکترونیکی</h1>
    <p>اطلاعات کتاب الکترونیکی را ویرایش کنید</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">فرم ویرایش کتاب الکترونیکی</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">عنوان <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($ebook['title']); ?>" required>
                            <div class="invalid-feedback">لطفا عنوان را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="author" class="form-label">نویسنده <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($ebook['author']); ?>" required>
                            <div class="invalid-feedback">لطفا نام نویسنده را وارد کنید.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="publisher" class="form-label">ناشر</label>
                            <input type="text" class="form-control" id="publisher" name="publisher" value="<?php echo htmlspecialchars($ebook['publisher'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="publication_year" class="form-label">سال انتشار</label>
                            <input type="number" class="form-control" id="publication_year" name="publication_year" value="<?php echo htmlspecialchars($ebook['publication_year'] ?? ''); ?>" min="1000" max="2099">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="isbn" class="form-label">شابک (ISBN)</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo htmlspecialchars($ebook['isbn'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="file_format" class="form-label">فرمت فایل</label>
                            <select class="form-select" id="file_format" name="file_format">
                                <option value="">انتخاب کنید</option>
                                <option value="PDF" <?php echo $ebook['file_format'] == 'PDF' ? 'selected' : ''; ?>>PDF</option>
                                <option value="EPUB" <?php echo $ebook['file_format'] == 'EPUB' ? 'selected' : ''; ?>>EPUB</option>
                                <option value="MOBI" <?php echo $ebook['file_format'] == 'MOBI' ? 'selected' : ''; ?>>MOBI</option>
                                <option value="AZW" <?php echo $ebook['file_format'] == 'AZW' ? 'selected' : ''; ?>>AZW</option>
                                <option value="TXT" <?php echo $ebook['file_format'] == 'TXT' ? 'selected' : ''; ?>>TXT</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="file_size" class="form-label">حجم فایل (بایت)</label>
                            <input type="number" class="form-control" id="file_size" name="file_size" value="<?php echo htmlspecialchars($ebook['file_size'] ?? ''); ?>" min="0">
                            <small class="text-muted">برای مثال: 1048576 برای 1MB</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="download_link" class="form-label">لینک دانلود</label>
                            <input type="url" class="form-control" id="download_link" name="download_link" value="<?php echo htmlspecialchars($ebook['download_link'] ?? ''); ?>" placeholder="https://...">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="drm_protect" name="drm_protect" <?php echo $ebook['drm_protect'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="drm_protect">
                                محافظت DRM دارد
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> ذخیره تغییرات
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

