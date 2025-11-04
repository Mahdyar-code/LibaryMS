<?php
require_once 'config.php';

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

$page_title = 'جزئیات کتاب الکترونیکی: ' . $ebook['title'];

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-file-earmark-pdf"></i> جزئیات کتاب الکترونیکی</h1>
    <p>اطلاعات کامل کتاب الکترونیکی</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo htmlspecialchars($ebook['title']); ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>عنوان:</strong>
                        <p><?php echo htmlspecialchars($ebook['title']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>نویسنده:</strong>
                        <p><?php echo htmlspecialchars($ebook['author']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>ناشر:</strong>
                        <p><?php echo htmlspecialchars($ebook['publisher'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>سال انتشار:</strong>
                        <p><?php echo htmlspecialchars($ebook['publication_year'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>شابک (ISBN):</strong>
                        <p><?php echo htmlspecialchars($ebook['isbn'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>فرمت فایل:</strong>
                        <p><span class="badge bg-info"><?php echo htmlspecialchars($ebook['file_format'] ?? '-'); ?></span></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>حجم فایل:</strong>
                        <p>
                            <?php 
                            if ($ebook['file_size']) {
                                $size_mb = round($ebook['file_size'] / 1024 / 1024, 2);
                                echo $size_mb . ' MB';
                            } else {
                                echo '-';
                            }
                            ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>محافظت DRM:</strong>
                        <p>
                            <?php if ($ebook['drm_protect']): ?>
                                <span class="badge bg-warning">دارد</span>
                            <?php else: ?>
                                <span class="badge bg-success">ندارد</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php if ($ebook['download_link']): ?>
                        <div class="col-12 mb-3">
                            <strong>لینک دانلود:</strong>
                            <p>
                                <a href="<?php echo htmlspecialchars($ebook['download_link']); ?>" 
                                   target="_blank" 
                                   class="btn btn-success">
                                    <i class="bi bi-download"></i> دانلود
                                </a>
                            </p>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-6 mb-3">
                        <strong>تاریخ افزودن:</strong>
                        <p><?php echo date('Y/m/d H:i', strtotime($ebook['date_added'])); ?></p>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-4">
                    <a href="edit_ebook.php?id=<?php echo $ebook['item_id']; ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> ویرایش
                    </a>
                    <a href="ebooks.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-right"></i> بازگشت
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

