<?php
require_once 'config.php';

$page_title = 'مدیریت کتاب‌ها';

// Handle delete
if (isset($_GET['delete'])) {
    $item_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM books WHERE book_id = $item_id");
    $conn->query("DELETE FROM library_items WHERE item_id = $item_id");
    header("Location: books.php?success=1");
    exit;
}

// Get all books
$books = $conn->query("
    SELECT li.*, b.author, b.genre, b.edition, b.pages, b.language
    FROM library_items li
    JOIN books b ON li.item_id = b.book_id
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
    <h1><i class="bi bi-book"></i> مدیریت کتاب‌ها</h1>
    <p>لیست تمام کتاب‌های موجود در کتابخانه</p>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">لیست کتاب‌ها</h5>
        <a href="add_book.php" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> افزودن کتاب جدید
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>نویسنده</th>
                        <th>ژانر</th>
                        <th>ناشر</th>
                        <th>سال انتشار</th>
                        <th>تعداد کل</th>
                        <th>موجود</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($books->num_rows > 0): ?>
                        <?php while ($book = $books->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><?php echo htmlspecialchars($book['genre'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($book['publisher'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($book['publication_year'] ?? '-'); ?></td>
                                <td><span class="badge bg-secondary"><?php echo $book['total_copies']; ?></span></td>
                                <td>
                                    <?php if ($book['available_copies'] > 0): ?>
                                        <span class="badge bg-success"><?php echo $book['available_copies']; ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">0</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-buttons">
                                    <a href="edit_book.php?id=<?php echo $book['item_id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="view_book.php?id=<?php echo $book['item_id']; ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="books.php?delete=<?php echo $book['item_id']; ?>" 
                                       class="btn btn-sm btn-danger btn-delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state">
                                    <i class="bi bi-book"></i>
                                    <h3>کتابی یافت نشد</h3>
                                    <p>برای شروع، کتاب جدیدی اضافه کنید.</p>
                                    <a href="add_book.php" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> افزودن کتاب
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

