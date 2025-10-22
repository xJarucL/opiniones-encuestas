<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recuperación de Contraseña</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 0;">
        <tr>
            <td>
                <table align="center" width="100%" max-width="600px" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin: auto;">
                    <tr>
                        <td style="padding: 30px; text-align: center; background-color: #1f2937;">
                            <h2 style="color: #ffffff; margin: 0;">Encuestas y Opiniones</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p style="font-size: 16px; color: #333333;">Hola,</p>
                            <p style="font-size: 16px; color: #333333;">
                                Hemos recibido una solicitud para restablecer tu contraseña.
                                Si fuiste tú, haz clic en el siguiente botón para continuar:
                            </p>

                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ url('/reset-password?token=' . $token) }}"
                                   style="padding: 12px 24px; background-color: #6c26dcff; color: #ffffff;
                                   text-decoration: none; font-weight: bold; border-radius: 5px;">
                                   Restablecer Contraseña
                                </a>
                            </p>

                            <p style="font-size: 14px; color: #555555;">
                                Si no realizaste esta solicitud, simplemente ignora este mensaje.
                                Tu cuenta está segura.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
