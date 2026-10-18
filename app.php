<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params(['httponly' => true, 'secure' => $secure, 'samesite' => 'Lax']);
    session_start();
}

require_once __DIR__ . '/connect.php';

function e(mixed $value): string { return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void { if (!current_user()) { header('Location: index.php'); exit; } }
function redirect(string $to): never { header('Location: ' . $to); exit; }
function csrf_token(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function verify_csrf(): void { if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) { http_response_code(403); exit('Your session token expired. Go back and try again.'); } }
function flash(string $message, string $type = 'success'): void { $_SESSION['flash'] = ['message' => $message, 'type' => $type]; }
function take_flash(): ?array { $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }
function avatar(array $user): string { return !empty($user['avatar_path']) ? (string)$user['avatar_path'] : 'assets/images/avatar.svg'; }
function upload_image(string $field, string $folder = 'uploads'): ?string {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return null;
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('The upload did not complete. Please try again.');
    if ($file['size'] > 8 * 1024 * 1024) throw new RuntimeException('Images must be 8 MB or smaller.');
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    if (!isset($types[$mime])) throw new RuntimeException('Upload a JPG, PNG, WEBP, or GIF image.');
    $root = __DIR__ . '/' . $folder;
    if (!is_dir($root) && !mkdir($root, 0755, true) && !is_dir($root)) throw new RuntimeException('Upload folder could not be created.');
    $name = bin2hex(random_bytes(16)) . '.' . $types[$mime];
    if (!move_uploaded_file($file['tmp_name'], $root . '/' . $name)) throw new RuntimeException('Could not save the uploaded image.');
    return $folder . '/' . $name;
}
function page_start(string $title, string $active = ''): void {
    $user = current_user(); $flash = take_flash();
    ?>
    <!doctype html><html lang="en" data-theme="light"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#f7f6fc" id="theme-color-meta"><script>try{document.documentElement.dataset.theme=localStorage.getItem('socialpulse-theme')||'light'}catch(e){document.documentElement.dataset.theme='light'}</script><title><?= e($title) ?> · Social Pulse</title><link rel="stylesheet" href="assets/css/app.css"></head><body>
    <header class="topbar"><a class="brand" href="<?= $user ? 'feed.php' : 'index.php' ?>"><span class="brand-mark">S</span><span>social<span class="brand-accent">pulse</span></span></a>
    <?php if ($user): ?><form class="top-search" action="explore.php" method="get"><input type="search" name="q" placeholder="Search people" value="<?= e($_GET['q'] ?? '') ?>" aria-label="Search people"><button aria-label="Search">⌕</button></form><nav class="top-actions"><a class="nav-link <?= $active==='feed'?'active':'' ?>" href="feed.php">Feed</a><a class="nav-link <?= $active==='explore'?'active':'' ?>" href="explore.php">Explore</a><a class="nav-avatar" href="profile.php?u=<?= e($user['username']) ?>"><img src="<?= e(avatar($user)) ?>" alt="Your profile"></a><button class="theme-toggle" type="button" data-theme-toggle aria-label="Switch to dark mode" aria-pressed="false"><span class="theme-toggle-icon" aria-hidden="true">☾</span><span class="theme-toggle-label">Dark mode</span></button><a class="nav-link logout" href="logout.php">Log out</a></nav><?php else: ?><button class="theme-toggle" type="button" data-theme-toggle aria-label="Switch to dark mode" aria-pressed="false"><span class="theme-toggle-icon" aria-hidden="true">☾</span><span class="theme-toggle-label">Dark mode</span></button><?php endif; ?></header>
    <?php if ($flash): ?><div class="flash <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
    <?php
}
function page_end(): void { ?><footer class="site-footer">Social Pulse <span>·</span> Built to connect people, one post at a time.</footer><script src="assets/js/app.js" defer></script></body></html><?php }
