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

        /* Logout Section */
        /* .logout-section {
            margin-top: auto;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        } */

        /* .logout-btn {
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
        } */

        

        /* Responsive Design */
       
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
                    <a href="{{ route('campaigns.months') }}" class="nav-link">
                        <i class="fas fa-bullhorn"></i>
                        Campaigns
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('all.emails') }}" class="nav-link">
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
                    <h1>Welcome Back, Imran</h1>
                    {{-- <p>Last login: Today at 09:30 AM</p> --}}
                </div>
                {{-- <div class="notifications">
                    <button class="icon-button">
                        <i class="fas fa-bell"></i>
                    </button>
                </div> --}}
            </div>

            {{-- <div class="greeting-card">
                <h2>Good Morning! 🌞</h2>
                <p>You have 3 new messages and 2 pending tasks</p>
            </div> --}}

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