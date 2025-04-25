<!DOCTYPE html>
<html>

<head>
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Restablecer Contraseña</h2>
        <p>Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el siguiente enlace para continuar:</p>

        <p><a href="<?= $enlace ?>" class="button">Restablecer Contraseña</a></p>

        <p>Si no solicitaste este cambio, puedes ignorar este mensaje.</p>
        <p>El enlace expirará en 1 hora.</p>

        <p>Atentamente,<br>El equipo de soporte</p>
    </div>
</body>

</html>