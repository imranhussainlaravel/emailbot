<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <style>
        :root {
            --toastify-color-light: #fff;
            --toastify-color-dark: #121212;
            --toastify-color-success: #07bc0c;
            --toastify-text-color-light: #757575;
            --toastify-toast-shadow: 0px 4px 12px rgba(0, 0, 0, .1);
            --toastify-toast-bd-radius: 6px;
            --toastify-font-family: sans-serif;
        }

        body {
            margin: 0;
            font-family: var(--toastify-font-family);
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-container {
            background: var(--toastify-color-light);
            padding: 40px;
            border-radius: var(--toastify-toast-bd-radius);
            box-shadow: var(--toastify-toast-shadow);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: var(--toastify-text-color-light);
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            margin-bottom: 6px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-group input:focus {
            border-color: var(--toastify-color-success);
            outline: none;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--toastify-color-success);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }

        .login-btn:hover {
            background-color: #059c0a;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
                margin: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2>Login</h2>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required placeholder="Enter your password">
            </div>
            @if (session('error'))
                <div style="color: red; margin-bottom: 10px;">
                    {{ session('error') }}
                </div>
            @endif

            <button type="submit" class="login-btn">Sign In</button>
        </form>
    </div>

</body>

</html>
