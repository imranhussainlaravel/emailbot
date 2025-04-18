<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog Post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.tiny.cloud/1/vxj76g8udnthdjtd21bsp83sa3kf9qku12j6vv454sn9ihgm/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css')}}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Blog Post</h1>

        <!-- Success message if any -->
        <div id="success-message" class="alert alert-success" style="display: none;"></div>

        <form method="POST" action="{{ route('blog.update', $blog->id) }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="{{ $blog->title }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="main_img">Main Image</label>
                <input type="file" name="main_img" id="main_img" class="form-control">
                <small>Current image: <img src="{{ $blog->main_img }}" alt="Current Image" style="max-width: 100px;"></small>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="editor" name="content" class="form-control" required>{{ $blog->content }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Blog Post</button>
        </form>
    </div>

    <script>
        tinymce.init({
            selector: '#editor',
            plugins: 'autolink lists link image charmap print preview',
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | bullist numlist | link image',
            height: 300,
            images_upload_url: '/upload-image.php',
        });

        // Optional: To show success message (for demonstration purposes)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            document.getElementById('success-message').innerText = 'Blog updated successfully!';
            document.getElementById('success-message').style.display = 'block';
        }
    </script>
</body>
</html>
