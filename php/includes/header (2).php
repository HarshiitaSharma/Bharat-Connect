<?php
/**
 * header.php
 * ----------
 * Shared site header/nav bar. Was an empty, unused stub before — now it's
 * actually included by index.php and dashboard.php so the header markup
 * only lives in one place.
 *
 * Optional variables a page can set before including this file:
 *   $extraNavHtml       - raw HTML injected into the nav, before the
 *                          login/dashboard/logout links (e.g. a
 *                          "New Request" button on the dashboard).
 *   $hideDashboardLink   - set true on dashboard.php itself, so it
 *                          doesn't show a redundant link to itself.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="site-header">
    <div class="brand">Bharat Connect</div>

    <?php if (isset($_SESSION['user_name'])): ?>
        <div id="currentUserInfo" style="color:#fff;font-size:13px;margin-left:12px">
            Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
        </div>
    <?php endif; ?>

    <nav style="display: flex; align-items: center; gap: 10px;">
        <?php if (!empty($extraNavHtml)) echo $extraNavHtml; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (empty($hideDashboardLink)): ?>
                <a href="/dashboard.php" class="btn">My Dashboard</a>
            <?php endif; ?>
            <a href="/php/logout.php" id="logoutBtn" class="btn btn-secondary">Logout</a>
        <?php else: ?>
            <a href="/login.html" class="btn">Login</a>
            <a href="/register.html" class="btn btn-secondary">Register</a>
        <?php endif; ?>
    </nav>
</header>
