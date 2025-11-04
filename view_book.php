<?php
require_once 'config.php';

$item_id = (int)$_GET['id'];
$book = $conn->query("
    SELECT li.*, b.author, b.genre, b.edition, b.pages, b.language
    FROM library_items li
    JOIN books b ON li.item_id = b.book_id
    WHERE li.item_id = $item_id
")->fetch_assoc();

if (!$book) {
    header("Location: books.php");
    exit;
}

$page_title = 'جزئیات کتاب: ' . $book['title'];

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-book"></i> جزئیات کتاب</h1>
    <p>اطلاعات کامل کتاب</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo htmlspecialchars($book['title']); ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>عنوان:</strong>
                        <p><?php echo htmlspecialchars($book['title']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>نویسنده:</strong>
                        <p><?php echo htmlspecialchars($book['author']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>ناشر:</strong>
                        <p><?php echo htmlspecialchars($book['publisher'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>سال انتشار:</strong>
                        <p><?php echo htmlspecialchars($book['publication_year'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>شابک (ISBN):</strong>
                        <p><?php echo htmlspecialchars($book['isbn'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>ژانر:</strong>
                        <p><?php echo htmlspecialchars($book['genre'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>چاپ:</strong>
                        <p><?php echo htmlspecialchars($book['edition'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>تعداد صفحات:</strong>
                        <p><?php echo htmlspecialchars($book['pages'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>زبان:</strong>
                        <p><?php echo htmlspecialchars($book['language'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تعداد کل نسخه:</strong>
                        <p><span class="badge bg-secondary"><?php echo $book['total_copies']; ?></span></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تعداد موجود:</strong>
                        <p>
                            <?php if ($book['available_copies'] > 0): ?>
                                <span class="badge bg-success"><?php echo $book['available_copies']; ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger">0</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>تاریخ افزودن:</strong>
                        <p><?php echo date('Y/m/d H:i', strtotime($book['date_added'])); ?></p>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-4">
                    <a href="edit_book.php?id=<?php echo $book['item_id']; ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> ویرایش
                    </a>
                    <a href="books.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-right"></i> بازگشت
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

