<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="@yield('description', 'Platform ekonomi sirkular sampah desa.')">
  <title>@yield('title', 'DESA-SIRKULAR | Ekonomi Sirkular Sampah Desa')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>
<body>
  @yield('content')
  @stack('scripts')
</body>
</html>