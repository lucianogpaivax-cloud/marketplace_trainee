<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
</head>
<body>
    <h1>Bem-vindo, {{ Auth::user()->name }}</h1>
    <p>Aqui você poderá gerenciar vendedores, clientes e relatórios do sistema.</p>
    <a href="{{ route('logout') }}">Sair</a>
</body>
</html>