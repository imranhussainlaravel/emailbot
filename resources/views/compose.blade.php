<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Campaign System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --background-color: #f8f9fa;
            --text-color: #2c3e50;
            --success-color: #28a745;
            --danger-color: #dc3545;
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

        /* Dashboard Layout */
        .dashboard-container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: var(--primary-color);
            padding: 1.5rem;
            color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            color: white;
            margin-bottom: 2rem;
        }

        .nav-menu {
            list-style: none;
            margin-top: 1rem;
            flex-grow: 1;
        }

        .nav-item {
            margin: 0.5rem 0;
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

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 2rem;
            background: white;
            overflow-y: auto;
        }

        /* Email Compose Styles */
        .email-compose-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .email-compose-container h2 {
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            text-align: center;
        }

        .compose-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .compose-card-header {
            background: var(--primary-color);
            color: white;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
        }

        .compose-card-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .text-muted {
            color: #6c757d;
            font-size: 0.875rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
        }

        .btn i {
            margin-right: 0.5rem;
        }

        .btn-primary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        hr {
            margin: 1.5rem 0;
            border: 0;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        /* Alert Styles */
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-top: 1.5rem;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .float-right {
            float: right;
        }

        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        /* Responsive Styles */
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

            .main-content {
                padding: 1rem;
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
            <div class="email-compose-container">
                <div class="nav-buttons">
                    <a href="{{ route('admin.change') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Emails
                    </a>
                </div>

                <h2>Compose New Email</h2>
                
                <div class="compose-card">
                    <div class="compose-card-header">Import Recipients</div>
                    <div class="compose-card-body">
                        <!-- Google Sheet Import Form -->
                        <form method="POST" action="{{ route('emails.import.google') }}">
                            @csrf
                            
                            <div class="form-group">
                                <label for="google_sheet_url">Google Sheet URL</label>
                                <input type="url" name="google_sheet_url" id="google_sheet_url" 
                                       class="form-control @error('google_sheet_url') is-invalid @enderror" 
                                       placeholder="https://docs.google.com/spreadsheets/d/..." required>
                                
                                @error('google_sheet_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                                <small class="text-muted">
                                    Ensure the sheet has at least one column with email addresses
                                </small>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-table"></i> Import from Google Sheet
                            </button>
                        </form>

                        <hr>

                        <!-- Alternative CSV Import -->
                        <h5>Or upload CSV</h5>
                        <form method="POST" action="#" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="file" name="csv_file" accept=".csv,.xlsx" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-file-csv"></i> Upload CSV
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Error/Success Alerts -->
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                        <a href="{{ route('emails.recipients') }}" class="btn btn-success float-right">
                            Proceed to Recipients <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>