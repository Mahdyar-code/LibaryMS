# راهنمای Push کردن پروژه به GitHub

## مرحله 1: نصب Git (اگر نصب نشده)

1. دانلود Git از: https://git-scm.com/download/win
2. نصب و راه‌اندازی
3. Git Bash را باز کنید

## مرحله 2: ایجاد Repository در GitHub

1. به https://github.com بروید و وارد حساب کاربری شوید
2. روی دکمه **"+"** (بالا راست) کلیک کنید
3. **"New repository"** را انتخاب کنید
4. نام repository را وارد کنید (مثلاً: `library-management-system`)
5. توضیحات (اختیاری): "سیستم مدیریت کتابخانه با PHP و MySQL"
6. **Public** یا **Private** را انتخاب کنید
7. **توجه**: گزینه "Initialize this repository with a README" را **تیک نزنید**
8. روی **"Create repository"** کلیک کنید

## مرحله 3: راه‌اندازی Git در پروژه

### در PowerShell یا Git Bash:

```bash
# رفتن به پوشه پروژه
cd C:\Users\Mahdyar\Desktop\cursor

# بررسی وضعیت Git
git status

# اگر repository وجود ندارد، Git را initialize کنید
git init

# اضافه کردن تمام فایل‌ها (فایل config.php به دلیل .gitignore اضافه نمی‌شود)
git add .

# ثبت تغییرات (commit)
git commit -m "Initial commit: Library Management System"

# اضافه کردن remote repository (آدرس را از GitHub کپی کنید)
# مثال: git remote add origin https://github.com/username/library-management-system.git
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git

# Push کردن به GitHub
git branch -M main
git push -u origin main
```

## مرحله 4: دستورات کامل (کپی کنید)

```bash
cd C:\Users\Mahdyar\Desktop\cursor
git init
git add .
git commit -m "Initial commit: Library Management System"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
git push -u origin main
```

**توجه**: `YOUR_USERNAME` و `YOUR_REPO_NAME` را با اطلاعات خود جایگزین کنید.

## مرحله 5: اگر از قبل Git Repository دارید

اگر قبلاً Git را initialize کرده‌اید:

```bash
git add .
git commit -m "Update: Add journals and ebooks to dashboard"
git push origin main
```

## تنظیمات Git (اولین بار)

اگر اولین بار از Git استفاده می‌کنید:

```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

## نکات مهم:

1. ✅ فایل `config.php` به دلیل `.gitignore` در GitHub push نمی‌شود (امنیت)
2. ✅ فایل `config.example.php` را در GitHub قرار دهید تا دیگران بتوانند از آن استفاده کنند
3. ✅ قبل از push، مطمئن شوید که تمام تغییرات commit شده‌اند
4. ✅ اگر خطای authentication دریافت کردید، از GitHub Personal Access Token استفاده کنید

## حل مشکل Authentication

اگر خطای authentication دریافت کردید:

1. به GitHub بروید → Settings → Developer settings → Personal access tokens → Tokens (classic)
2. روی **"Generate new token"** کلیک کنید
3. نام token را وارد کنید (مثلاً: "LMS Project")
4. گزینه‌های `repo` را تیک بزنید
5. **"Generate token"** را بزنید
6. Token را کپی کنید (فقط یکبار نمایش داده می‌شود!)
7. در هنگام push، به جای password از این token استفاده کنید

## بررسی وضعیت

```bash
# بررسی وضعیت فایل‌ها
git status

# مشاهده تاریخچه commit ها
git log

# مشاهده remote repository
git remote -v
```

## دستورات مفید

```bash
# حذف فایل از Git (اما نگه‌داری در سیستم)
git rm --cached filename

# مشاهده تغییرات
git diff

# بازگرداندن تغییرات
git checkout -- filename

# مشاهده branch ها
git branch
```

---

**موفق باشید!** 🚀

