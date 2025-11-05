<?php
// Contact form processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $subject = sanitizeInput($_POST['subject']);
    $message = sanitizeInput($_POST['message']);
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    // Check for spam (simple honeypot and time-based check)
    if (isset($_POST['honeypot']) && !empty($_POST['honeypot'])) {
        $errors[] = "Spam detected";
    }
    
    // Rate limiting (simple session-based)
    session_start();
    if (isset($_SESSION['last_contact_time']) && 
        (time() - $_SESSION['last_contact_time']) < 60) {
        $errors[] = "Please wait before sending another message";
    }
    
    if (empty($errors)) {
        // Process the form (send email, save to database, etc.)
        $success = sendContactEmail($name, $email, $subject, $message);
        
        if ($success) {
            $_SESSION['last_contact_time'] = time();
            header("Location: index.php?success=1");
            exit();
        } else {
            header("Location: index.php?error=1");
            exit();
        }
    } else {
        // Redirect back with errors
        $errorString = implode(", ", $errors);
        header("Location: index.php?error=" . urlencode($errorString));
        exit();
    }
} else {
    // If not POST request, redirect to homepage
    header("Location: index.php");
    exit();
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sendContactEmail($name, $email, $subject, $message) {
    // Email configuration
    $to = "alex.johnson@email.com"; // Replace with your actual email
    $headers = [
        "From: " . $email,
        "Reply-To: " . $email,
        "Return-Path: " . $email,
        "X-Mailer: PHP/" . phpversion(),
        "MIME-Version: 1.0",
        "Content-Type: text/html; charset=UTF-8"
    ];
    
    // Email subject
    $emailSubject = "Portfolio Contact: " . $subject;
    
    // Email body
    $emailBody = generateEmailTemplate($name, $email, $subject, $message);
    
    // Send email
    try {
        $success = mail($to, $emailSubject, $emailBody, implode("\r\n", $headers));
        
        // Log the contact attempt
        logContactAttempt($name, $email, $subject, $success);
        
        return $success;
    } catch (Exception $e) {
        error_log("Contact form error: " . $e->getMessage());
        return false;
    }
}

function generateEmailTemplate($name, $email, $subject, $message) {
    $template = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>New Contact Form Submission</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                margin: 0;
                padding: 20px;
                background-color: #f4f4f4;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .header {
                background: linear-gradient(135deg, #030213, #4f46e5);
                color: white;
                padding: 20px;
                text-align: center;
                border-radius: 10px 10px 0 0;
                margin: -30px -30px 30px -30px;
            }
            .field {
                margin-bottom: 20px;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 8px;
                border-left: 4px solid #4f46e5;
            }
            .field-label {
                font-weight: bold;
                color: #4f46e5;
                margin-bottom: 5px;
            }
            .field-value {
                color: #333;
                word-wrap: break-word;
            }
            .footer {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #eee;
                text-align: center;
                color: #666;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>New Portfolio Contact</h1>
                <p>You have received a new message through your portfolio website</p>
            </div>
            
            <div class="field">
                <div class="field-label">Name:</div>
                <div class="field-value">' . htmlspecialchars($name) . '</div>
            </div>
            
            <div class="field">
                <div class="field-label">Email:</div>
                <div class="field-value">' . htmlspecialchars($email) . '</div>
            </div>
            
            <div class="field">
                <div class="field-label">Subject:</div>
                <div class="field-value">' . htmlspecialchars($subject) . '</div>
            </div>
            
            <div class="field">
                <div class="field-label">Message:</div>
                <div class="field-value">' . nl2br(htmlspecialchars($message)) . '</div>
            </div>
            
            <div class="footer">
                <p>This message was sent from your portfolio contact form.</p>
                <p>Time: ' . date('Y-m-d H:i:s') . '</p>
                <p>IP Address: ' . getClientIP() . '</p>
            </div>
        </div>
    </body>
    </html>';
    
    return $template;
}

function logContactAttempt($name, $email, $subject, $success) {
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'success' => $success ? 'YES' : 'NO',
        'ip' => getClientIP(),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
    ];
    
    $logLine = implode(' | ', $logData) . "\n";
    
    // Create logs directory if it doesn't exist
    if (!file_exists('logs')) {
        mkdir('logs', 0755, true);
    }
    
    // Write to log file
    file_put_contents('logs/contact_' . date('Y-m') . '.log', $logLine, FILE_APPEND | LOCK_EX);
}

function getClientIP() {
    $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    
    foreach ($ipKeys as $key) {
        if (array_key_exists($key, $_SERVER) && !empty($_SERVER[$key])) {
            $ips = explode(',', $_SERVER[$key]);
            $ip = trim($ips[0]);
            
            if (filter_var($ip, FILTER_VALIDATE_IP, 
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
}

// Alternative: Save to database instead of email
function saveContactToDatabase($name, $email, $subject, $message) {
    try {
        // Database configuration
        $host = 'localhost';
        $dbname = 'portfolio_db';
        $username = 'your_db_username';
        $password = 'your_db_password';
        
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Prepare and execute insert statement
        $stmt = $pdo->prepare("
            INSERT INTO contacts (name, email, subject, message, ip_address, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([$name, $email, $subject, $message, getClientIP()]);
        
        return true;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}

// Auto-responder function
function sendAutoResponder($email, $name) {
    $subject = "Thank you for contacting me!";
    $headers = [
        "From: alex.johnson@email.com",
        "Reply-To: alex.johnson@email.com",
        "MIME-Version: 1.0",
        "Content-Type: text/html; charset=UTF-8"
    ];
    
    $message = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Thank you for your message</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                margin: 0;
                padding: 20px;
                background-color: #f4f4f4;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
            }
            .logo {
                font-size: 24px;
                font-weight: bold;
                color: #4f46e5;
                margin-bottom: 10px;
            }
            .content {
                margin-bottom: 30px;
            }
            .cta {
                text-align: center;
                margin: 30px 0;
            }
            .button {
                display: inline-block;
                background: #4f46e5;
                color: white;
                padding: 12px 24px;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            }
            .footer {
                border-top: 1px solid #eee;
                padding-top: 20px;
                text-align: center;
                color: #666;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div class="logo">Alex Johnson</div>
                <h1>Thank you for reaching out!</h1>
            </div>
            
            <div class="content">
                <p>Hi ' . htmlspecialchars($name) . ',</p>
                
                <p>Thank you for contacting me through my portfolio website. I have received your message and appreciate you taking the time to reach out.</p>
                
                <p>I typically respond to all inquiries within 24-48 hours. If your message is urgent, please feel free to call me directly at <strong>+1 (555) 123-4567</strong>.</p>
                
                <p>In the meantime, feel free to:</p>
                <ul>
                    <li>Check out my latest projects on <a href="https://github.com">GitHub</a></li>
                    <li>Connect with me on <a href="https://linkedin.com">LinkedIn</a></li>
                    <li>Browse through my portfolio for more examples of my work</li>
                </ul>
            </div>
            
            <div class="cta">
                <a href="https://yourportfolio.com" class="button">Visit My Portfolio</a>
            </div>
            
            <div class="footer">
                <p>Best regards,<br>Alex Johnson<br>Full Stack Developer</p>
                <p>Email: alex.johnson@email.com | Phone: +1 (555) 123-4567</p>
            </div>
        </div>
    </body>
    </html>';
    
    return mail($email, $subject, $message, implode("\r\n", $headers));
}

/*
Database setup SQL (if using database storage):

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(500) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('new', 'read', 'replied') DEFAULT 'new'
);

CREATE INDEX idx_created_at ON contacts(created_at);
CREATE INDEX idx_status ON contacts(status);
*/
?>