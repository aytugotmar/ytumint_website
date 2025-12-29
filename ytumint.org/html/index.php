<?php
require_once 'config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = Database::getInstance();

// Veritabanından verileri çek
$events = $db->fetchAll("SELECT * FROM events WHERE is_active = 1 ORDER BY event_date ASC");
$communities = $db->fetchAll("SELECT * FROM communities WHERE is_active = 1 ORDER BY display_order ASC");
$boardMembers = $db->fetchAll("SELECT * FROM team_members WHERE member_type = 'board' AND is_active = 1 ORDER BY display_order ASC");
$aboutUs = $db->fetchOne("SELECT * FROM about_us LIMIT 1");

// Hero içeriğini çek
$heroTitleRow = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'hero_title'");
$heroTitle = $heroTitleRow['setting_value'] ?? 'YTU MINT\'e Hoş Geldiniz';

$heroSubtitleRow = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'hero_subtitle'");
$heroSubtitle = $heroSubtitleRow['setting_value'] ?? 'Teknoloji ve İnovasyonda Öncü Topluluk';

$heroButtonRow = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'hero_button_text'");
$heroButton = $heroButtonRow['setting_value'] ?? 'Keşfet';

// İletişim formu işleme
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $msg = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($msg)) {
        $message = 'Lütfen tüm alanları doldurun.';
        $messageType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Geçerli bir e-posta adresi girin.';
        $messageType = 'error';
    } else {
        $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
        if ($db->query($sql, [$name, $email, $subject, $msg])) {
            $message = 'Mesajınız başarıyla gönderildi. En kısa sürede size geri dönüş yapacağız.';
            $messageType = 'success';
        } else {
            $message = 'Mesaj gönderilirken bir hata oluştu. Lütfen tekrar deneyin.';
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YTU MINT - Ana Sayfa</title>
    <?php
    $faviconSetting = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'site_favicon'");
    if (!empty($faviconSetting['setting_value'])): ?>
    <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars($faviconSetting['setting_value']); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo (int)@filemtime(__DIR__ . '/assets/css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <!-- Header -->
    <header class="header">
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

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="video-background">
            <iframe 
                id="hero-video"
                src="https://www.youtube.com/embed/<?php 
                $videoSetting = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'youtube_video_id'");
                echo htmlspecialchars($videoSetting['setting_value'] ?? 'dQw4w9WgXcQ'); 
                ?>?autoplay=1&mute=1&loop=1&enablejsapi=1&playlist=<?php echo htmlspecialchars($videoSetting['setting_value'] ?? 'dQw4w9WgXcQ'); ?>&controls=1&showinfo=0&rel=0&modestbranding=1&playsinline=1&disablekb=0&fs=1&iv_load_policy=3"
                frameborder="0" 
                allow="autoplay; encrypted-media; fullscreen" 
                loading="eager"
                allowfullscreen>
            </iframe>
        </div>
    </section>

    <!-- Events Calendar Section -->
    <?php if (!empty($events)): ?>
    <section class="section">
        <div class="container">
            <h2 class="section-title">Etkinlik Takvimimiz</h2>
            <div id="calendar-container">
                <div class="calendar-header">
                    <button id="prevMonth" class="calendar-nav-btn"><i class="fas fa-chevron-left"></i></button>
                    <h3 id="currentMonth"></h3>
                    <button id="nextMonth" class="calendar-nav-btn"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="calendar-weekdays">
                    <div>Pzt</div>
                    <div>Sal</div>
                    <div>Çar</div>
                    <div>Per</div>
                    <div>Cum</div>
                    <div>Cmt</div>
                    <div>Paz</div>
                </div>
                <div id="calendarDays" class="calendar-days"></div>
            </div>
        </div>
    </section>
    
    <!-- Event Details Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 id="modalDate"></h2>
            <div id="modalEvents"></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- About Section -->
    <?php if ($aboutUs): ?>
    <section id="about" class="section about-section">
        <div class="container">
            <h2 class="section-title">Biz Kimiz?</h2>
            <div class="about-content">
                <?php if (!empty($aboutUs['image']) && file_exists($aboutUs['image'])): ?>
                    <img src="<?php echo htmlspecialchars($aboutUs['image']); ?>" alt="Hakkımızda" class="about-image">
                <?php else: ?>
                    <div class="about-image"></div>
                <?php endif; ?>
                <div class="about-text">
                    <p><?php echo nl2br(htmlspecialchars(substr($aboutUs['content'], 0, 400))); ?></p>
                    <a href="about.php" class="read-more">devamını oku...</a>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Communities Section -->
    <?php if (!empty($communities)): ?>
    <section id="communities" class="section">
        <div class="container">
            <h2 class="section-title">Topluluklar</h2>
            <div class="communities-grid">
                <?php foreach ($communities as $community): ?>
                <div class="community-card">
                    <?php if (!empty($community['image']) && file_exists($community['image'])): ?>
                        <img src="<?php echo htmlspecialchars($community['image']); ?>" alt="<?php echo htmlspecialchars($community['name']); ?>" class="community-image">
                    <?php else: ?>
                        <div class="community-image"></div>
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($community['name']); ?></h3>
                    <?php if (!empty($community['website_url'])): ?>
                    <a href="<?php echo htmlspecialchars($community['website_url']); ?>" target="_blank" class="btn">Siteye Git</a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <a href="communities.php" class="cta-button">Daha Fazla Detay</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Board Members Section -->
    <?php if (!empty($boardMembers)): ?>
    <section id="board" class="section">
        <div class="container">
            <h2 class="section-title">Yönetim Kurulu</h2>
            <div class="board-grid">
                <?php foreach ($boardMembers as $member): ?>
                <div class="board-member">
                    <?php if (!empty($member['image']) && file_exists($member['image'])): ?>
                        <img src="<?php echo htmlspecialchars($member['image']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" class="member-image">
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
            <div style="text-align: center; margin-top: 40px;">
                <a href="team.php" class="cta-button">Tümünü Görüntüle</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Contact Section -->
    <section id="contact" class="section contact-section">
        <div class="container">
            <h2 class="section-title">Bize Ulaşın</h2>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>" style="max-width: 600px; margin: 0 auto 30px;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form class="contact-form" method="POST" action="#contact">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Ad Soyad" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="E-posta" required>
                </div>
                <div class="form-group">
                    <input type="text" name="subject" placeholder="Konu" required>
                </div>
                <div class="form-group">
                    <textarea name="message" placeholder="Mesaj" required></textarea>
                </div>
                <div style="text-align: center;">
                    <button type="submit" name="contact_submit" class="submit-btn">Gönder</button>
                </div>
            </form>
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
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Calendar functionality
        const eventsData = <?php echo json_encode($events); ?>;
        let currentDate = new Date();
        
        const monthNames = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran",
            "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"];

        function renderCalendar() {
            const calendarDays = document.getElementById('calendarDays');
            const currentMonthEl = document.getElementById('currentMonth');
            if (!calendarDays || !currentMonthEl) return;

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            currentMonthEl.textContent = `${monthNames[month]} ${year}`;

            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDayOfWeek = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;

            calendarDays.innerHTML = '';
            
            // Empty cells before first day
            for (let i = 0; i < startingDayOfWeek; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day empty';
                calendarDays.appendChild(emptyDay);
            }
            
            // Days of month
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const dayEvents = eventsData.filter(event => event.event_date === dateStr);
                
                if (dayEvents.length > 0) {
                    dayElement.classList.add('has-event');
                    dayElement.innerHTML = `
                        <span class="day-number">${day}</span>
                        <span class="event-indicator">${dayEvents.length}</span>
                    `;
                    dayElement.addEventListener('click', () => showEventDetails(dateStr, dayEvents));
                } else {
                    dayElement.innerHTML = `<span class="day-number">${day}</span>`;
                }
                
                // Highlight today
                const today = new Date();
                if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
                    dayElement.classList.add('today');
                }
                
                calendarDays.appendChild(dayElement);
            }
        }

        function showEventDetails(date, events) {
            const modal = document.getElementById('eventModal');
            const modalDate = document.getElementById('modalDate');
            const modalEvents = document.getElementById('modalEvents');
            
            const dateParts = date.split('-');
            const dateObj = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
            const formattedDate = `${dateParts[2]} ${monthNames[dateObj.getMonth()]} ${dateParts[0]}`;
            
            modalDate.textContent = formattedDate;
            
            modalEvents.innerHTML = events.map(event => `
                <div class="modal-event">
                    ${event.image ? `<img src="${event.image}" alt="${event.title}" class="modal-event-image">` : ''}
                    <div class="modal-event-content">
                        <h3>${event.title}</h3>
                        <p class="event-time"><i class="fas fa-clock"></i> ${event.event_time.substring(0, 5)}</p>
                        <p class="event-description">${event.description}</p>
                    </div>
                </div>
            `).join('');
            
            modal.style.display = 'block';
        }

        const prevMonthBtn = document.getElementById('prevMonth');
        if (prevMonthBtn) {
            prevMonthBtn.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
            });
        }

        const nextMonthBtn = document.getElementById('nextMonth');
        if (nextMonthBtn) {
            nextMonthBtn.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            });
        }

        const closeModalBtn = document.querySelector('.close-modal');
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', () => {
                const modal = document.getElementById('eventModal');
                if (modal) modal.style.display = 'none';
            });
        }

        window.addEventListener('click', (e) => {
            const modal = document.getElementById('eventModal');
            if (modal && e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Initialize calendar (DOM yoksa script kırılmasın)
        renderCalendar();

        // ===============================
        // 1. HEADER SCROLL (ilk yüklemede gizli, kaydırınca görünür)
        // ===============================
        (function() {
            const header = document.querySelector('.header');
            if (!header) return;

            function applyHeaderState() {
                const scrolled = window.scrollY > 0;
                if (scrolled) {
                    header.classList.add('visible');
                } else {
                    header.classList.remove('visible');
                }
            }

            window.addEventListener('scroll', applyHeaderState, { passive: true });
            window.addEventListener('resize', applyHeaderState);
            window.addEventListener('touchmove', applyHeaderState, { passive: true });
            requestAnimationFrame(applyHeaderState);
            applyHeaderState();
        })();

        // ===============================
        // 2. HAMBURGER MENU
        // ===============================
        (function() {
            const btn = document.querySelector('.mobile-menu-toggle');
            const menu = document.querySelector('.nav-menu');
            if (!btn || !menu) return;

            btn.addEventListener('click', function(e) {
                e.preventDefault();
                menu.classList.toggle('active');
                const icon = btn.querySelector('i');
                icon.className = menu.classList.contains('active') ? 'fas fa-times' : 'fas fa-bars';
            });

            // Close on link click
            document.querySelectorAll('.nav-menu a').forEach(function(link) {
                link.addEventListener('click', function() {
                    menu.classList.remove('active');
                    const icon = btn.querySelector('i');
                    icon.className = 'fas fa-bars';
                });
            });

            // Close on outside click
            document.addEventListener('click', function(e) {
                if (!menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.classList.remove('active');
                    const icon = btn.querySelector('i');
                    icon.className = 'fas fa-bars';
                }
            });
        })();

        // ===============================
        // 3. YOUTUBE VIDEO (AUTO-PAUSE ON SCROLL)
        // ===============================
        (function() {
            let player;
            let playing = false;
            const container = document.querySelector('.video-background');
            const heroSection = document.querySelector('.hero');
            if (!container || !heroSection) return;
            let userPaused = false;
            let autoPaused = false;

            function tryEnableSound() {
                if (!player) return;
                try { player.unMute(); } catch (e) {}
                try { player.setVolume(100); } catch (e) {}
            }

            function enableSoundOnFirstGesture() {
                tryEnableSound();
                document.removeEventListener('click', enableSoundOnFirstGesture);
                document.removeEventListener('touchstart', enableSoundOnFirstGesture);
            }

            document.addEventListener('click', enableSoundOnFirstGesture, { once: true });
            document.addEventListener('touchstart', enableSoundOnFirstGesture, { once: true, passive: true });

            function setupObserver() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) {
                            // Scroll ile otomatik duraklat
                            pauseVideo(true);
                        } else {
                            // Geri gelince devam et (kullanıcı manuel durdurmadıysa)
                            if (!userPaused) playVideo();
                        }
                    });
                }, {
                    threshold: 0,
                    rootMargin: '0px'
                });
                observer.observe(heroSection);
            }

            function setupScrollFallback() {
                window.addEventListener('scroll', () => {
                    const rect = heroSection.getBoundingClientRect();
                    const fullyOut = rect.bottom <= 0 || rect.top >= window.innerHeight;
                    if (fullyOut) {
                        pauseVideo(true);
                    } else {
                        if (!userPaused) playVideo();
                    }
                }, { passive: true });
            }

            function pauseVideo(isAuto = false) {
                if (!player) return;
                player.pauseVideo();
                playing = false;
                if (!isAuto) userPaused = true;
                if (isAuto) autoPaused = true;
            }

            function playVideo() {
                if (!player) return;
                // Autoplay'in stabil olması için önce muted başlatıyoruz;
                // tarayıcı izin verirse biraz sonra sesi açmayı deniyoruz.
                player.playVideo();
                playing = true;
                userPaused = false;
                autoPaused = false;
            }

            window.onYouTubeIframeAPIReady = function() {
                player = new YT.Player('hero-video', {
                    events: {
                        onReady: function() {
                            // Sayfa yüklenir yüklenmez başlat (muted autoplay daha güvenilir)
                            try { player.mute(); } catch (e) {}
                            player.playVideo();
                            playing = true;

                            // Kısa gecikmeyle sesi açmayı dene (izinli tarayıcılarda çalışır)
                            setTimeout(() => {
                                tryEnableSound();
                            }, 800);

                            setupObserver();
                            setupScrollFallback();
                        },
                        onStateChange: function(e) {
                            playing = (e.data === YT.PlayerState.PLAYING);

                            // Kullanıcı YouTube kontrollerinden durdurduysa hatırla (auto-pause değilse)
                            if (e.data === YT.PlayerState.PAUSED) {
                                if (!autoPaused) userPaused = true;
                            }

                            if (e.data === YT.PlayerState.ENDED) {
                                playVideo(); // keep looping without showing end screen
                            }
                        }
                    }
                });
            };

            // Load YouTube API if needed
            if (!window.YT || !window.YT.Player) {
                const s = document.createElement('script');
                s.src = 'https://www.youtube.com/iframe_api';
                document.head.appendChild(s);
            }
        })();
    </script>
</body>
</html>
