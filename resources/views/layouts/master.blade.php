<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema Checklist')</title>
    @yield('styles')
</head>
<body>
    @yield('content')
    @yield('scripts')
</body>
</html>