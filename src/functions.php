<?php
/********************************************************************
 *  GitHub‑timeline helper library  –  pure PHP, no external libs   *
 ********************************************************************/

/* -----------------------------------------------------------------
 * 1. Generate a cryptographically‑safe 6‑digit verification code
 * ----------------------------------------------------------------*/
function generateVerificationCode(): string
{
    return str_pad((string) random_int(0, 999_999), 6, '0', STR_PAD_LEFT);
}

/* -----------------------------------------------------------------
 * 2. “Database” helpers – store / remove emails in a flat‑file
 * ----------------------------------------------------------------*/
function registerEmail(string $email): void
{
    $file   = __DIR__ . '/registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];

    if (!in_array($email, $emails, true)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

function unsubscribeEmail(string $email): void
{
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        return;
    }
    $emails  = file($file, FILE_IGNORE_NEW_LINES);
    $updated = array_filter($emails, fn($e) => trim($e) !== trim($email));
    file_put_contents($file, implode(PHP_EOL, $updated) . PHP_EOL, LOCK_EX);
}

/* -----------------------------------------------------------------
 * 3.  E‑mail senders
 * ----------------------------------------------------------------*/
function sendVerificationEmail(string $email, string $code, bool $forUnsub = false): void
{
    $subject = $forUnsub ? 'Confirm Unsubscription' : 'Your Verification Code';
    $body    = $forUnsub
        ? "<p>To confirm unsubscription, use this code: <strong>$code</strong></p>"
        : "<p>Your verification code is: <strong>$code</strong></p>";

    $headers = "From: no-reply@example.com\r\nContent-Type: text/html;\r\n";
    mail($email, $subject, $body, $headers);
}

/* -----------------------------------------------------------------
 * 4. Fetch and format GitHub public events as HTML
 * ----------------------------------------------------------------*/
function fetchGitHubTimeline(): array
{
    $ctx  = stream_context_create(['http' => ['user_agent' => 'rtCamp-assignment']]);
    $json = file_get_contents('https://api.github.com/events', false, $ctx);
    return json_decode($json, true) ?: [];
}

function formatGitHubData(array $events): string
{
    $rows = '';
    foreach (array_slice($events, 0, 10) as $e) {
        $type = htmlspecialchars($e['type']           ?? 'N/A',     ENT_QUOTES);
        $user = htmlspecialchars($e['actor']['login'] ?? 'unknown', ENT_QUOTES);
        $rows .= "<tr><td>$type</td><td>$user</td></tr>";
    }

    return
        "<h2>GitHub Timeline Updates</h2>
         <table border='1'>
           <tr><th>Event</th><th>User</th></tr>$rows
         </table>
         <p><a href='http://localhost:8000/unsubscribe.php?email=__EMAIL__'
               id='unsubscribe-button'>Unsubscribe</a></p>";
}

/* -----------------------------------------------------------------
 * 5. Email the formatted timeline to every subscriber
 * ----------------------------------------------------------------*/
function sendGitHubUpdatesToSubscribers(): void
{
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        return;
    }

    // ── 1. load & clean the e‑mail list ───────────────────────────────
    $rawLines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $emails   = array_map('trim', $rawLines);          // remove CR/LF + spaces
    $emails   = array_filter($emails, static fn($e) =>
        filter_var($e, FILTER_VALIDATE_EMAIL));        // keep only valid ones
    if (!$emails) {
        return;                                        // nothing to send
    }

    // ── 2. build the common HTML payload ──────────────────────────────
    $eventsHtml = formatGitHubData(fetchGitHubTimeline());   // table + placeholder
    $headers    = "From: no-reply@example.com\r\n"
                . "Content-Type: text/html;\r\n";

    // ── 3. send personalised copies ───────────────────────────────────
    foreach ($emails as $email) {
        // replace the placeholder with a URL‑encoded version of this address
        $body = str_replace('__EMAIL__', urlencode($email), $eventsHtml);
        mail($email, 'Latest GitHub Updates', $body, $headers);
    }
}
