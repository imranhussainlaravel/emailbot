<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="icon" type="image/x-icon" href="{{ URL('images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css')}}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Bootstrap JS (for dismissing alerts) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard')}}"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="{{ route('admin.categoty')}}"><i class="fas fa-table"></i> Categories</a></li>
                <li><a href="{{ route('admin.product')}}"><i class="fas fa-tasks"></i> Product</a></li>
                <li style="font-weight: bold;" ><a href="{{ route('admin.blogpage')}}" style="color: rgb(242, 210, 52);"><i class="fas fa-blog"></i>Blog</a></li>
                <li><a href="#"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="#"><i class="fas fa-cogs"></i> Settings</a></li>
                <li><a href="{{route('admin.logout')}}"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content" id="main-content">
            <div id="app-container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Dashboard Stats -->
                <!-- Active Users Table -->
                <div id="table-container">
                    <header class="table-header">
                        <h3>Blog</h3>
                        <div id="filter-buttons">
                            <a href="{{ route('admin.blogcreate')}}">
                            <button class="filter-btn">Add blog</button>
                            </a>
                        </div>
                    </header>
                    <table id="users-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>image</th>
                                {{-- <th>Status</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($blog as $blogitem)
                                <tr>
                                    <td>{{$blogitem->id}}</td>
                                    <td>{{$blogitem->title}}</td>
                                    <td>
                                        {{-- <img src="{{ $blogitem->main_img }}" alt="Custom Boxes" class="img-fluid"> --}}
                                        <img src="{{ $blogitem->main_img }}" alt="Custom Boxes" class="img-fluid" style="max-width: 50px; height: auto;">
                                        
                                    </td>
                                    {{-- <td> --}}
                                        {{-- <form action="{{ route('product.toggle-status', $blogitem->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="status {{$blogitem->status == 'active' ? 'active' : 'inactive'}}">
                                                {{$product->status == 'active' ? 'Active' : 'Inactive'}}
                                            </button>
                                        </form>
                                    </td> --}}
                                    {{-- <td><button class="btn-details">Delete</button><button class="btn-details" style="margin-left: 10px">Edit</button></td> --}}
                                    <td>
                                        <form action="{{ route('blog.destroy', $blogitem->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE') <!-- This will allow us to specify the DELETE method -->
                                            <button type="submit" class="btn-details" style="margin-left: 10px; background-color: #df4b30; color: white;">Delete</button>
                                        </form>
                                        <a href="{{ route('blog.edit', $blogitem->id) }}" class="btn-details" style="margin-left: 10px; background-color: #4CAF50; color: white;">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            <!-- Add more rows as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
    {{-- <script>
        // Detect when the window is being closed or refreshed
        window.addEventListener("beforeunload", function (event) {
            // Make an AJAX request to log out
            navigator.sendBeacon("{{ route('admin.logout') }}");
        });
    </script> --}}
    <script>
        window.addEventListener("beforeunload", function (event) {
            if (!window.sessionStorage.getItem('stayOnPage')) {
                fetch("{{ route('admin.logout') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });
            }
        });
    
        window.addEventListener("DOMContentLoaded", function () {
            window.sessionStorage.setItem('stayOnPage', true);
    
            // If the user navigates away, we remove the session key
            window.addEventListener("unload", function () {
                window.sessionStorage.removeItem('stayOnPage');
            });
        });
    </script>
    
    
</body>
</html>
