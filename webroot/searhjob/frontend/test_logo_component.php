<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тест компонента логотипа</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Тест компонента логотипа</h1>
        <p>Проверяем, как отображается компонент загрузки логотипа:</p>
        
        <?php include 'components/company_logo_upload.php'; ?>
        
        <hr style="margin: 30px 0;">
        <p><a href="edit_profile.php">← Назад к редактированию профиля</a></p>
    </div>
</body>
</html>
