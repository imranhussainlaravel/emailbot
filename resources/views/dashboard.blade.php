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

        /* Sidebar Navigation */
        .sidebar {
            width: 260px;
            background: var(--primary-color);
            padding: 1.5rem;
            color: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
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
            background: rgba(255,255,255,0.1);
        }

        .nav-link i {
            width: 25px;
            text-align: center;
        }

        /* Active State */
        .nav-link.active-nav {
            background: rgba(255,255,255,0.1);
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

        /* Main Content Area */
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

        /* Logout Section */
        .logout-section {
            margin-top: auto;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .logout-btn {
            width: 100%;
            padding: 0.8rem;
            background: rgba(255,255,255,0.1);
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
            background: rgba(255,255,255,0.2);
        }

        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Responsive Design */
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
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active-nav">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.change') }}" class="nav-link">
                        <i class="fas fa-envelope"></i> Emails
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.compaigns') }}" class="nav-link">
                        <i class="fas fa-bullhorn"></i>
                        Campaigns
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/all-data" class="nav-link">
                        <i class="fas fa-database"></i>
                        All Data
                    </a>
                </li>
            </ul>

            <!-- Logout Section -->
            <div class="logout-section">
                
                <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Log Out
                    </button>
                </form>
                
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <div class="user-info">
                    <h1>Welcome Back, Admin</h1>
                    <p>Last login: Today at 09:30 AM</p>
                </div>
                <div class="notifications">
                    <button class="icon-button">
                        <i class="fas fa-bell"></i>
                    </button>
                </div>
            </div>

            <div class="greeting-card">
                <h2>Good Morning! 🌞</h2>
                <p>You have 3 new messages and 2 pending tasks</p>
            </div>

            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p class="stat-value">1,234</p>
                </div>
                <div class="stat-card">
                    <h3>Active Campaigns</h3>
                    <p class="stat-value">15</p>
                </div>
                <div class="stat-card">
                    <h3>Messages</h3>
                    <p class="stat-value">23</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>