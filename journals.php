<?php
require_once 'config.php';

$page_title = 'مدیریت مجلات';

// Handle delete
if (isset($_GET['delete'])) {
    $item_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM journals WHERE journal_id = $item_id");
    $conn->query("DELETE FROM library_items WHERE item_id = $item_id");
    header("Location: journals.php?success=1");
    exit;
}

// Get all journals
$journals = $conn->query("
    SELECT li.*, j.journal_name, j.valume, j.issue, j.issn, j.field_of_study
    FROM library_items li
    JOIN journals j ON li.item_id = j.journal_id
    WHERE li.item_type = 'Journal'
    ORDER BY j.journal_name
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
    <h1><i class="bi bi-journal-text"></i> مدیریت مجلات</h1>
    <p>لیست تمام مجلات موجود در کتابخانه</p>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">لیست مجلات</h5>
        <a href="add_journal.php" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> افزودن مجله جدید
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>نام مجله</th>
                        <th>عنوان</th>
                        <th>جلد</th>
                        <th>شماره</th>
                        <th>ISSN</th>
                        <th>رشته</th>
                        <th>ناشر</th>
                        <th>سال انتشار</th>
                        <th>تعداد کل</th>
                        <th>موجود</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($journals->num_rows > 0): ?>
                        <?php while ($journal = $journals->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($journal['journal_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($journal['title']); ?></td>
                                <td><?php echo htmlspecialchars($journal['valume'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($journal['issue'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($journal['issn'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($journal['field_of_study'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($journal['publisher'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($journal['publication_year'] ?? '-'); ?></td>
                                <td><span class="badge bg-secondary"><?php echo $journal['total_copies']; ?></span></td>
                                <td>
                                    <?php if ($journal['available_copies'] > 0): ?>
                                        <span class="badge bg-success"><?php echo $journal['available_copies']; ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">0</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-buttons">
                                    <a href="edit_journal.php?id=<?php echo $journal['item_id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="view_journal.php?id=<?php echo $journal['item_id']; ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="journals.php?delete=<?php echo $journal['item_id']; ?>" 
                                       class="btn btn-sm btn-danger btn-delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="text-center">
                                <div class="empty-state">
                                    <i class="bi bi-journal-text"></i>
                                    <h3>مجله‌ای یافت نشد</h3>
                                    <p>برای شروع، مجله جدیدی اضافه کنید.</p>
                                    <a href="add_journal.php" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> افزودن مجله
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

