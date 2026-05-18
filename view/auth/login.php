<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Hemodinamics</title>
    
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #206773;
            --primary-hover: #174d56;
            --primary-light: rgba(32, 103, 115, 0.1);
            --dark: #0f172a;
            --light: #f8fafc;
            --slate-300: #cbd5e1;
            --slate-700: #334155;
            --success: #10b981;
            --danger: #ef4444;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.4);
            --shadow-premium: 0 20px 40px -15px rgba(15, 23, 42, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient glowing circles */
        .ambient-glow-1 {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(32, 103, 115, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            top: -10%;
            left: -10%;
            z-index: 1;
            pointer-events: none;
        }

        .ambient-glow-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(32, 103, 115, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            bottom: -10%;
            right: -10%;
            z-index: 1;
            pointer-events: none;
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
            perspective: 1000px;
        }

        .login-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            box-shadow: var(--shadow-premium);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px -20px rgba(15, 23, 42, 0.25);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-section img {
            max-width: 180px;
            height: auto;
            background: white;
            padding: 12px 24px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(32, 103, 115, 0.1);
            transition: transform 0.3s ease;
        }

        .logo-section img:hover {
            transform: scale(1.05);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .login-header p {
            font-size: 14px;
            color: var(--slate-700);
        }

        /* Beautiful error box */
        .error-box {
            background-color: rgba(239, 68, 68, 0.08);
            border-left: 4px solid var(--danger);
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }

        .error-box i {
            color: var(--danger);
            font-size: 18px;
        }

        .error-box span {
            font-size: 13.5px;
            color: var(--danger);
            font-weight: 500;
            line-height: 1.4;
        }

        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        /* Premium Floating Label Input Groups */
        .input-group {
            position: relative;
            margin-bottom: 24px;
        }

        .input-group i.input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-700);
            transition: color 0.3s ease;
            font-size: 18px;
            z-index: 2;
        }

        .input-group input {
            width: 100%;
            padding: 16px 18px 16px 48px;
            font-size: 15px;
            color: var(--dark);
            background: rgba(255, 255, 255, 0.7);
            border: 1.5px solid rgba(15, 23, 42, 0.1);
            border-radius: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(32, 103, 115, 0.15);
        }

        .input-group input:focus ~ i.input-icon {
            color: var(--primary);
        }

        /* Password toggle style */
        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-700);
            cursor: pointer;
            transition: color 0.3s ease;
            font-size: 16px;
            z-index: 2;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        /* Login Button */
        .login-btn {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            border: none;
            border-radius: 14px;
            color: white;
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 20px -5px rgba(32, 103, 115, 0.3);
        }

        .login-btn:hover {
            background: var(--primary-hover);
            box-shadow: 0 15px 25px -5px rgba(32, 103, 115, 0.4);
            transform: translateY(-2px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        /* Footer credits */
        .login-footer {
            margin-top: 35px;
            text-align: center;
            font-size: 12px;
            color: var(--slate-700);
            line-height: 1.5;
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <div class="login-container">
        <div class="login-card">
            
            <div class="logo-section">
                <img src="assets/image/logo.png" alt="Hemodinamics SRL Logo">
            </div>
            
            <div class="login-header">
                <h1>Bienvenido de nuevo</h1>
                <p>Ingrese sus credenciales para acceder al sistema</p>
            </div>

            <?php if (isset($error) && !empty($error)): ?>
                <div class="error-box">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form action="index.php?c=auth&a=Auth" method="POST" autocomplete="off">
                
                <div class="input-group">
                    <i class="fa-regular fa-user input-icon"></i>
                    <input type="text" name="username" placeholder="Usuario" required value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
                </div>

                <div class="input-group">
                    <i class="fa-regular fa-key input-icon"></i>
                    <input type="password" name="password" id="password" placeholder="Contraseña" required>
                    <i class="fa-regular fa-eye password-toggle" id="togglePassword"></i>
                </div>

                <button type="submit" class="login-btn">
                    <span>Ingresar</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </form>

            <div class="login-footer">
                <p>&copy; <?php echo date('Y'); ?> Hemodinamics S.R.L. Todos los derechos reservados.</p>
                
            </div>

        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // toggle the eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
