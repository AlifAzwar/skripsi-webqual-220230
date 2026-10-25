<?php
session_start();
require_once '../config/database.php';

// Jika admin sudah login, langsung alihkan ke dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: ../index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $admin = $stmt->fetch();

            if ($admin && trim($password) === trim($admin['password'])) {
                // Set session
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id_admin'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_name'] = $admin['nama_admin'];

                header("Location: ../index.php");
                exit;
            } else {
                $error = 'Username atau password salah.';
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    } else {
        $error = 'Harap isi semua kolom.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Evaluasi WebQual 4.0 InfoSumbar</title>
    <!-- Google Fonts Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ef4444;
            --primary-hover: #b91c1c;
            --bg-dark: #f8f9fa;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            background-image: 
                radial-gradient(at 0% 0%, rgba(239, 68, 68, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(239, 68, 68, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-main);
            overflow: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-section h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .logo-section p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 30px;
            line-height: 1.4;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-control {
            width: 100%;
            padding: 12px 16px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-main);
            font-size: 0.9rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .input-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            padding: 12px;
            font-size: 0.85rem;
            color: #f87171;
            margin-bottom: 20px;
            text-align: left;
            animation: shake 0.3s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .footer-text {
            margin-top: 30px;
            font-size: 0.75rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="logo-section">
            <h2>InfoSumbar</h2>
            <p>Sistem Informasi Hasil Evaluasi Kualitas Layanan Website (WebQual 4.0)</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="input-control" placeholder="Masukkan username admin" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="input-control" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>
    </div>
</div>

</body>
</html>
