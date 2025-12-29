<?php
// Veritabanı yapılandırması
define('DB_HOST', 'localhost');
define('DB_USER', 'ytumint_user');
define('DB_PASS', 'sifre');
define('DB_NAME', 'ytumint');

// Site ayarları
define('SITE_URL', 'http://ytumint.org');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

// Session ayarları
define('SESSION_LIFETIME', 3600); // 1 saat

// Güvenlik
define('ENCRYPTION_KEY', 'deepmint_secret_key_2024');

// Ortam (varsayılan: production)
// Sunucuda environment variable olarak YTUMINT_ENV=development ayarlarsanız hatalar ekranda görünür.
define('APP_ENV', getenv('YTUMINT_ENV') ?: 'production');

// Hata raporlama
// Production'da ekrana basma kapalı; loglama açık.
ini_set('log_errors', '1');
ini_set('display_errors', APP_ENV === 'development' ? '1' : '0');
ini_set('display_startup_errors', APP_ENV === 'development' ? '1' : '0');
error_reporting(E_ALL);

// Timezone
date_default_timezone_set('Europe/Istanbul');

// Session başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function ytumint_upload_error_message($code) {
    $code = (int)$code;
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'Yüklenen dosya boyutu izin verilen limiti aşıyor.';
        case UPLOAD_ERR_PARTIAL:
            return 'Dosya kısmi yüklendi. Lütfen tekrar deneyin.';
        case UPLOAD_ERR_NO_FILE:
            return 'Dosya seçilmedi.';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Sunucuda geçici klasör (tmp) bulunamadı.';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Sunucu diske yazamadı (permission/owner kontrol edin).';
        case UPLOAD_ERR_EXTENSION:
            return 'Bir PHP eklentisi dosya yüklemeyi durdurdu.';
        default:
            return 'Dosya yükleme hatası (kod: ' . $code . ').';
    }
}

function ytumint_delete_upload_by_relative($relativePath) {
    if (empty($relativePath)) {
        return;
    }
    if (strpos($relativePath, 'uploads/') !== 0) {
        return;
    }

    $rest = substr($relativePath, strlen('uploads/'));
    $abs = rtrim(UPLOAD_PATH, "/\\") . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rest);
    if (is_file($abs)) {
        @unlink($abs);
    }
}

function ytumint_save_upload($fieldName, $prefix, $subdir = '', $allowedExts = null) {
    if ($allowedExts === null) {
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    }

    if (!isset($_FILES[$fieldName]) || empty($_FILES[$fieldName]['name'])) {
        return ['attempted' => false, 'path' => null, 'error' => null];
    }

    $file = $_FILES[$fieldName];
    if ((int)$file['error'] !== UPLOAD_ERR_OK) {
        return ['attempted' => true, 'path' => null, 'error' => ytumint_upload_error_message($file['error'])];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExts = array_map('strtolower', (array)$allowedExts);
    if (!in_array($ext, $allowedExts, true)) {
        return ['attempted' => true, 'path' => null, 'error' => 'Geçersiz dosya uzantısı. İzin verilenler: ' . implode(', ', $allowedExts)];
    }

    $baseDir = rtrim(UPLOAD_PATH, "/\\");
    $subdir = trim((string)$subdir, "/\\");
    $targetDir = $subdir === '' ? $baseDir : ($baseDir . DIRECTORY_SEPARATOR . $subdir);
    $targetDirWithSep = $targetDir . DIRECTORY_SEPARATOR;

    if (!is_dir($targetDir)) {
        if (!@mkdir($targetDir, 0755, true)) {
            return ['attempted' => true, 'path' => null, 'error' => 'Uploads klasörü oluşturulamadı. Sunucuda yazma izni yok.'];
        }
    }
    if (!is_writable($targetDir)) {
        return ['attempted' => true, 'path' => null, 'error' => 'Uploads klasörü yazılabilir değil (permission/owner kontrol edin).'];
    }

    try {
        $suffix = random_int(1000, 9999);
    } catch (Exception $e) {
        $suffix = rand(1000, 9999);
    }
    $filename = $prefix . '_' . time() . '_' . $suffix . '.' . $ext;
    $absPath = $targetDirWithSep . $filename;

    if (!move_uploaded_file($file['tmp_name'], $absPath)) {
        return ['attempted' => true, 'path' => null, 'error' => 'Dosya sunucuya taşınamadı (move_uploaded_file başarısız).'];
    }

    $relative = 'uploads/' . ($subdir === '' ? '' : ($subdir . '/')) . $filename;
    return ['attempted' => true, 'path' => $relative, 'error' => null];
}
?>
