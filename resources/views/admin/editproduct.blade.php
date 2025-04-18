<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="icon" type="image/x-icon" href="{{ URL('images/logo.png') }}">
    <script src="https://cdn.tiny.cloud/1/vxj76g8udnthdjtd21bsp83sa3kf9qku12j6vv454sn9ihgm/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

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
        .editor-container {
    width: 100%; /* Adjusts width to take the full form container */
    margin: 20px auto;
}

#editor {
    width: 100%;
    min-height: 300px; /* Sets minimum height */
    box-sizing: border-box; /* Ensures padding doesn't affect width */
}
    </style>
      <script>
        tinymce.init({
        selector: '#editor',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist casechange formatpainter advtable',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table checklist | spellcheckdialog a11ycheck typography | align lineheight',
        height: "100%",
        image_title: true,
        automatic_uploads: true,
        file_picker_types: 'image',
        // images_upload_url: '/uploads/blog',
        images_upload_url: '/upload-image.php', 
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });

    </script>
</head>

<body>

    <form action="{{ route('update.product', $product->id) }}" method="POST" enctype="multipart/form-data">
        <div class="form-container">
            <h2>Edit Product</h2>
                @csrf
                @method('PUT') <!-- This is important for PUT requests -->

                <!-- Title -->
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="{{ $product->title }}" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required>{{ $product->description }}</textarea>
                </div>

                <!-- Nav ID (Dropdown) -->
                {{-- <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" required>
                        @foreach ($categories as $category)
                            <option value="{{$category->id}}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{$category->title}}</option>
                        @endforeach
                    </select>
                </div> --}}

                <div class="form-group">
                    <label for="nav_id1">Boxes by Industry</label>
                    <select id="nav_id1" name="category_id1" class="form-control">
                        <option value="">Select an Option (if needed)</option>
                        @foreach ($nav1 as $category1)
                            <option value="{{ $category1->id }}" 
                                {{ $category1->id == $product->industry ? 'selected' : '' }}>
                                {{ $category1->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id1')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="nav_id2">Boxes by Material</label>
                    <select id="nav_id2" name="category_id2" class="form-control">
                        <option value="">Select an Option (if needed)</option>
                        @foreach ($nav2 as $category2)
                            <option value="{{ $category2->id }}" 
                                {{ $category2->id == $product->box ? 'selected' : '' }}>
                                {{ $category2->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id2')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="nav_id3">Boxes by Style</label>
                    <select id="nav_id3" name="category_id3" class="form-control">
                        <option value="">Select an Option (if needed)</option>
                        @foreach ($nav3 as $category3)
                            <option value="{{ $category3->id }}" 
                                {{ $category3->id == optional($product->category)->id ? 'selected' : '' }}>
                                {{ $category3->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id3')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                

                <!-- Image Uploads -->
                @for ($i = 1; $i <= 4; $i++)
                <div class="form-group">
                    <label for="image_{{ $i }}">Image {{ $i }} (1:1 Ratio) "use in case of image update"</label>
                    <input type="file" id="image_{{ $i }}" name="image_{{ $i }}" accept="image/*">
                </div>
                @endfor

                <!-- Heading Description -->
                {{-- <div class="form-group">
                    <label for="heading2">Heading Description</label>
                    <input type="text" id="heading2" name="heading2" value="{{ $product->heading2 }}" required>
                </div> --}}

                <!-- Second Description -->
                {{-- <div class="form-group">
                    <label for="description2">2nd Description</label>
                    <textarea id="description2" name="description2" required>{{ $product->description2 }}</textarea>
                </div> --}}

                <!-- Image 5 -->
                <div class="form-group">
                    <label for="image_5">Image 5 (1:1 Ratio) png(desc) "use in case of image update"</label>
                    <input type="file" id="image_5" name="image_5" accept="image/*">
                    
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                </div>
        </div>
        <label for="content">3rd description (for long description)</label>
        <div class="editor-container">
            <textarea id="editor" name="content">{{ $product->content }}</textarea>
        </div>

        <div class="form-container" style="text-align: center;">
            <div class="form-group">
                <button type="submit">Update Product</button>
            </div>
        </div>
    </form>


</body>

</html>
