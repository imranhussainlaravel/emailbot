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

        .form-group input[type="file"] {
            border: none;
            background-color: #1e1e1e;
        }

        .img-preview {
            display: block;
            margin-top: 10px;
            border-radius: 8px;
        }

        /* Image ratio styles */
        #main_img_preview {
            width: 300px;
            height: 300px; /* 1:1 aspect ratio */
            object-fit: cover;
        }

        #header_img_preview {
        width: 300px;  /* Example width */
        height: 450px; /* 2:3 aspect ratio */
        object-fit: cover;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.15/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.15/cropper.min.js"></script>


    
</head>
<body>

    <div class="form-container">
        <h2>Save Category</h2>
        <form action="{{ route('save.form')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Title -->
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Enter category title" required value="{{ old('title') }}">
                @error('title')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter category description" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
            </div>

         
            <div class="form-group">
                <label for="nav_id">Navigation</label>
                <select id="nav_id" name="nav_id" required>
                    <option value="1" {{ old('nav_id') == 1 ? 'selected' : '' }}>Boxes by Industry</option>
                    <option value="2" {{ old('nav_id') == 2 ? 'selected' : '' }}>Boxes by Material</option>
                    <option value="3" {{ old('nav_id') == 3 ? 'selected' : '' }}>Boxes by Style</option>
                    <!-- Add more options as needed -->
                </select>
                @error('nav_id')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- ICON -->
            <div class="form-group" id="icon-containers-" style="display: none;">
                <label for="icon_file">Icon (that required)</label>
                <input type="file" id="icon" name="icon" accept="image/*">
                <input type="text" id="icon_name" name="icon_name" placeholder="Enter icon name (e.g., 'home')" style="display:none;">
                @error('icon')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Header Image (2:3 Ratio) -->
            <div class="form-group">
                <label for="header_img">Header Image (2:3 Ratio)</label>
                <input type="file" id="header_img" name="header_img" accept="image/*" required>
                @error('header_img')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
                <div>
                    <img id="header_img_preview" class="img-preview" src="#" alt="Header Image Preview" style="display:none;">
                    <canvas id="header_img_canvas" style="display:none;"></canvas>
                </div>
            </div>

            <!-- Main Image (1:1 Ratio) -->
            <div class="form-group">
                <label for="main_img">Main Image (1:1 Ratio)</label>
                <input type="file" id="main_img" name="main_img" accept="image/*" required>
                @error('main_img')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
                <div>
                    <img id="main_img_preview" class="img-preview" src="#" alt="Main Image Preview" style="display:none;">
                    <canvas id="main_img_canvas" style="display:none;"></canvas>
                </div>
            </div>


            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit">Save Category</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('nav_id').addEventListener('change', function () {
            const iconContainer = document.getElementById('icon-containers-');
            const selectedValue = this.value;
    
            // Show the icon upload field only if the selected value is 1
            if (selectedValue === '1') {
                iconContainer.style.display = 'block';
            } else {
                iconContainer.style.display = 'none';
            }
        });
    
        // Trigger change event on page load to handle old input value
        document.getElementById('nav_id').dispatchEvent(new Event('change'));
    </script>

    <script>
    let headerCropper;
    let mainCropper;


    document.getElementById('main_img').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const mainImgPreview = document.getElementById('main_img_preview');
            mainImgPreview.src = URL.createObjectURL(file);
            mainImgPreview.style.display = 'block';

            if (mainCropper) {
                mainCropper.destroy();
            }

            mainCropper = new Cropper(mainImgPreview, {
                aspectRatio: 1 / 1, // Keep this as 1:1 ratio
                viewMode: 1,
                autoCropArea: 1,
            });
        }
    });

    // On form submit, retrieve the cropped image data
    document.addEventListener('DOMContentLoaded', function () {
            // Your code that initializes Cropper.js goes here
            // Example for header image
            document.getElementById('header_img').addEventListener('change', function(event) {
                const [file] = event.target.files;
                if (file) {
                    const headerImgPreview = document.getElementById('header_img_preview');
                    headerImgPreview.src = URL.createObjectURL(file);
                    headerImgPreview.style.display = 'block';

                    headerImgPreview.onload = function () {
                        if (headerCropper) {
                            headerCropper.destroy();  // Destroy old cropper
                        }
                        headerCropper = new Cropper(headerImgPreview, {
                            aspectRatio: 2 / 3,  // 2:3 ratio
                            viewMode: 1,
                            autoCropArea: 1,
                            cropBoxResizable: false,
                        });
                    };
                }
            });
    });

    document.getElementById('header_img').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const headerImgPreview = document.getElementById('header_img_preview');
            headerImgPreview.src = URL.createObjectURL(file);
            headerImgPreview.style.display = 'block';

            headerImgPreview.onload = function () {
                if (headerCropper) {
                    headerCropper.destroy();
                }
                headerCropper = new Cropper(headerImgPreview, {
                    aspectRatio: 2 / 3,
                    viewMode: 1,
                    autoCropArea: 1,
                    cropBoxResizable: false,
                });
            };
        }
    });
    </script>

</body>
</html>

