<?php
require_once 'config.php';

$page_title = 'ویرایش کتاب';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'] ?? null;
    $publication_year = $_POST['publication_year'] ?? null;
    $isbn = $_POST['isbn'] ?? null;
    $genre = $_POST['genre'] ?? null;
    $edition = $_POST['edition'] ?? null;
    $pages = $_POST['pages'] ?? null;
    $language = $_POST['language'] ?? null;
    $total_copies = (int)$_POST['total_copies'];
    $available_copies = (int)$_POST['available_copies'];
    
    // Update library_items
    $stmt = $conn->prepare("UPDATE library_items SET title=?, publisher=?, publication_year=?, isbn=?, total_copies=?, available_copies=? WHERE item_id=?");
    $stmt->bind_param("ssisiii", $title, $publisher, $publication_year, $isbn, $total_copies, $available_copies, $item_id);
    $stmt->execute();
    
    // Update books
    $stmt2 = $conn->prepare("UPDATE books SET author=?, genre=?, edition=?, pages=?, language=? WHERE book_id=?");
    $stmt2->bind_param("ssiisi", $author, $genre, $edition, $pages, $language, $item_id);
    $stmt2->execute();
    
    header("Location: books.php?success=1");
    exit;
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-pencil"></i> ویرایش کتاب</h1>
    <p>اطلاعات کتاب را ویرایش کنید</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">فرم ویرایش کتاب</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">عنوان کتاب <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                            <div class="invalid-feedback">لطفا عنوان کتاب را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="author" class="form-label">نویسنده <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                            <div class="invalid-feedback">لطفا نام نویسنده را وارد کنید.</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="publisher" class="form-label">ناشر</label>
                            <input type="text" class="form-control" id="publisher" name="publisher" value="<?php echo htmlspecialchars($book['publisher'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="publication_year" class="form-label">سال انتشار</label>
                            <input type="number" class="form-control" id="publication_year" name="publication_year" value="<?php echo htmlspecialchars($book['publication_year'] ?? ''); ?>" min="1000" max="2099">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="isbn" class="form-label">شابک (ISBN)</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['isbn'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="genre" class="form-label">ژانر</label>
                            <input type="text" class="form-control" id="genre" name="genre" value="<?php echo htmlspecialchars($book['genre'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="edition" class="form-label">چاپ</label>
                            <input type="number" class="form-control" id="edition" name="edition" value="<?php echo htmlspecialchars($book['edition'] ?? ''); ?>" min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pages" class="form-label">تعداد صفحات</label>
                            <input type="number" class="form-control" id="pages" name="pages" value="<?php echo htmlspecialchars($book['pages'] ?? ''); ?>" min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="language" class="form-label">زبان</label>
                            <input type="text" class="form-control" id="language" name="language" value="<?php echo htmlspecialchars($book['language'] ?? 'فارسی'); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="total_copies" class="form-label">تعداد کل نسخه <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="total_copies" name="total_copies" value="<?php echo $book['total_copies']; ?>" min="1" required>
                            <div class="invalid-feedback">لطفا تعداد نسخه را وارد کنید.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="available_copies" class="form-label">تعداد موجود <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="available_copies" name="available_copies" value="<?php echo $book['available_copies']; ?>" min="0" max="<?php echo $book['total_copies']; ?>" required>
                            <div class="invalid-feedback">تعداد موجود نمی‌تواند بیشتر از تعداد کل باشد.</div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> ذخیره تغییرات
                        </button>
                        <a href="books.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> انصراف
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

