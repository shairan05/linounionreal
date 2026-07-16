<?php
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$email = sanitize($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

try {
    $db = getDB();

    // Check if already subscribed
    $stmt = $db->prepare("SELECT id FROM newsletter_subscribers WHERE email = :email");
    $stmt->execute([':email' => $email]);

    if ($stmt->fetch()) {
        echo json_encode(['success' => true, 'message' => 'You\'re already subscribed!']);
        exit;
    }

    $stmt = $db->prepare("INSERT INTO newsletter_subscribers (email) VALUES (:email)");
    $stmt->execute([':email' => $email]);

    echo json_encode(['success' => true, 'message' => 'Welcome to LINO UNION! Check your inbox.']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error subscribing. Please try again.']);
}
