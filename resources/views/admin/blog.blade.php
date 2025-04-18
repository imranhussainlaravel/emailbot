<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tiny.cloud/1/vxj76g8udnthdjtd21bsp83sa3kf9qku12j6vv454sn9ihgm/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css')}}">

    <title>Onix Packaging</title>
    <style>
        /* html, body {
            height: 100%;
            margin: 0;
        }*/
      
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
    <form action="{{ route('save.blog')}}" method="POST" enctype="multipart/form-data">

        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" placeholder="Enter category title" required value="{{ old('title') }}">
            @error('title')
                <div class="text-danger"  style="color: red;">{{ $message }}</div>
            @enderror
        </div>
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

        <div class="editor-container">
            <textarea id="editor" name="content"></textarea>
        </div>
        {{-- <button type="submit">Save Content</button> --}}
        <div class="form-group">
            <button type="submit">Save Content</button>
        </div>
    </form>
</body>
</html>
