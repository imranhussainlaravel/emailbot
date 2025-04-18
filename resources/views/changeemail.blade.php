<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --background-color: #f8f9fa;
            --text-color: #2c3e50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background: var(--background-color);
            min-height: 100vh;
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: var(--primary-color);
            padding: 1.5rem;
            color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .nav-menu {
            list-style: none;
            margin-top: 2rem;
            flex-grow: 1;
        }

        .nav-item {
            margin: 1rem 0;
            transition: transform 0.2s ease;
        }

        .nav-item:hover {
            transform: translateX(5px);
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-link i {
            width: 25px;
            text-align: center;
        }

        .nav-link.active-nav {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active-nav::after {
            content: "";
            position: absolute;
            right: -1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: var(--secondary-color);
            border-radius: 2px;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            background: white;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .logout-section {
            margin-top: auto;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            width: 100%;
            padding: 0.8rem;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .email-container h2 {
            margin-bottom: 1rem;
            color: var(--primary-color);
            text-align: center;
            font-size: 2rem;
        }

        .email-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .email-header .active-email {
            margin: 0;
            padding: 0;
            border: none;
        }

        .other-emails button:disabled {
            background: gray;
            color: white;
            cursor: not-allowed;
            opacity: 0.8;
            border: none;
        }

        .compose-button {
            text-align: left;
            margin-bottom: 2rem;
        }

        .compose-button .btn {
            background: var(--secondary-color);
            color: white;
            text-decoration: none;
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            font-weight: bold;
            transition: background 0.3s ease;
            display: inline-block;
        }

        .compose-button .btn:hover {
            background: #2980b9;
        }

        .active-email {
            background: white;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .active-email h3 {
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }

        .other-emails ul {
            list-style: none;
            padding: 0;
        }

        .other-emails li {
            margin-bottom: 0.5rem;
        }

        .other-emails button {
            background: var(--secondary-color);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
            text-align: left;
        }

        .other-emails button:hover {
            background: #2980b9;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .nav-link.active-nav::after {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h2>Admin Panel</h2>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.change') }}" class="nav-link active-nav">
                        <i class="fas fa-envelope"></i> Emails
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/campaigns" class="nav-link">
                        <i class="fas fa-bullhorn"></i> Campaigns
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/all-data" class="nav-link">
                        <i class="fas fa-database"></i> All Data
                    </a>
                </li>
            </ul>
            <div class="logout-section">
                <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">

            <!-- Email Display Section -->
            <div class="email-header">
                <div class="compose-button">
                    <a href="{{ route('emails.compose') }}" class="btn">Compose New Email</a>
                </div>

                <div class="active-email">
                    <h3><i class="fas fa-user" style="margin-right:8px;"></i>{{ $activeEmail->mail_username }}</h3>
                </div>

            </div>

            <div class="email-container">
                <h2>Email Management</h2>

                <!-- Other Emails List -->
                <div class="other-emails">
                    {{-- <h3>Other Emails</h3> --}}
                    <ul>
                        <li>
                            <button disabled>{{ $activeEmail->mail_username }}</button>
                        </li>
                        @foreach ($emails as $email)
                            <li>
                                <button onclick="switchEmail({{ $email->id }})">{{ $email->mail_username }}</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <script>
        function switchEmail(emailId) {
            window.location.href = '/emails/switch/' + emailId;
        }
    </script>
</body>

</html>
