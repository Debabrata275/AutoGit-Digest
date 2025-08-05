# AutoGit-Digest

✅ Summary

This PR completes the assignment by implementing all required functionality inside the src/ folder, as per the provided guidelines. No changes were made outside src/, and all rules regarding structure, function names, form visibility, and email formatting have been followed.

📌 Features Implemented

✅ Email Verification using 6-digit code
✅ Verification email sent via PHP mail() in HTML format
✅ Email saved to registered_emails.txt (no DB used)
✅ Unsubscribe mechanism with code confirmation
✅ Unsubscribe emails follow exact HTML template
✅ GitHub timeline fetched via CRON every 5 minutes
✅ HTML email includes formatted <table> with unsubscribe link
✅ Styling added inside src/ with no external libraries
✅ Input values retained after submission
✅ Tested successfully on both Windows and Ubuntu with Mailpit
🖼️ Local Testing Evidence

Screenshots attached:
📩 Verification email in Mailpit
✅ Successful verification message
📰 GitHub Timeline email with formatted HTML
🚫 Unsubscribe email with 6-digit confirmation code
🛠 Environment

PHP version: 8.3
Mailpit used for local email testing
CRON job scheduled with setup_cron.sh and verified using crontab -l
