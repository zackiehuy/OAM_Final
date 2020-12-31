<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
</head>
<body class="hold-transition sidebar-mini">
<div id="app" class="wrapper">
    <main-app></main-app>
</div>
<script src="/js/app.js"></script>
</body>
</html>
