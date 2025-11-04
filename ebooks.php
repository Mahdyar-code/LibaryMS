<?php
require_once 'config.php';

$page_title = 'مدیریت کتاب‌های الکترونیکی';

// Handle delete
if (isset($_GET['delete'])) {
    $item_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM ebook WHERE ebook_id = $item_id");
    $conn->query("DELETE FROM library_items WHERE item_id = $item_id");
    header("Location: ebooks.php?success=1");
    exit;
}

// Get all ebooks
$ebooks = $conn->query("
    SELECT li.*, e.author, e.file_format, e.file_size, e.download_link, e.drm_protect
    FROM library_items li
    JOIN ebook e ON li.item_id = e.ebook_id
    WHERE li.item_type = 'Ebook'
    ORDER BY li.title
");

include 'includes/header.php';
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> عملیات با موفقیت انجام شد.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="page-header">
    <h1><i class="bi bi-file-earmark-pdf"></i> مدیریت کتاب‌های الکترونیکی</h1>
    <p>لیست تمام کتاب‌های الکترونیکی</p>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <a href="add_ebook.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> افزودن کتاب الکترونیکی جدید
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">لیست کتاب‌های الکترونیکی</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>نویسنده</th>
                        <th>فرمت فایل</th>
                        <th>حجم فایل</th>
                        <th>محافظت DRM</th>
                        <th>ناشر</th>
                        <th>سال انتشار</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($ebooks->num_rows > 0): ?>
                        <?php while ($ebook = $ebooks->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ebook['title']); ?></td>
                                <td><?php echo htmlspecialchars($ebook['author']); ?></td>
                                <td><span class="badge bg-info"><?php echo htmlspecialchars($ebook['file_format'] ?? '-'); ?></span></td>
                                <td>
                                    <?php 
                                    if ($ebook['file_size']) {
                                        $size_mb = round($ebook['file_size'] / 1024 / 1024, 2);
                                        echo $size_mb . ' MB';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($ebook['drm_protect']): ?>
                                        <span class="badge bg-warning">دارد</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">ندارد</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($ebook['publisher'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($ebook['publication_year'] ?? '-'); ?></td>
                                <td class="action-buttons">
                                    <?php if ($ebook['download_link']): ?>
                                        <a href="<?php echo htmlspecialchars($ebook['download_link']); ?>" 
                                           target="_blank"
                                           class="btn btn-sm btn-success"
                                           title="دانلود">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="edit_ebook.php?id=<?php echo $ebook['item_id']; ?>" 
                                       class="btn btn-sm btn-primary"
                                       title="ویرایش">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="view_ebook.php?id=<?php echo $ebook['item_id']; ?>" 
                                       class="btn btn-sm btn-info"
                                       title="مشاهده">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="ebooks.php?delete=<?php echo $ebook['item_id']; ?>" 
                                       class="btn btn-sm btn-danger btn-delete"
                                       title="حذف">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <h3>کتاب الکترونیکی یافت نشد</h3>
                                    <p>برای شروع، کتاب الکترونیکی جدیدی اضافه کنید.</p>
                                    <a href="add_ebook.php" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> افزودن کتاب الکترونیکی
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

