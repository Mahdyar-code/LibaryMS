<?php
require_once 'config.php';

$page_title = 'افزودن کتاب جدید';

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
    
    // Insert into library_items
    $stmt = $conn->prepare("INSERT INTO library_items (title, publisher, publication_year, isbn, item_type, total_copies, available_copies) VALUES (?, ?, ?, ?, 'Book', ?, ?)");
    $stmt->bind_param("ssisii", $title, $publisher, $publication_year, $isbn, $total_copies, $total_copies);
    $stmt->execute();
    $item_id = $conn->insert_id;
    
    // Insert into books
    $stmt2 = $conn->prepare("INSERT INTO books (book_id, author, genre, edition, pages, language) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("issiis", $item_id, $author, $genre, $edition, $pages, $language);
    $stmt2->execute();
    
    header("Location: books.php?success=1");
    exit;
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1><i class="bi bi-plus-circle"></i> افزودن کتاب جدید</h1>
    <p>اطلاعات کتاب را وارد کنید</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">فرم افزودن کتاب</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">عنوان کتاب <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                            <div class="invalid-feedback">لطفا عنوان کتاب را وارد کنید.</div>
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
                            <label for="genre" class="form-label">ژانر</label>
                            <input type="text" class="form-control" id="genre" name="genre">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="edition" class="form-label">چاپ</label>
                            <input type="number" class="form-control" id="edition" name="edition" min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pages" class="form-label">تعداد صفحات</label>
                            <input type="number" class="form-control" id="pages" name="pages" min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="language" class="form-label">زبان</label>
                            <input type="text" class="form-control" id="language" name="language" value="فارسی">
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

