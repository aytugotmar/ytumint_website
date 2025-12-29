<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YTU MINT - Ekibimiz</title>
    <?php
    require_once 'config/database.php';
    $db = Database::getInstance();
    $faviconSetting = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'site_favicon'");
    if (!empty($faviconSetting['setting_value'])): ?>
    <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars($faviconSetting['setting_value']); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo (int)@filemtime(__DIR__ . '/assets/css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php
    // Yönetim Kurulu
    $boardMembers = $db->fetchAll("
        SELECT * FROM team_members 
        WHERE member_type = 'board' AND is_active = 1 
        ORDER BY display_order ASC, created_at ASC
    ");
    
    // Topluluk Üyeleri (topluluk bazında grupla)
    $communityMembers = $db->fetchAll("
        SELECT tm.*, c.name as community_name, c.display_order as community_order
        FROM team_members tm
        INNER JOIN communities c ON tm.community_id = c.id
        WHERE tm.member_type = 'community' AND tm.is_active = 1 AND c.is_active = 1
        ORDER BY c.display_order ASC, tm.display_order ASC, tm.created_at ASC
    ");
    
    // Topluluk üyelerini topluluk bazında grupla
    $groupedCommunityMembers = [];
    foreach ($communityMembers as $member) {
        $groupedCommunityMembers[$member['community_name']][] = $member;
    }
    
    // Aktif Üyeler
    $activeMembers = $db->fetchAll("
        SELECT * FROM team_members 
        WHERE member_type = 'active' AND is_active = 1 
        ORDER BY display_order ASC, created_at ASC
    ");
    ?>
    
    <!-- Header -->
    <header class="header visible">
        <div class="container">
            <a href="/" class="logo">
                <?php
                $logoSetting = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'site_logo'");
                if (!empty($logoSetting['setting_value'])): ?>
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
                    <li><a href="/about.php">Biz Kimiz?</a></li>
                    <li><a href="/communities.php">Topluluklar</a></li>
                    <li><a href="/team.php">Ekibimiz</a></li>
                    <li><a href="/#contact">Bize Ulaşın</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Team Section -->
    <section class="section" style="margin-top: 80px; min-height: calc(100vh - 80px);">
        <div class="container">
            <div class="section-header">
                <h2>Ekibimiz</h2>
                <p>YTU MINT ailesi</p>
            </div>

            <?php if (!empty($boardMembers)): ?>
            <!-- Yönetim Kurulu -->
            <div style="margin-bottom: 60px;">
                <h3 style="text-align: center; font-size: 32px; color: var(--white); margin-bottom: 40px;">
                    Yönetim Kurulu
                </h3>
                <div class="board-grid">
                    <?php foreach ($boardMembers as $member): ?>
                    <div class="board-member">
                        <?php if (!empty($member['image']) && file_exists($member['image'])): ?>
                            <img src="<?php echo htmlspecialchars($member['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($member['name']); ?>" 
                                 class="member-image">
                        <?php else: ?>
                            <div class="member-image"></div>
                        <?php endif; ?>
                        <div class="member-name"><?php echo htmlspecialchars($member['name']); ?></div>
                        <div class="member-position"><?php echo htmlspecialchars($member['position']); ?></div>
                        <?php
                        $email = trim((string)($member['email'] ?? ''));
                        $linkedin = trim((string)($member['linkedin'] ?? ''));
                        $linkedinUrl = $linkedin;
                        if ($linkedinUrl !== '' && !preg_match('~^https?://~i', $linkedinUrl)) {
                            $linkedinUrl = 'https://' . $linkedinUrl;
                        }
                        $hasEmail = $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL);
                        $hasLinkedin = $linkedinUrl !== '';
                        ?>
                        <?php if ($hasEmail || $hasLinkedin): ?>
                        <div class="member-links">
                            <?php if ($hasEmail): ?>
                                <a href="mailto:<?php echo htmlspecialchars($email); ?>" aria-label="E-posta">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($hasLinkedin): ?>
                                <a href="<?php echo htmlspecialchars($linkedinUrl); ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($groupedCommunityMembers)): ?>
            <!-- Topluluk Üyeleri -->
            <div style="margin-bottom: 60px;">
                <?php foreach ($groupedCommunityMembers as $communityName => $members): ?>
                <div style="margin-bottom: 50px;">
                    <h3 style="text-align: center; font-size: 28px; color: var(--white); margin-bottom: 30px;">
                        <?php echo htmlspecialchars($communityName); ?> Ekibi
                    </h3>
                    <div class="board-grid">
                        <?php foreach ($members as $member): ?>
                        <div class="board-member">
                            <?php if (!empty($member['image']) && file_exists($member['image'])): ?>
                                <img src="<?php echo htmlspecialchars($member['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($member['name']); ?>" 
                                     class="member-image">
                            <?php else: ?>
                                <div class="member-image"></div>
                            <?php endif; ?>
                            <div class="member-name"><?php echo htmlspecialchars($member['name']); ?></div>
                            <div class="member-position"><?php echo htmlspecialchars($member['position']); ?></div>
                            <?php
                            $email = trim((string)($member['email'] ?? ''));
                            $linkedin = trim((string)($member['linkedin'] ?? ''));
                            $linkedinUrl = $linkedin;
                            if ($linkedinUrl !== '' && !preg_match('~^https?://~i', $linkedinUrl)) {
                                $linkedinUrl = 'https://' . $linkedinUrl;
                            }
                            $hasEmail = $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL);
                            $hasLinkedin = $linkedinUrl !== '';
                            ?>
                            <?php if ($hasEmail || $hasLinkedin): ?>
                            <div class="member-links">
                                <?php if ($hasEmail): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($email); ?>" aria-label="E-posta">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if ($hasLinkedin): ?>
                                    <a href="<?php echo htmlspecialchars($linkedinUrl); ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($activeMembers)): ?>
            <!-- Aktif Üyeler -->
            <div style="margin-bottom: 60px;">
                <h3 style="text-align: center; font-size: 28px; color: var(--white); margin-bottom: 30px;">
                    Aktif Üyeler
                </h3>
                <div class="board-grid">
                    <?php foreach ($activeMembers as $member): ?>
                    <div class="board-member">
                        <?php if (!empty($member['image']) && file_exists($member['image'])): ?>
                            <img src="<?php echo htmlspecialchars($member['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($member['name']); ?>" 
                                 class="member-image">
                        <?php else: ?>
                            <div class="member-image"></div>
                        <?php endif; ?>
                        <div class="member-name"><?php echo htmlspecialchars($member['name']); ?></div>
                        <div class="member-position"><?php echo htmlspecialchars($member['position']); ?></div>
                        <?php
                        $email = trim((string)($member['email'] ?? ''));
                        $linkedin = trim((string)($member['linkedin'] ?? ''));
                        $linkedinUrl = $linkedin;
                        if ($linkedinUrl !== '' && !preg_match('~^https?://~i', $linkedinUrl)) {
                            $linkedinUrl = 'https://' . $linkedinUrl;
                        }
                        $hasEmail = $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL);
                        $hasLinkedin = $linkedinUrl !== '';
                        ?>
                        <?php if ($hasEmail || $hasLinkedin): ?>
                        <div class="member-links">
                            <?php if ($hasEmail): ?>
                                <a href="mailto:<?php echo htmlspecialchars($email); ?>" aria-label="E-posta">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($hasLinkedin): ?>
                                <a href="<?php echo htmlspecialchars($linkedinUrl); ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
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
                <a href="<?php echo htmlspecialchars($twitterUrl['setting_value']); ?>" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                <?php endif; ?>
                <?php if (!empty($linkedinUrl['setting_value'])): ?>
                <a href="<?php echo htmlspecialchars($linkedinUrl['setting_value']); ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
                <?php endif; ?>
                <?php if (!empty($instagramUrl['setting_value'])): ?>
                <a href="<?php echo htmlspecialchars($instagramUrl['setting_value']); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                <?php endif; ?>
                <?php if (!empty($tiktokUrl['setting_value'])): ?>
                <a href="<?php echo htmlspecialchars($tiktokUrl['setting_value']); ?>" target="_blank"><i class="fab fa-tiktok"></i></a>
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
            
            // Menü linklerine tıklayınca menüyü kapat
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
