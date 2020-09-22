<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Project Manager</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700,800,900" rel="stylesheet">

</head>

<body style="background:#f1f1f1;padding-top:20px;padding-bottom:20px;">
    <center>
        <table class="" border="0" cellspacing="0" cellpadding="0" width="600"
            style="width:6.25in;background:#ffffff; border-collapse:collapse">
            <tbody>
                <tr>
                    <td height="10"></td>
                </tr>

                <tr>
                    <td style="padding-left:20px;" align="center">
                        <p style="font-weight:600;font-size:36px;"><span style="color:#004000;">Proj</span><span style="color:#99d9ea;">ect</span><span style="color:#5fa659">Manager</span></p>
                    </td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td height="10" style="padding:20px;font-size:20px;">
                        Your Email: {{ $data['email'] }}
                    </td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td height="10" style="padding:20px;font-size:20px;">
                        Your Password: {{ $data['password'] }}
                    </td>
                </tr>
            </tbody>
        </table>
    </center>
</body>

</html>
