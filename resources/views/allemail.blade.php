<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email List</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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

        #emailSearch {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f2f2f2;
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
                    <a href="{{ route('campaigns.months') }}" class="nav-link">
                        <i class="fas fa-bullhorn"></i>
                        Campaigns
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('all.emails') }}" class="nav-link active-nav">
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
    <div class="main-content">
        <div class="header">
            <h1>Email List</h1>
            <p>Search and view connected emails below</p>
        </div>

        <!-- Search Input -->
        <input type="text" id="emailSearch" placeholder="Search emails...">

        <!-- Email List Table -->
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>status</th>
                        <th>opened_at</th>
                        <th>phone</th>
                        <th>contact_status</th>
                        <th>comment</th>
                        <th>save</th>



                    </tr>
                </thead>
                <tbody id="emailTable">
                    @php $i = 1; @endphp
                    @foreach($emails as $email)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $email->recipients }}</td>
                            <td>{{ $email->status }}</td>
                            <td>{{ $email->opened_at }}</td>
                            <td>{{ $email->phone }}</td>
                            <td>
                                <form method="POST" action="{{ route('email_logs.update', $email->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <select name="contact_status" class="form-select form-select-sm">
                                        @php
                                            $statuses = ['none', 'contacted', 'not_interested', 'interested', 'contact_later', 'bounce'];
                                        @endphp
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}" {{ $email->contact_status == $status ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    </td>
                                    <td>
                                    <input type="text" name="comment" value="{{ $email->comment }}" class="form-control form-control-sm mt-1" placeholder="Add comment...">
                                    </td>
                                    <td>
                                    <button type="submit" class="btn btn-sm btn-primary mt-1">Save</button>
                                </form>
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JavaScript for real-time search -->
<script>
    document.getElementById('emailSearch').addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll("#emailTable tr");
        rows.forEach(row => {
            const email = row.cells[1].textContent.toLowerCase();
            row.style.display = email.includes(filter) ? "" : "none";
        });
    });
</script>

</body>
</html>
