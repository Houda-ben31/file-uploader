<?php
// إعداد مجلد الحفظ
$uploadDir = __DIR__ . '/uploads/';
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

// السماح فقط بهذه الأنواع
$allowedTypes = ['jpg','jpeg','png','gif','pdf','zip','rar','mp3','mp4'];
$maxSize = 10 * 1024 * 1024; // 10 ميجا

if (!empty($_FILES['files']['name'][0])) {
    foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
        $originalName = $_FILES['files']['name'][$key];
        $fileSize = $_FILES['files']['size'][$key];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // تحقق من النوع والحجم
        if (!in_array($ext, $allowedTypes)) {
            echo "<p class='error'>❌ الملف <b>$originalName</b> غير مسموح به.</p>";
            continue;
        }
        if ($fileSize > $maxSize) {
            echo "<p class='error'>⚠️ الملف <b>$originalName</b> يتجاوز الحد الأقصى (10MB).</p>";
            continue;
        }

        // توليد اسم عشوائي آمن
        $safeName = uniqid('file_', true) . '.' . $ext;
        $targetPath = $uploadDir . $safeName;

        // نقل الملف
        if (move_uploaded_file($tmpName, $targetPath)) {
            $url = "uploads/" . $safeName;
            echo "<p class='success'>✅ تم رفع <b>$originalName</b> بنجاح: <a href='$url' target='_blank'>$url</a></p>";
        } else {
            echo "<p class='error'>❌ فشل رفع الملف <b>$originalName</b>.</p>";
        }
    }
} else {
    echo "<p class='error'>لم يتم اختيار أي ملفات.</p>";
}
echo '<p><a href="index.html">🔙 العودة</a></p>';
?>
