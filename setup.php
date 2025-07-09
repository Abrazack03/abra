<?php
/**
 * Beipoa eCommerce Setup Script
 * This script helps you set up the database and check system requirements
 */

$step = $_GET['step'] ?? 1;
$error = '';
$success = '';

// Check PHP version
$phpVersion = phpversion();
$phpVersionOK = version_compare($phpVersion, '7.4.0', '>=');

// Check required extensions
$requiredExtensions = ['pdo', 'pdo_mysql', 'mysqli', 'gd', 'curl', 'json'];
$missingExtensions = [];

foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step == 2) {
        // Database configuration
        $host = $_POST['host'] ?? 'localhost';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $database = $_POST['database'] ?? 'beipoa_ecommerce';
        
        if (empty($username)) {
            $error = 'Please enter a database username.';
        } else {
            try {
                // Test connection
                $dsn = "mysql:host=$host;charset=utf8mb4";
                $pdo = new PDO($dsn, $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Create database if it doesn't exist
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `$database`");
                
                // Read and execute schema
                $schema = file_get_contents('database/schema.sql');
                if ($schema) {
                    // Remove the initial CREATE DATABASE and USE statements since we already did that
                    $schema = preg_replace('/CREATE DATABASE.*?;/', '', $schema);
                    $schema = preg_replace('/USE .*?;/', '', $schema);
                    
                    // Execute schema
                    $pdo->exec($schema);
                    
                    // Update database config file
                    $configContent = file_get_contents('config/database.php');
                    $configContent = str_replace("define('DB_HOST', 'localhost');", "define('DB_HOST', '$host');", $configContent);
                    $configContent = str_replace("define('DB_USER', 'root');", "define('DB_USER', '$username');", $configContent);
                    $configContent = str_replace("define('DB_PASS', '');", "define('DB_PASS', '$password');", $configContent);
                    $configContent = str_replace("define('DB_NAME', 'beipoa_ecommerce');", "define('DB_NAME', '$database');", $configContent);
                    
                    file_put_contents('config/database.php', $configContent);
                    
                    $success = 'Database setup completed successfully!';
                    $step = 3;
                } else {
                    $error = 'Could not read database schema file.';
                }
                
            } catch (PDOException $e) {
                $error = 'Database connection failed: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beipoa Setup - Step <?php echo $step; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .setup-container {
            background: white;
            border-radius: 16px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        
        .setup-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .setup-logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 0.5rem;
        }
        
        .setup-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin: 0 0.5rem;
            position: relative;
        }
        
        .step.active {
            background: #2563eb;
            color: white;
        }
        
        .step.completed {
            background: #10b981;
            color: white;
        }
        
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            width: 40px;
            height: 2px;
            background: #e5e7eb;
            transform: translateY(-50%);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fed7aa;
        }
        
        .requirement {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            background: #f9fafb;
        }
        
        .requirement.ok {
            background: #ecfdf5;
            color: #065f46;
        }
        
        .requirement.error {
            background: #fef2f2;
            color: #991b1b;
        }
        
        .requirement i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #2563eb;
        }
        
        .btn {
            display: inline-block;
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
        }
        
        .btn-secondary {
            background: #6b7280;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
        }
        
        .button-group {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            margin-top: 2rem;
        }
        
        .text-center {
            text-align: center;
        }
        
        .success-icon {
            font-size: 4rem;
            color: #10b981;
            margin-bottom: 1rem;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="setup-container">
        <div class="setup-header">
            <h1 class="setup-logo">Beipoa Setup</h1>
            <p class="setup-subtitle">Let's get your eCommerce store ready!</p>
        </div>
        
        <div class="step-indicator">
            <div class="step <?php echo $step >= 1 ? ($step > 1 ? 'completed' : 'active') : ''; ?>">1</div>
            <div class="step <?php echo $step >= 2 ? ($step > 2 ? 'completed' : 'active') : ''; ?>">2</div>
            <div class="step <?php echo $step >= 3 ? 'active' : ''; ?>">3</div>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($step == 1): ?>
            <h2>Step 1: System Requirements</h2>
            <p>Checking if your system meets the requirements...</p>
            
            <div style="margin: 1.5rem 0;">
                <div class="requirement <?php echo $phpVersionOK ? 'ok' : 'error'; ?>">
                    <i class="fas fa-<?php echo $phpVersionOK ? 'check' : 'times'; ?>"></i>
                    <span>PHP <?php echo $phpVersion; ?> (Required: 7.4+)</span>
                </div>
                
                <?php foreach ($requiredExtensions as $ext): ?>
                    <div class="requirement <?php echo extension_loaded($ext) ? 'ok' : 'error'; ?>">
                        <i class="fas fa-<?php echo extension_loaded($ext) ? 'check' : 'times'; ?>"></i>
                        <span>PHP Extension: <?php echo $ext; ?></span>
                    </div>
                <?php endforeach; ?>
                
                <div class="requirement <?php echo is_writable('config/') ? 'ok' : 'error'; ?>">
                    <i class="fas fa-<?php echo is_writable('config/') ? 'check' : 'times'; ?>"></i>
                    <span>Config directory writable</span>
                </div>
                
                <div class="requirement <?php echo file_exists('database/schema.sql') ? 'ok' : 'error'; ?>">
                    <i class="fas fa-<?php echo file_exists('database/schema.sql') ? 'check' : 'times'; ?>"></i>
                    <span>Database schema file exists</span>
                </div>
            </div>
            
            <?php if (!$phpVersionOK || !empty($missingExtensions) || !is_writable('config/') || !file_exists('database/schema.sql')): ?>
                <div class="alert alert-warning">
                    <strong>Please fix the issues above before continuing.</strong>
                </div>
            <?php endif; ?>
            
            <div class="button-group">
                <span></span>
                <?php if ($phpVersionOK && empty($missingExtensions) && is_writable('config/') && file_exists('database/schema.sql')): ?>
                    <a href="?step=2" class="btn">Continue</a>
                <?php else: ?>
                    <button class="btn" onclick="window.location.reload()">Recheck</button>
                <?php endif; ?>
            </div>
            
        <?php elseif ($step == 2): ?>
            <h2>Step 2: Database Configuration</h2>
            <p>Configure your MySQL database connection.</p>
            
            <form method="POST" style="margin-top: 1.5rem;">
                <div class="form-group">
                    <label for="host" class="form-label">Database Host</label>
                    <input type="text" id="host" name="host" class="form-input" value="localhost" required>
                </div>
                
                <div class="form-group">
                    <label for="username" class="form-label">Database Username</label>
                    <input type="text" id="username" name="username" class="form-input" placeholder="root" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Database Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Leave empty if no password">
                </div>
                
                <div class="form-group">
                    <label for="database" class="form-label">Database Name</label>
                    <input type="text" id="database" name="database" class="form-input" value="beipoa_ecommerce" required>
                </div>
                
                <div class="button-group">
                    <a href="?step=1" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn">Setup Database</button>
                </div>
            </form>
            
        <?php elseif ($step == 3): ?>
            <div class="text-center">
                <i class="fas fa-check-circle success-icon"></i>
                <h2>Setup Complete!</h2>
                <p>Your Beipoa eCommerce store has been set up successfully.</p>
                
                <div style="margin: 2rem 0; text-align: left; background: #f9fafb; padding: 1.5rem; border-radius: 8px;">
                    <h3>Default Admin Credentials:</h3>
                    <p><strong>Username:</strong> admin</p>
                    <p><strong>Password:</strong> admin123</p>
                    <p style="margin-top: 1rem; color: #ef4444; font-size: 0.875rem;">
                        <i class="fas fa-exclamation-triangle"></i>
                        Please change the admin password after logging in!
                    </p>
                </div>
                
                <div class="button-group">
                    <a href="index.html" class="btn btn-secondary">View Store</a>
                    <a href="admin/" class="btn">Admin Panel</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>