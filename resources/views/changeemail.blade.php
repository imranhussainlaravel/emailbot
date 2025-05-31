<!DOCTYPE html>
<html>
<head>
    <title>Professional Dashboard</title>
    <!-- Fixed Font Awesome Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --background-color: #f8f9fa;
            --text-color: #2c3e50;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #1a1a2e;
            color: #fff;
            padding: 20px;
            flex-shrink: 0;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-item {
            margin-bottom: 15px;
        }

        .nav-link {
            text-decoration: none;
            color: #ccc;
            font-size: 16px;
            display: block;
            transition: 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #00f0ff;
        }
        .nav-link.active-nav{
            color: #00a6ff;
        }

        .logout-section {
            margin-top: 30px;
        }

        .logout-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .main-content {
            flex-grow: 1;
            padding: 40px;
            background-color: #ffffff;
        }

        .header h1 {
            margin: 0 0 5px 0;
            font-size: 28px;
        }

        .header p {
            margin: 0 0 20px 0;
            color: #666;
        }
        .greeting-card {
            background: var(--secondary-color);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            animation: slideIn 0.5s ease;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
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
                    <a href="{{ route('campaigns.months') }}" class="nav-link">
                        <i class="fas fa-bullhorn"></i> Campaigns
                    </a>
                </li>
                <li class="nav-item">
                    {{-- <a href="/all-data" class="nav-link"> --}}
                        <a href="{{ route('all.emails') }}" class="nav-link">
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
