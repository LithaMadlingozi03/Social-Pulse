<?php
require_once __DIR__ . '/app.php';
if (current_user()) redirect('feed.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim((string)($_POST['username'] ?? '')); $display = trim((string)($_POST['display_name'] ?? '')); $email = strtolower(trim((string)($_POST['email'] ?? ''))); $password = (string)($_POST['password'] ?? '');
    if (!preg_match('/^[a-zA-Z0-9_.]{3,25}$/', $username)) $error = 'Username must be 3–25 characters using letters, numbers, dots, or underscores.';
    elseif ($display === '' || mb_strlen($display) > 60) $error = 'Enter a display name of up to 60 characters.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Enter a valid email address.';
    elseif (strlen($password) < 10) $error = 'Use a password with at least 10 characters.';
    else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1'); $stmt->bind_param('ss', $username, $email); $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) $error = 'That username or email is already registered.';
        else { $hash = password_hash($password, PASSWORD_DEFAULT); $stmt = $conn->prepare('INSERT INTO users (username, display_name, email, password_hash) VALUES (?, ?, ?, ?)'); $stmt->bind_param('ssss', $username, $display, $email, $hash); $stmt->execute(); flash('Your account is ready. Sign in to get started.'); redirect('index.php'); }
    }
}
page_start('Create account');
?>
<main class="auth-shell register-shell"><section class="auth-visual"><div class="visual-orbit orbit-one"></div><div class="visual-orbit orbit-two"></div><div class="visual-content"><span class="eyebrow">YOUR NEXT CHAPTER STARTS HERE</span><h1>Find your<br><span>kind of people.</span></h1><p>Share the things you care about, follow new perspectives, and build a feed that feels like yours.</p><div class="visual-tags"><span>✦ Create</span><span>↗ Discover</span><span>◎ Connect</span></div></div><div class="visual-bottom">Your voice belongs here.</div></section><section class="auth-panel"><div class="auth-card"><div class="brand auth-brand"><span class="brand-mark">S</span><span>social<span class="brand-accent">pulse</span></span></div><span class="eyebrow muted">JOIN THE COMMUNITY</span><h2>Create your account.</h2><p class="muted">It only takes a moment to get started.</p><?php if ($error): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?><form method="post" class="form-stack"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Display name<input name="display_name" maxlength="60" required value="<?= e($_POST['display_name'] ?? '') ?>" placeholder="How people will know you"></label><label>Username<input name="username" maxlength="25" pattern="[A-Za-z0-9_.]{3,25}" required value="<?= e($_POST['username'] ?? '') ?>" placeholder="choose_a_username"></label><label>Email address<input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>" placeholder="you@example.com"></label><label>Password<input type="password" name="password" minlength="10" autocomplete="new-password" required placeholder="At least 10 characters"></label><button class="btn primary full" type="submit">Create account <span>→</span></button></form><p class="auth-switch">Already have an account? <a href="index.php">Sign in</a></p><p class="tiny-note">By joining, you agree to be kind and keep the community welcoming.</p></div></section></main>
<?php page_end(); ?>
