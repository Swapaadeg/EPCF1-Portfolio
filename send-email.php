<?php
// Headers CORS et JSON DOIVENT être en premier
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Désactiver l'affichage des erreurs pour éviter de casser le JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Récupérer les données du formulaire
$nom = isset($_POST['nom']) ? htmlspecialchars(trim($_POST['nom'])) : '';
$prenom = isset($_POST['prenom']) ? htmlspecialchars(trim($_POST['prenom'])) : '';
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';
$consentement = isset($_POST['consentement']) ? $_POST['consentement'] : '';

// Validation des champs
$errors = [];

if (empty($nom)) {
    $errors[] = 'Le nom est requis';
}

if (empty($prenom)) {
    $errors[] = 'Le prénom est requis';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email invalide';
}

if (empty($message)) {
    $errors[] = 'Le message est requis';
}

if (empty($consentement)) {
    $errors[] = 'Vous devez accepter le traitement de vos données';
}

// Si des erreurs, retourner
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Configuration email
$to = 'marie.rivier23@gmail.com';
$subject = 'Nouveau message depuis ton portfolio - ' . $nom . ' ' . $prenom;

// Créer le message HTML
$htmlMessage = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #7F5A83, #0D324D);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .field {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #7F5A83;
            border-radius: 4px;
        }
        .field strong {
            color: #7F5A83;
            display: block;
            margin-bottom: 5px;
        }
        .message-content {
            background: #fff;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Nouveau message depuis ton portfolio</h1>
        </div>
        <div class="content">
            <div class="field">
                <strong>Nom complet:</strong>
                ' . $nom . ' ' . $prenom . '
            </div>
            <div class="field">
                <strong>Email:</strong>
                <a href="mailto:' . $email . '" style="color: #0D324D;">' . $email . '</a>
            </div>
            <div class="field">
                <strong>Message:</strong>
                <div class="message-content">' . nl2br($message) . '</div>
            </div>
        </div>
        <div class="footer">
            <p>Ce message a été envoyé depuis le formulaire de contact de ton portfolio</p>
            <p>Date: ' . date('d/m/Y à H:i') . '</p>
        </div>
    </div>
</body>
</html>
';

// Message texte alternatif
$textMessage = "Nouveau message depuis ton portfolio\n\n";
$textMessage .= "Nom: $nom $prenom\n";
$textMessage .= "Email: $email\n\n";
$textMessage .= "Message:\n$message\n\n";
$textMessage .= "---\n";
$textMessage .= "Envoyé le " . date('d/m/Y à H:i');

// Headers pour l'email
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    'From: Portfolio Contact <noreply@marie-rivier.com>',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion(),
    'X-Priority: 1',
    'Importance: High'
];

// MODE PRODUCTION : Envoyer l'email
$mailSent = mail($to, $subject, $htmlMessage, implode("\r\n", $headers));

// OPTIONNEL : Sauvegarde de backup dans un fichier log
// Décommenter si tu veux garder une trace locale des messages
/*
$logFile = 'messages.txt';
$logContent = "\n\n=== MESSAGE REÇU LE " . date('d/m/Y à H:i:s') . " ===\n";
$logContent .= "De: $nom $prenom\n";
$logContent .= "Email: $email\n";
$logContent .= "Message: $message\n";
$logContent .= "==========================================\n";
file_put_contents($logFile, $logContent, FILE_APPEND);
*/

if ($mailSent !== false) {
    // Email de confirmation à l'expéditeur (désactivé en mode dev)
    $confirmSubject = 'Confirmation - Message reçu';
    $confirmMessage = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #7F5A83, #0D324D); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Message bien reçu !</h1>
        </div>
        <div class="content">
            <p>Bonjour ' . $prenom . ',</p>
            <p>Merci d\'avoir pris contact avec moi ! J\'ai bien reçu votre message et je vous répondrai dans les plus brefs délais.</p>
            <p>À bientôt,<br><strong>Marie Rivier</strong><br>Développeuse Full Stack</p>
        </div>
        <div class="footer">
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
    ';
    
    $confirmHeaders = [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: SwapDevStudio <noreply@marie-rivier.com>',
        'X-Mailer: PHP/' . phpversion()
    ];
    
    // Envoyer l'email de confirmation à l'utilisateur
    mail($email, $confirmSubject, $confirmMessage, implode("\r\n", $confirmHeaders));
    
    http_response_code(200);
    echo json_encode([
        'success' => true, 
        'message' => 'Message envoyé avec succès ! Vous recevrez une confirmation par email.'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Erreur lors de l\'envoi du message. Veuillez réessayer.'
    ]);
}
