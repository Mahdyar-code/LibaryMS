<?php
require_once 'config.php';

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

$page_title = 'جزئیات مجله: ' . $journal['journal_name'];

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-journal-text"></i> جزئیات مجله</h1>
    <p>اطلاعات کامل مجله</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo htmlspecialchars($journal['journal_name']); ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>نام مجله:</strong>
                        <p><?php echo htmlspecialchars($journal['journal_name']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>عنوان:</strong>
                        <p><?php echo htmlspecialchars($journal['title']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>ناشر:</strong>
                        <p><?php echo htmlspecialchars($journal['publisher'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>سال انتشار:</strong>
                        <p><?php echo htmlspecialchars($journal['publication_year'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>ISSN:</strong>
                        <p><?php echo htmlspecialchars($journal['issn'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>شابک (ISBN):</strong>
                        <p><?php echo htmlspecialchars($journal['isbn'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>جلد:</strong>
                        <p><?php echo htmlspecialchars($journal['valume'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>شماره:</strong>
                        <p><?php echo htmlspecialchars($journal['issue'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>رشته:</strong>
                        <p><?php echo htmlspecialchars($journal['field_of_study'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تعداد کل نسخه:</strong>
                        <p><span class="badge bg-secondary"><?php echo $journal['total_copies']; ?></span></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تعداد موجود:</strong>
                        <p>
                            <?php if ($journal['available_copies'] > 0): ?>
                                <span class="badge bg-success"><?php echo $journal['available_copies']; ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger">0</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تاریخ افزودن:</strong>
                        <p><?php echo date('Y/m/d H:i', strtotime($journal['date_added'])); ?></p>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-4">
                    <a href="edit_journal.php?id=<?php echo $journal['item_id']; ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> ویرایش
                    </a>
                    <a href="journals.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-right"></i> بازگشت
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

