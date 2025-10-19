<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>

    <!-- Título principal da página -->
    <h2>Crie sua conta</h2>

    <!-- Exibe mensagens de erro de validação ) -->
    @if ($errors->any())
        <div>
            <ul>
                <!-- Loop pelos erros retornados pelo controller -->
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulário de cadastro -->
    <!-- O método é POST, e a rota usada é 'register' -->
    <form action="{{ route('register') }}" method="POST">
        @csrf

        <!-- Campo: Nome -->
        <label for="name">Nome completo:</label><br>
        <input type="text" name="name" id="name" required><br><br>

        <!-- Campo: E-mail -->
        <label for="email">E-mail:</label><br>
        <input type="email" name="email" id="email" required><br><br>

        <!-- Campo: Senha -->
        <label for="password">Senha:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <!-- Campo: Confirmar senha -->
        <label for="password_confirmation">Confirmar senha:</label><br>
        <input type="password" name="password_confirmation" id="password_confirmation" required><br><br>

        <!-- Campo: Tipo de conta -->
        <label for="role">Tipo de conta:</label><br>
    <select name="role" id="role" required>
        <option value="">Selecione...</option>
        <option value="cliente">Cliente</option>
        <option value="vendedor">Vendedor</option>
    </select><br><br>

        <!-- Campos extras do vendedor -->
    <div id="seller-fields" style="display: none;">
        <label for="tipo_loja">Tipo de loja:</label><br>
        <input type="text" name="tipo_loja" id="tipo_loja"><br><br>

        <label for="escopo">Escopo:</label><br>
        <select name="escopo" id="escopo">
            <option value="nacional">Nacional</option>
            <option value="internacional">Internacional</option>
        </select><br><br>
    </div>

        <!-- Botão de envio -->
        <button type="submit">Cadastrar</button>
    </form>

    <!-- Link para o login -->
    <p>Já tem uma conta? <a href="{{ route('login') }}">Entrar</a></p>

    <script>
document.getElementById('role').addEventListener('change', function () {
    const sellerFields = document.getElementById('seller-fields');
    if (this.value === 'vendedor') {
        sellerFields.style.display = 'block';
    } else {
        sellerFields.style.display = 'none';
    }
});
</script>

</body>
</html>
