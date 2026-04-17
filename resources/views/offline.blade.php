{{-- resources/views/offline.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tidak Ada Koneksi — PinjamAlat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            background: #f0f4ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            padding: 24px;
            text-align: center;
        }
        .icon {
            font-size: 4rem;
            color: #94a3b8;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: .4; }
        }
        h2 { font-size: 1.4rem; font-weight: 700; color: #0f172a; margin-bottom: 10px; }
        p  { color: #64748b; font-size: .9rem; margin-bottom: 28px; line-height: 1.6; }
        .btn-retry {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #3b82f6;
            color: #fff;
            padding: 12px 28px;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            font-size: .9rem;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s;
        }
        .btn-retry:hover { background: #2563eb; color: #fff; }
    </style>
</head>
<body>
    <div>
        <div class="icon"><i class="bi bi-wifi-off"></i></div>
        <h2>Tidak Ada Koneksi Internet</h2>
        <p>
            Kamu sedang offline. Periksa koneksi internetmu<br>
            lalu coba lagi.
        </p>
        <button class="btn-retry" onclick="window.location.reload()">
            <i class="bi bi-arrow-clockwise"></i>
            Coba Lagi
        </button>
    </div>
</body>
</html>