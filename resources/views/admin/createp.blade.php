<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tiny.cloud/1/vxj76g8udnthdjtd21bsp83sa3kf9qku12j6vv454sn9ihgm/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
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


.editor-container {
    width: 100%; /* Adjusts width to take the full form container */
    margin: 20px auto;
}

#editor {
    width: 100%;
    min-height: 300px; /* Sets minimum height */
    box-sizing: border-box; /* Ensures padding doesn't affect width */
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
            height: 300px;
            /* 1:1 aspect ratio */
            object-fit: cover;
        }

        #header_img_preview {
            width: 300px;
            /* Example width */
            height: 450px;
            /* 2:3 aspect ratio */
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
    <script>
    //     tinymce.init({
    //     selector: '#editor',
    //     plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist casechange formatpainter advtable',
    //     toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table checklist | spellcheckdialog a11ycheck typography | align lineheight',
    //     height: "100%",
    //     image_title: true,
    //     automatic_uploads: true,
    //     file_picker_types: 'image',
    //     // images_upload_url: '/uploads/blog',
    //     images_upload_url: '/upload-image.php', 
    //     setup: function (editor) {
    //         editor.on('change', function () {
    //             editor.save();
    //         });
    //     }
    // });

    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.15/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.15/cropper.min.js"></script>

</head>

