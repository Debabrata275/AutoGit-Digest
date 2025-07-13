<?php
require_once 'functions.php';

$data = fetchGitHubTimeline();              // Step 1: Fetch GitHub data
$formattedData = formatGitHubData($data);   // Step 2: Format as HTML
sendGitHubUpdatesToSubscribers($formattedData);  // Step 3: Send to all users
