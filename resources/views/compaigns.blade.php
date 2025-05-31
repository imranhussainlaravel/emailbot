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
        .highlight-date {
            font-weight: bold;
            color: #3e1a5e;
            /* background-color: #f0e6fa; light purple background */
            padding: 3px 6px;
            border-radius: 4px;
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
                <a href="{{ route('campaigns.months') }}" class="nav-link active-nav">
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
                    <th>Days</th>
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
                    @php
                        $dayOfWeek = \Carbon\Carbon::parse($campaign['sent_at'])->format('l'); // Get day name
                       $rowColor = match ($dayOfWeek) {
                            'Monday' => '#F57C00',  // Lightest Orange (Peach)
                            'Tuesday' => '#FFE0B2', // Light Orange
                            'Wednesday' => '#FFCC80', // Medium Orange
                            'Thursday' => '#FFB74D', // Slightly Darker Orange
                            'Friday' => '#FF9800', // Mid-Dark Orange
                            'Saturday' => '#FB8C00', // Dark Orange
                            'Sunday' => '#F57C00', // Darker Orange
                            default => 'white',
                        };
                    @endphp

                    <tr style="background-color: {{ $rowColor }}; color: #2c3e50;" onclick="window.location='{{ route('campaigns_view', $campaign['id']) }}'">
                        <td>{{ $campaign['id'] }}</td>
                        <td>{{ $dayOfWeek }}</td>
                        <td>{{ $campaign['subject'] }}</td>
                       <td>
                            <span class="highlight-date">
                                {{ \Carbon\Carbon::parse($campaign['sent_at'])->format('d-m') }}
                            </span>
                        </td>
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
