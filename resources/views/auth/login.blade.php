<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema POS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #2c2c2c;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            background-color: #363636;
            border-radius: 8px;
            box-shadow: 0 2px 3px rgba(0,0,0,0.3);
        }
        .card-header {
            background-color: #292929;
            border-radius: 8px 8px 0 0;
        }
        .card-header-title {
            color: #ffffff;
        }
        .label {
            color: #ffffff;
        }
        .input {
            background-color: #404040;
            border-color: #4a4a4a;
            color: #ffffff;
        }
        .input:focus {
            border-color: #485fc7;
            box-shadow: 0 0 0 0.125em rgba(72, 95, 199, 0.25);
        }
        .button.is-primary {
            background-color: #485fc7;
        }
        .button.is-primary:hover {
            background-color: #3e54b3;
        }
        .help.is-danger {
            color: #f14668;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-4">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title is-centered">
                            <span class="icon mr-2">
                                <i class="fas fa-user"></i>
                            </span>
                            Iniciar Sesión
                        </p>
                    </header>
                    <div class="card-content">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="field">
                                <label class="label">Correo Electrónico</label>
                                <div class="control has-icons-left">
                                    <input class="input @error('email') is-danger @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                @error('email')
                                    <p class="help is-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="field">
                                <label class="label">Contraseña</label>
                                <div class="control has-icons-left">
                                    <input class="input @error('password') is-danger @enderror" type="password" name="password" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <p class="help is-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-primary is-fullwidth">
                                        Iniciar Sesión
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
