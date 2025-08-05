# 🚀 AutoGitDigest

**AutoGitDigest** is a PHP-based automated GitHub timeline digest system.  
It allows users to subscribe via email, verify their subscription, and receive the latest GitHub events in their inbox every 5 minutes via a CRON job.  
The system also includes an unsubscribe flow, all implemented using **pure PHP** — no external libraries.

---

## 📌 Features

- Email verification with secure 6-digit code  
- Automated GitHub timeline updates via CRON  
- Unsubscribe with confirmation code  
- HTML-formatted emails  
- Local email testing with Mailpit  
- No database – uses `registered_emails.txt`

---

## 🛠️ Tech Stack

- **Language:** PHP 8.3  
- **Tools:** Mailpit, CRON  
- **API:** [GitHub Events API](https://api.github.com/events)

---

## 📂 Project Structure

AutoGitDigest/
│
├── src/
│ ├── index.php # Subscribe, verify, unsubscribe UI
│ ├── unsubscribe.php # Handles unsubscribe flow
│ ├── functions.php # Core logic
│ ├── cron.php # Sends updates via CRON
│ ├── setup_cron.sh # CRON setup script
│ ├── registered_emails.txt # Subscriber storage
│ └── style.css # Optional styling
│
└── README.md

yaml
Copy
Edit

---

## ⚙️ Installation

1️⃣ Clone the repository:
```bash
git clone https://github.com/Debabrata275/AutoGit-Digest.git
cd AutoGit-Digest/src
2️⃣ Start Mailpit:

bash
Copy
Edit
mailpit
Access at: http://localhost:8025

3️⃣ Start PHP server:

bash
Copy
Edit
php -S localhost:8000
Access at: http://localhost:8000

4️⃣ Setup CRON job:

bash
Copy
Edit
chmod +x setup_cron.sh
./setup_cron.sh
Check with:

bash
Copy
Edit
crontab -l
📸 Screenshots
(Replace UPLOAD_LINK_HERE with actual image links after uploading them)



🎥 Demo Video
(Replace UPLOAD_LINK_HERE with actual video link)