<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="icon" type="image/x-icon" href="{{ URL('images/logo.png') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            padding: 20px;
        }

        .form-container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            width: 500px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .form-group input[type="text"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            background-color: #2a2a2a;
            border: 1px solid #444;
            color: #e0e0e0;
            border-radius: 5px;
        }

        .form-group textarea {
            height: 100px;
        }

        .form-group button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Edit Category</h2>
        <form action="{{ route('update.form', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Enter category title" value="{{ $category->title }}" required>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter category description" required>{{ $category->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="icon">Icon (chose only in case of update)</label>
                <input type="file" id="icon" name="icon" accept="image/*">
                <input type="text" id="icon_name" name="icon_name" placeholder="Enter icon name (e.g., 'home')" style="display:none;">
                @error('icon')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
            </div>    

            <!-- Nav ID (Dropdown) -->
            <div class="form-group">
                <label for="nav_id">Navigation</label>
                <select id="nav_id" name="nav_id" required>
                    <option value="1" {{ $category->nav_id == 1 ? 'selected' : '' }}>Boxes by Industry</option>
                    <option value="2" {{ $category->nav_id == 2 ? 'selected' : '' }}>Boxes by Material</option>
                    <option value="3" {{ $category->nav_id == 3 ? 'selected' : '' }}>Boxes by Style</option>
                    <!-- Add more options as needed -->
                </select>
            </div>

            <!-- Images Section -->
            <div class="form-group">
                <label for="header_img">Header Image (chose only in case of update)</label>
                {{-- <img id="header_img_preview" src="{{ $category->header_img }}" alt="Header Image" class="img-preview" style="width: 100%; height: auto;"> --}}
                <input type="file" id="header_img" name="header_img" accept="image/*">
            </div>

            <div class="form-group">
                <label for="main_img">Main Image (chose only in case of update)</label>
                {{-- <img id="main_img_preview" src="{{ $category->main_img }}" alt="Main Image" class="img-preview" style="width: 100%; height: auto;"> --}}
                <input type="file" id="main_img" name="main_img" accept="image/*">
            </div>


            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit">Update Category</button>
            </div>
        </form>
    </div>
</body>
</html>
