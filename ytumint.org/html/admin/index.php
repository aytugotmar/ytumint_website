<?php
require_once '../config/database.php';

$db = Database::getInstance();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Giriş kontrolü
function checkLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: index.php');
        exit;
    }
}

// Çıkış kontrolü
if (isset($_GET['logout'])) {
    session_destroy();
    setcookie('admin_remember', '', time() - 3600, '/');
    header('Location: index.php');
    exit;
}

// Cookie'den otomatik giriş kontrolü
if (!isset($_SESSION['admin_logged_in']) && isset($_COOKIE['admin_remember'])) {
    $cookieData = json_decode($_COOKIE['admin_remember'], true);
    if ($cookieData && isset($cookieData['username']) && isset($cookieData['token'])) {
        $user = $db->fetchOne("SELECT * FROM admin_users WHERE username = ?", [$cookieData['username']]);
        if ($user && hash_equals(hash('sha256', $user['password']), $cookieData['token'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        }
    }
}

$error = '';

// YouTube video ID'sini ayarlardan çek
$videoSetting = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'youtube_video_id'");
$youtubeVideoId = $videoSetting['setting_value'] ?? 'dQw4w9WgXcQ';

// Giriş formu işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    if (empty($username) || empty($password)) {
        $error = 'Kullanıcı adı ve şifre gereklidir.';
    } else {
        $user = $db->fetchOne("SELECT * FROM admin_users WHERE username = ?", [$username]);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            
            // Beni hatırla işaretliyse cookie oluştur (30 gün)
            if ($remember) {
                $cookieData = json_encode([
                    'username' => $username,
                    'token' => hash('sha256', $user['password'])
                ]);
                setcookie('admin_remember', $cookieData, time() + (30 * 24 * 60 * 60), '/');
            }
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Kullanıcı adı veya şifre hatalı.';
        }
    }
}

// Eğer zaten giriş yapmışsa dashboard'a yönlendir
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi - YTU MINT</title>
    <link rel="stylesheet" href="../assets/css/admin-login.css?v=<?php echo (int)@filemtime(__DIR__ . '/../assets/css/admin-login.css'); ?>">
</head>
<body>
    <!-- YouTube Video Background -->
    <div class="video-background">
        <iframe 
            src="https://www.youtube.com/embed/<?php echo htmlspecialchars($youtubeVideoId); ?>?autoplay=1&mute=0&loop=1&playlist=<?php echo htmlspecialchars($youtubeVideoId); ?>&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3&disablekb=1&fs=0"
            frameborder="0"
            allow="autoplay; encrypted-media"
            allowfullscreen>
        </iframe>
    </div>
    
    <div class="overlay"></div>
    
    <div class="login-container">
        <div class="login-box">
            <div class="login-logo">
                <svg width="80" height="80" viewBox="0 0 100 100">
                    <path d="M50 10 L30 30 Q20 50 30 70 L50 90 L70 70 Q80 50 70 30 Z" fill="#7ed09e"/>
                    <circle cx="40" cy="40" r="3" fill="#4a7c59"/>
                    <circle cx="60" cy="40" r="3" fill="#4a7c59"/>
                    <circle cx="50" cy="60" r="3" fill="#4a7c59"/>
                    <line x1="40" y1="40" x2="50" y2="50" stroke="#4a7c59" stroke-width="2"/>
                    <line x1="60" y1="40" x2="50" y2="50" stroke="#4a7c59" stroke-width="2"/>
                    <line x1="50" y1="50" x2="50" y2="60" stroke="#4a7c59" stroke-width="2"/>
                </svg>
                <h1>YTU MINT</h1>
                <p>Yönetim Paneli Girişi</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form class="login-form" method="POST">
                <div class="form-group">
                    <label for="username">Kullanıcı Adı</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        required
                        autocomplete="username"
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        autocomplete="current-password">
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Beni Hatırla (30 gün)</span>
                    </label>
                </div>
                
                <button type="submit" class="login-btn">Giriş Yap</button>
            </form>
            
            <div class="back-link">
                <a href="/">← Siteye Geri Dön</a>
            </div>
        </div>
    </div>
</body>
</html>
