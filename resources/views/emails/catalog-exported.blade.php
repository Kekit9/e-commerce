<!DOCTYPE html>
<html>
<head>
    <title>Catalog Export</title>
    <style>
        .button {
            background: #3490dc;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<h1>Catalog Export Completed</h1>
<p><strong>File Name:</strong> {{ $filename }}</p>
<a href="{{ $downloadUrl }}" class="button">Download Catalog</a>
</body>
</html>
