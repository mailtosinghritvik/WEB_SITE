<?php
declare(strict_types=1);

/**
 * GoDaddy enquiry inbox.
 * Set this to the address that should receive the contact form
 * before the site goes live. Example: 'hello@yourdomain.com'
 */
const AGENTORGS_INBOX = 'info@agentorgs.com';

function field(string $key, int $max): string
{
    $value = trim((string) ($_POST[$key] ?? ''));
    $value = strip_tags($value);
    $value = str_replace(["\r", "\n"], ' ', $value);
    if (strlen($value) > $max) {
        $value = substr($value, 0, $max);
    }
    return $value;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: contact.html');
    exit;
}

if (trim((string) ($_POST['company_website'] ?? '')) !== '') {
    header('Location: contact.html?status=sent');
    exit;
}

$name = field('name', 120);
$company = field('company', 160);
$email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
$phone = field('phone', 40);
$message = trim(strip_tags((string) ($_POST['message'] ?? '')));
$message = str_replace("\r", '', $message);
if (strlen($message) > 4000) {
    $message = substr($message, 0, 4000);
}

if ($name === '' || $company === '' || $email === false || $message === '') {
    header('Location: contact.html?status=error');
    exit;
}

if (AGENTORGS_INBOX === '') {
    header('Location: contact.html?status=inbox');
    exit;
}

$body = "Name: {$name}\nCompany: {$company}\nEmail: {$email}\nPhone: {$phone}\n\n{$message}\n";
$headers = "Reply-To: {$email}\r\nContent-Type: text/plain; charset=UTF-8\r\n";
$ok = mail(AGENTORGS_INBOX, 'AgentOrgs enquiry from ' . $name, $body, $headers);

header('Location: contact.html?status=' . ($ok ? 'sent' : 'error'));
exit;
