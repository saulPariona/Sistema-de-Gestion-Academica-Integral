<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Colegio Max Planck</title>
    <link rel="icon" type="image/png" href="{{ asset('image/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #004f39 0%, #078461 50%, #004f39 100%);
            position: relative;
            overflow: hidden;
        }

        .bg-circles {
            position: absolute;
            inset: 0;
            opacity: 0.07;
        }

        .container {
            text-align: center;
            z-index: 10;
            padding: 2rem;
            max-width: 480px;
        }

        .error-code {
            font-family: 'Playfair Display', serif;
            font-size: 5rem;
            font-weight: 700;
            color: #FFFACA;
            line-height: 1;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .error-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.75rem;
        }

        .error-message {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.8rem;
            background: #FFFACA;
            color: #004f39;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 2px;
            transition: all 0.2s;
            font-family: 'Playfair Display', serif;
        }

        .btn:hover {
            background: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn svg {
            width: 16px;
            height: 16px;
        }

        .footer {
            position: absolute;
            bottom: 1.5rem;
            left: 0;
            right: 0;
            text-align: center;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.7rem;
            font-family: 'Playfair Display', serif;
        }

        @yield('extra-styles')
    </style>
</head>

<body>
    <svg class="bg-circles" viewBox="0 0 800 800" preserveAspectRatio="xMidYMid slice">
        <circle cx="400" cy="400" r="350" fill="none" stroke="white" stroke-width="1" />
        <circle cx="400" cy="400" r="280" fill="none" stroke="white" stroke-width="0.5" />
        <circle cx="400" cy="400" r="200" fill="none" stroke="white" stroke-width="0.5" />
        <circle cx="400" cy="400" r="120" fill="none" stroke="white" stroke-width="0.3" />
    </svg>

    <div class="container">
        <div class="icon-wrapper">
            @yield('icon')
        </div>
        <div class="error-code">@yield('code')</div>
        <h1 class="error-title">@yield('title')</h1>
        <p class="error-message">@yield('message')</p>
        @yield('action')
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Colegio Max Planck &mdash; Sistema de Gestión Académica
    </div>
</body>

</html>
