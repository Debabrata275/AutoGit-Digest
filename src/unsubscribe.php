<?php
require_once 'functions.php';
session_start();

$message = '';

if (isset($_POST['unsubscribe_email'])) {
    $email = filter_var($_POST['unsubscribe_email'], FILTER_VALIDATE_EMAIL);
    if ($email) {
        $code = generateVerificationCode();
        $_SESSION['unsub_code']  = $code;
        $_SESSION['unsub_email'] = $email;

        $subject = "Confirm Unsubscription";
        $body    = "<p>To confirm unsubscription, use this code: <strong>$code</strong></p>";
        $headers = "From: no-reply@example.com\r\nContent-Type: text/html;\r\n";
        mail($email, $subject, $body, $headers);

        $message = 'Unsubscription code sent.';
    } else {
        $message = 'Invalid email.';
    }
}

if (isset($_POST['unsubscribe_verification_code'])) {
    if ($_POST['unsubscribe_verification_code'] === ($_SESSION['unsub_code'] ?? '')) {
        unsubscribeEmail($_SESSION['unsub_email']);
        $message = 'Unsubscribed successfully.';
        unset($_SESSION['unsub_code'], $_SESSION['unsub_email']);
    } else {
        $message = 'Invalid verification code.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Unsubscribe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="card">
    <h1>Unsubscribe</h1>
    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- request code -->
    <form method="POST">
        <input type="email" name="unsubscribe_email" required
               value="<?= htmlspecialchars($_POST['unsubscribe_email']
                          ?? ($_GET['email'] ?? '')) ?>">
        <button id="submit-unsubscribe">Unsubscribe</button>
    </form>

    <!-- verify code -->
    <form method="POST">
        <input type="text" name="unsubscribe_verification_code" maxlength="6" required
               value="<?= htmlspecialchars($_POST['unsubscribe_verification_code'] ?? '') ?>">
        <button id="verify-unsubscribe">Verify</button>
    </form>
</div>
</body>
</html>
