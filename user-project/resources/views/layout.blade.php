<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'First Decision')</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body>
    @yield('content')
</body>
</html>
