<!DOCTYPE html>
<html>
<head>
    <title>Email Campaigns - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --background-color: #f8f9fa;
            --text-color: #2c3e50;
        }
        * {
            margin: 0; padding: 0;
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
        .nav-link:hover,
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
        .nav-link i {
            width: 25px;
            text-align: center;
        }
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

        .main-content {
            flex: 1;
            padding: 2rem;
            background: white;
            overflow-y: auto;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f1f1f1;
        }
        tr:hover {
            background-color: #f0f8ff;
            cursor: pointer;
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
                <a href="{{ route('admin.change') }}" class="nav-link">
                    <i class="fas fa-envelope"></i> Emails
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.compaigns') }}" class="nav-link active-nav">
                    <i class="fas fa-bullhorn"></i> Campaigns
                </a>
            </li>
            <li class="nav-item">
                <a href="/all-data" class="nav-link">
                    <i class="fas fa-database"></i> All Data
                </a>
            </li>
        </ul>

        <!-- Logout -->
        <div class="logout-section">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <h2>Email Campaign Stats</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Sent At</th>
                    <th>Total Organic Emails</th>
                    <th>Opened</th>
                    <th>Clicked</th>
                    <th>Open CTR (%)</th>
                    <th>Click CTR (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaignData as $campaign)
                    <tr onclick="window.location='{{ route('campaigns_view', $campaign['id']) }}'">
                        <td>{{ $campaign['id'] }}</td>
                        <td>{{ $campaign['title'] }}</td>
                        <td>{{ $campaign['subject'] }}</td>
                        <td>{{ $campaign['sent_at'] }}</td>
                        <td>{{ $campaign['total_emails'] }}</td>
                        <td>{{ $campaign['opened_emails'] }}</td>
                        <td>{{ $campaign['clicked_emails'] }}</td>
                        <td>{{ $campaign['opened_ctr'] }}%</td>
                        <td>{{ $campaign['clicked_ctr'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</div>
</body>
</html>
