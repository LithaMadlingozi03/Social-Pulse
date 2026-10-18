<?php
require_once __DIR__ . '/app.php';
if (current_user()) redirect('feed.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $identity = trim((string)($_POST['identity'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $stmt = $conn->prepare('SELECT id, username, display_name, email, password_hash, avatar_path, bio FROM users WHERE username = ? OR email = ? LIMIT 1');
    $stmt->bind_param('ss', $identity, $identity); $stmt->execute(); $user = $stmt->get_result()->fetch_assoc();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true); unset($user['password_hash']); $_SESSION['user'] = $user; redirect('feed.php');
    }
    $error = 'That username/email and password combination was not recognised.';
}
page_start('Welcome');
?>
<main class="auth-shell"><section class="auth-visual"><div class="visual-orbit orbit-one"></div><div class="visual-orbit orbit-two"></div><div class="visual-content"><span class="eyebrow">YOUR PEOPLE. YOUR MOMENTS.</span><h1>Make room for<br><span>what moves you.</span></h1><p>A more thoughtful place to share what you love, find your people, and keep up with the moments that matter.</p><div class="visual-tags"><span>✦ Real connections</span><span>↗ Fresh perspectives</span><span>◎ Your community</span></div></div><div class="visual-bottom">A little more signal. A lot less noise.</div></section><section class="auth-panel"><div class="auth-card"><div class="brand auth-brand"><span class="brand-mark">S</span><span>social<span class="brand-accent">pulse</span></span></div><span class="eyebrow muted">WELCOME BACK</span><h2>Sign in to your world.</h2><p class="muted">Pick up right where you left off.</p><?php if ($error): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?><form method="post" class="form-stack"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Username or email<input name="identity" autocomplete="username" required value="<?= e($_POST['identity'] ?? '') ?>" placeholder="you@example.com"></label><label>Password<input type="password" name="password" autocomplete="current-password" required placeholder="Enter your password"></label><button class="btn primary full" type="submit">Sign in <span>→</span></button></form><p class="auth-switch">New to Social Pulse? <a href="register.php">Create an account</a></p><p class="tiny-note">A portfolio project · Built with PHP and MySQL</p></div></section></main>
<?php page_end(); ?>
