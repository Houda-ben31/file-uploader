<?php
$url = trim($_POST['url'] ?? '');
if (!$url) die("<p class='error'>❌ لم يتم إدخال الرابط.</p>");

$uploadDir = __DIR__ . '/uploads/';
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

// السماح بنفس الأنواع
$allowedTypes = ['jpg','jpeg','png','gif','pdf','zip','rar','mp3','mp4'];
$maxSize = 10 * 1024 * 1024; // 10 ميجا

// استخراج اسم الملف وامتداده
$filename = basename(parse_url($url, PHP_URL_PATH));
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if (!in_array($ext, $allowedTypes)) die("<p class='error'>⚠️ نوع الملف ($ext) غير مسموح به.</p>");

// تحميل الملف من الرابط
$context = stream_context_create(['http' => ['timeout' => 10]]);
$fileData = @file_get_contents($url, false, $context);
if ($fileData === false) die("<p class='error'>❌ تعذر تحميل الملف من الرابط.</p>");

// التحقق من الحجم
if (strlen($fileData) > $maxSize) die("<p class='error'>⚠️ الملف أكبر من 10MB.</p>");

// توليد اسم عشوائي آمن
$safeName = uniqid('url_', true) . '.' . $ext;
$target = $uploadDir . $safeName;

// حفظ الملف
file_put_contents($target, $fileData);

echo "<p class='success'>✅ تم سحب الملف بنجاح: <a href='uploads/$safeName' target='_blank'>تحميل</a></p>";
echo '<p><a href="index.html">🔙 العودة</a></p>';
?>
