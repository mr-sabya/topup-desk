<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Service Paused</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8fafc;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: sans-serif;
        }

        .pause-card {
            background: white;
            padding: 40px;
            border-radius: 30px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            max-width: 400px;
        }

        .icon-circle {
            width: 100px;
            height: 100px;
            background: #fee2e2;
            color: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
    </style>
</head>

<body>
    <div class="pause-card animate__animated animate__pulse animate__infinite">
        <div class="icon-circle">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5.5 3.5A.5.5 0 0 1 6 4v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5zm5 0a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5z" />
            </svg>
        </div>
        <h2 class="fw-bold">Service Paused</h2>
        <p class="text-muted">We are currently updating our systems. Please check back in a few minutes.</p>
        <div class="spinner-grow text-primary spinner-grow-sm" role="status"></div>
    </div>
</body>

</html>