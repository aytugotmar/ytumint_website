<?php
// Admin sidebar logosu ve favicon için HTML oluştur
function renderAdminSidebar($siteLogo = '', $siteFavicon = '') {
    ?>
    <?php if (!empty($siteFavicon)): ?>
    <link rel="icon" type="image/x-icon" href="../<?php echo htmlspecialchars($siteFavicon); ?>">
    <?php endif; ?>
    <?php
}

function renderSidebarLogo($siteLogo = '') {
    if (!empty($siteLogo)): ?>
        <img src="../<?php echo htmlspecialchars($siteLogo); ?>" alt="YTU MINT" style="width: 80px; height: 80px; object-fit: contain;">
    <?php else: ?>
        <svg width="80" height="80" viewBox="0 0 100 100">
            <path d="M50 10 L30 30 Q20 50 30 70 L50 90 L70 70 Q80 50 70 30 Z" fill="#7ed09e"/>
            <circle cx="40" cy="40" r="3" fill="#4a7c59"/>
            <circle cx="60" cy="40" r="3" fill="#4a7c59"/>
            <circle cx="50" cy="60" r="3" fill="#4a7c59"/>
            <line x1="40" y1="40" x2="50" y2="50" stroke="#4a7c59" stroke-width="2"/>
            <line x1="60" y1="40" x2="50" y2="50" stroke="#4a7c59" stroke-width="2"/>
            <line x1="50" y1="50" x2="50" y2="60" stroke="#4a7c59" stroke-width="2"/>
        </svg>
    <?php endif;
}
?>