<body>

    <form action="{{ route('save.product') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-container">
        <h2>Save Prodcut</h2>
            <!-- Title -->
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Enter category title" required value="{{ old('title') }}">
                @error('title')
                    <div class="text-danger"  style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter category description" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger"  style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nav ID (Dropdown) -->
            <div class="form-group">
                <label for="nav_id1">Boxes by Industry</label>
                <select id="nav_id1" name="category_id1">
                    <option value="0">Select an Option (if needed)</option>
                    @foreach ($nav1 as $category1)
                        <option value="{{ $category1->id }}" {{ old('category_id') == $category1->id ? 'selected' : '' }}>{{ $category1->title }}</option>
                    @endforeach
                </select>
                @error('category_id1')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nav_id2">Boxes by Material</label>
                <select id="nav_id2" name="category_id2">
                    <option value="0">Select an Option (if needed)</option>
                    @foreach ($nav2 as $category2)
                        <option value="{{ $category2->id }}" {{ old('category_id') == $category2->id ? 'selected' : '' }}>{{ $category2->title }}</option>
                    @endforeach
                </select>
                @error('category_id2')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nav_id3">Boxes by Style</label>
                <select id="nav_id3" name="category_id3">
                    <option value="0">Select an Option (if needed)</option>
                    @foreach ($nav3 as $category3)
                        <option value="{{ $category3->id }}" {{ old('category_id') == $category3->id ? 'selected' : '' }}>{{ $category3->title }}</option>
                    @endforeach
                </select>
                @error('category_id3')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="main_img">Image 1 (1:1 Ratio)</label>
                <input type="file" id="main_img" name="image_1" accept="image/*" required>
                @error('image_1')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
                {{-- <div>
                    <img id="main_img_preview" class="img-preview" src="#" alt="Main Image Preview" style="display:none;">
                    <canvas id="main_img_canvas" style="display:none;"></canvas>
                </div> --}}
            </div>
            <div class="form-group">
                <label for="main_img">Image 2 (1:1 Ratio)</label>
                <input type="file" id="main_img" name="image_2" accept="image/*" required>
                @error('image_2')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
                {{-- <div>
                    <img id="main_img_preview" class="img-preview" src="#" alt="Main Image Preview" style="display:none;">
                    <canvas id="main_img_canvas" style="display:none;"></canvas>
                </div> --}}
            </div>
            <div class="form-group">
                <label for="main_img">Image 3 (1:1 Ratio)</label>
                <input type="file" id="main_img" name="image_3" accept="image/*" required>
                @error('image_3')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
                {{-- <div>
                    <img id="main_img_preview" class="img-preview" src="#" alt="Main Image Preview" style="display:none;">
                    <canvas id="main_img_canvas" style="display:none;"></canvas>
                </div> --}}
            </div>
            <div class="form-group">
                <label for="main_img">Image 4 (1:1 Ratio)</label>
                <input type="file" id="main_img" name="image_4" accept="image/*" required>
                @error('image_4')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
                {{-- <div>
                    <img id="main_img_preview" class="img-preview" src="#" alt="Main Image Preview" style="display:none;">
                    <canvas id="main_img_canvas" style="display:none;"></canvas>
                </div> --}}
            </div>
            {{-- <div class="form-group">
                <label for="title">Heading Description</label>
                <input type="text" id="title" name="heading2" placeholder="Enter Heading" required value="{{ old('heading2') }}"></input>
                @error('heading2')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group"> --}}
                {{-- <label for="description">2nD Description</label>
                <textarea id="description2" name="description2" placeholder="Enter category 2nd description" required>{{ old('description2') }}</textarea>
                @error('description2')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
             </div> --}}
            <div class="form-group">
                <label for="main_img">Image 5 (1:1 Ratio) png(desc)</label>
                <input type="file" id="main_img" name="image_5" accept="image/*" required>
                @error('image_5')
                    <div class="text-danger" style="color: red;">{{ $message }}</div>
                @enderror
                {{-- <div>
                    <img id="main_img_preview" class="img-preview" src="#" alt="Main Image Preview" style="display:none;">
                    <canvas id="main_img_canvas" style="display:none;"></canvas>
                </div> --}}
            </div>


    </div>
    {{-- <label for="content">3rd description (for long description)</label> --}}
    {{-- <div class="editor-container">
        <textarea id="editor" name="content"></textarea>
    </div> --}}


    <!-- Submit Button -->
    <div class="form-container" style="text-align: center;">
        <div class="form-group">
            <button type="submit">Save Product</button>
        </div>
    </div>

    </form>


    <script>
        let headerCropper;
        let mainCropper;
        // Handle header image upload and initialize Cropper.js
        // document.getElementById('header_img').addEventListener('change', function(event) {
        //     const [file] = event.target.files;
        //     if (file) {
        //         const headerImgPreview = document.getElementById('header_img_preview');
        //         headerImgPreview.src = URL.createObjectURL(file);
        //         headerImgPreview.style.display = 'block';

        //         headerImgPreview.onload = function () {
        //             if (headerCropper) {
        //                 headerCropper.destroy();  // Destroy old cropper
        //             }

        //             // Initialize new cropper with correct aspect ratio
        //             headerCropper = new Cropper(headerImgPreview, {
        //                 aspectRatio: 2 / 3,  // 2:3 ratio
        //                 viewMode: 1,
        //                 autoCropArea: 1,
        //                 cropBoxResizable: false  // Prevent resizing the crop box
        //             });
        //         };
        //     }
        // });



        // document.getElementById('main_img').addEventListener('change', function(event) {
        //     const [file] = event.target.files;
        //     if (file) {
        //         const mainImgPreview = document.getElementById('main_img_preview');
        //         mainImgPreview.src = URL.createObjectURL(file);
        //         mainImgPreview.style.display = 'block';

        //         if (mainCropper) {
        //             mainCropper.destroy();
        //         }

        //         mainCropper = new Cropper(mainImgPreview, {
        //             aspectRatio: 1 / 1, // Keep this as 1:1 ratio
        //             viewMode: 1,
        //             autoCropArea: 1,
        //         });
        //     }
        // });

        // On form submit, retrieve the cropped image data
        // document.querySelector('form').addEventListener('submit', function(event) {
        // event.preventDefault(); // Prevent default form submission

        // const headerDataUrl = headerCropper.getCroppedCanvas().toDataURL();

        // // Log the cropped image data URL
        // console.log(headerDataUrl);

        // // Add your AJAX code here to submit the form
        // });
    </script>

</body>

</html>
