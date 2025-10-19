<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Cliente</title>
</head>
<body>
    <h1>Bem-vindo, {{ Auth::user()->name }}</h1>
    <a href="{{ route('logout') }}">Sair</a>
</body>
</html>