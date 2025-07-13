<?php
require_once 'functions.php';
session_start();

$message = '';

if (isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if ($email) {
        $code = generateVerificationCode();
        $_SESSION['code']  = $code;
        $_SESSION['email'] = $email;
        sendVerificationEmail($email, $code);
        $message = 'Verification code sent to your email.';
    } else {
        $message = 'Invalid email address.';
    }
}

if (isset($_POST['verification_code'])) {
    if ($_POST['verification_code'] === ($_SESSION['code'] ?? '')) {
        registerEmail($_SESSION['email']);
        $message = 'Email verified and registered successfully!';
        unset($_SESSION['code'], $_SESSION['email']);
    } else {
        $message = 'Invalid verification code.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="card">
    <h1>Email Verification</h1>
    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- subscribe -->
    <form method="POST">
        <input type="email" name="email" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <button id="submit-email">Submit</button>
    </form>

    <!-- verify subscribe -->
    <form method="POST">
        <input type="text" name="verification_code" maxlength="6" required
               value="<?= htmlspecialchars($_POST['verification_code'] ?? '') ?>">
        <button id="submit-verification">Verify</button>
    </form>

    <!-- unsubscribe -->
    <form method="POST" action="unsubscribe.php">
        <input type="email" name="unsubscribe_email" required
               value="<?= htmlspecialchars($_POST['unsubscribe_email'] ?? '') ?>">
        <button id="submit-unsubscribe">Unsubscribe</button>
    </form>
</div>
</body>
</html>
