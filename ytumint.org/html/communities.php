<?php
require_once 'config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = Database::getInstance();

$communities = $db->fetchAll("SELECT * FROM communities WHERE is_active = 1 ORDER BY display_order ASC, created_at ASC");

$faviconSetting = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'site_favicon'");
$logoSetting = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'site_logo'");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YTU MINT - Topluluklar</title>
    <?php if (!empty($faviconSetting['setting_value'])): ?>
    <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars($faviconSetting['setting_value']); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo (int)@filemtime(__DIR__ . '/assets/css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <!-- Header -->
    <header class="header visible">
        <div class="container">
            <a href="/" class="logo">
                <?php if (!empty($logoSetting['setting_value'])): ?>
                    <img src="<?php echo htmlspecialchars($logoSetting['setting_value']); ?>" alt="YTU MINT">
                <?php else: ?>
                <svg width="70" height="70" viewBox="0 0 100 100">
                    <path d="M50 10 L30 30 Q20 50 30 70 L50 90 L70 70 Q80 50 70 30 Z" fill="#7ed09e"/>
                    <circle cx="40" cy="40" r="3" fill="#4a7c59"/>
                    <circle cx="60" cy="40" r="3" fill="#4a7c59"/>
                    <circle cx="50" cy="60" r="3" fill="#4a7c59"/>
                    <line x1="40" y1="40" x2="50" y2="50" stroke="#4a7c59" stroke-width="2"/>
                    <line x1="60" y1="40" x2="50" y2="50" stroke="#4a7c59" stroke-width="2"/>
                    <line x1="50" y1="50" x2="50" y2="60" stroke="#4a7c59" stroke-width="2"/>
                </svg>
                <?php endif; ?>
            </a>
            <button class="mobile-menu-toggle" aria-label="Menü">
                <i class="fas fa-bars"></i>
            </button>
            <nav>
                <ul class="nav-menu">
                    <li><a href="/">Ana Sayfa</a></li>
                    <li><a href="about.php">Biz Kimiz?</a></li>
                    <li><a href="communities.php">Topluluklar</a></li>
                    <li><a href="team.php">Ekibimiz</a></li>
                    <li><a href="/#contact">Bize Ulaşın</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Communities List -->
    <section class="section" style="margin-top: 80px; min-height: calc(100vh - 80px);">
        <div class="container">
            <div class="section-header">
                <h2>Topluluklar</h2>
                <p>Tüm komite detayları</p>
            </div>

            <?php if (empty($communities)): ?>
                <div class="card" style="max-width: 800px; margin: 0 auto;">
                    <p>Şu anda görüntülenecek topluluk bulunamadı.</p>
                </div>
            <?php else: ?>
                <div class="communities-details">
                    <?php foreach ($communities as $community): ?>
                        <article class="community-detail">
                            <div class="community-detail-header">
                                <?php if (!empty($community['image']) && file_exists($community['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($community['image']); ?>" alt="<?php echo htmlspecialchars($community['name']); ?>" class="community-detail-image">
                                <?php endif; ?>
                                <h3 class="community-detail-title"><?php echo htmlspecialchars($community['name']); ?></h3>
                            </div>

                            <?php if (!empty($community['description'])): ?>
                                <div class="community-detail-text">
                                    <?php echo nl2br(htmlspecialchars((string)$community['description'])); ?>
                                </div>
                            <?php endif; ?>

                            <div class="community-detail-actions">
                                <?php if (!empty($community['website_url'])): ?>
                                    <a href="<?php echo htmlspecialchars($community['website_url']); ?>" target="_blank" rel="noopener" class="btn">Siteye Git</a>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                <?php
                $footerLogoSetting = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'site_logo'");
                if (!empty($footerLogoSetting['setting_value'])): ?>
                    <img src="<?php echo htmlspecialchars($footerLogoSetting['setting_value']); ?>" alt="YTU MINT" style="width: 100px; height: 100px;">
                <?php else: ?>
                <svg width="100" height="100" viewBox="0 0 100 100">
                    <path d="M50 10 L30 30 Q20 50 30 70 L50 90 L70 70 Q80 50 70 30 Z" fill="#7ed09e"/>
                    <circle cx="40" cy="40" r="3" fill="#4a7c59"/>
                    <circle cx="60" cy="40" r="3" fill="#4a7c59"/>
                    <circle cx="50" cy="60" r="3" fill="#4a7c59"/>
                    <line x1="40" y1="40" x2="50" y2="50" stroke="#4a7c59" stroke-width="2"/>
                    <line x1="60" y1="40" x2="50" y2="50" stroke="#4a7c59" stroke-width="2"/>
                    <line x1="50" y1="50" x2="50" y2="60" stroke="#4a7c59" stroke-width="2"/>
                </svg>
                <?php endif; ?>
            </div>

            <div class="footer-menu">
                <h4>Menü</h4>
                <a href="/">Ana Sayfa</a>
                <a href="about.php">Biz Kimiz?</a>
                <a href="communities.php">Topluluklar</a>
                <a href="team.php">Ekibimiz</a>
                <a href="/#contact">Bize Ulaşın</a>
            </div>

            <div class="social-links">
                <?php
                $twitterUrl = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'twitter_url'");
                $linkedinUrl = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'linkedin_url'");
                $instagramUrl = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'instagram_url'");
                $tiktokUrl = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'tiktok_url'");
                ?>
                <?php if (!empty($twitterUrl['setting_value'])): ?>
                <a href="<?php echo htmlspecialchars($twitterUrl['setting_value']); ?>" target="_blank" rel="noopener"><i class="fa-brands fa-x-twitter"></i></a>
                <?php endif; ?>
                <?php if (!empty($linkedinUrl['setting_value'])): ?>
                <a href="<?php echo htmlspecialchars($linkedinUrl['setting_value']); ?>" target="_blank" rel="noopener"><i class="fab fa-linkedin"></i></a>
                <?php endif; ?>
                <?php if (!empty($instagramUrl['setting_value'])): ?>
                <a href="<?php echo htmlspecialchars($instagramUrl['setting_value']); ?>" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                <?php endif; ?>
                <?php if (!empty($tiktokUrl['setting_value'])): ?>
                <a href="<?php echo htmlspecialchars($tiktokUrl['setting_value']); ?>" target="_blank" rel="noopener"><i class="fab fa-tiktok"></i></a>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> YTU MINT. Tüm hakları saklıdır. DEEPMINT tarafından ❤️ ile geliştirildi.</p>
        </div>
    </footer>

    <script>
        // Mobil menü toggle
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const navMenu = document.querySelector('.nav-menu');

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                const icon = this.querySelector('i');
                if (navMenu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });

            document.querySelectorAll('.nav-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    navMenu.classList.remove('active');
                    const icon = mobileMenuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
            });
        }
    </script>
</body>
</html>
