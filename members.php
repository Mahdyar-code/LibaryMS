<?php
require_once 'config.php';

$page_title = 'مدیریت اعضا';

// Handle delete
if (isset($_GET['delete'])) {
    $member_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM members WHERE member_id = $member_id");
    $conn->query("DELETE FROM persons WHERE person_id = $member_id");
    header("Location: members.php?success=1");
    exit;
}

// Get all members
$members = $conn->query("
    SELECT m.*, p.first_name, p.last_name, p.email, p.phone, p.address
    FROM members m
    JOIN persons p ON m.member_id = p.person_id
    ORDER BY p.last_name, p.first_name
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
    <h1><i class="bi bi-people"></i> مدیریت اعضا</h1>
    <p>لیست تمام اعضای کتابخانه</p>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">لیست اعضا</h5>
        <a href="add_member.php" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> افزودن عضو جدید
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>نام و نام خانوادگی</th>
                        <th>شماره عضویت</th>
                        <th>نوع عضویت</th>
                        <th>ایمیل</th>
                        <th>تلفن</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($members->num_rows > 0): ?>
                        <?php while ($member = $members->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($member['membership_number']); ?></td>
                                <td>
                                    <?php
                                    $type_class = ['Student' => 'bg-info', 'Faculty' => 'bg-warning', 'Public' => 'bg-secondary'];
                                    $type_text = ['Student' => 'دانشجو', 'Faculty' => 'استاد', 'Public' => 'عمومی'];
                                    $type = $member['membership_type'];
                                    ?>
                                    <span class="badge <?php echo $type_class[$type]; ?>"><?php echo $type_text[$type]; ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($member['email']); ?></td>
                                <td><?php echo htmlspecialchars($member['phone'] ?? '-'); ?></td>
                                <td>
                                    <?php if ($member['is_active']): ?>
                                        <span class="badge bg-success">فعال</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">غیرفعال</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-buttons">
                                    <a href="edit_member.php?id=<?php echo $member['member_id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="view_member.php?id=<?php echo $member['member_id']; ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="members.php?delete=<?php echo $member['member_id']; ?>" 
                                       class="btn btn-sm btn-danger btn-delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="bi bi-people"></i>
                                    <h3>عضو یافت نشد</h3>
                                    <p>برای شروع، عضو جدیدی اضافه کنید.</p>
                                    <a href="add_member.php" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> افزودن عضو
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

