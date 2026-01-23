<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Описание вещи изменено</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-radius: 5px;">
        <h2 style="color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px;">
            📝 Описание вещи обновлено
        </h2>

        <p>Привет, <strong>{{ $thing->master->name }}</strong>!</p>

        <p>
            Описание вашей вещи <strong>"{{ $thing->name }}"</strong> было изменено.
        </p>

        <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0;">
            <h4 style="margin-top: 0;">Информация о вещи:</h4>
            <p><strong>Название:</strong> {{ $thing->name }}</p>
            <p><strong>Дата создания:</strong> {{ $thing->created_at->format('d.m.Y H:i') }}</p>
            @if($thing->warranty)
                <p><strong>Гарантия до:</strong> {{ $thing->warranty->format('d.m.Y') }}</p>
            @endif
        </div>

        <div style="background-color: #f1f3f5; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <h4 style="margin-top: 0;">📖 Текущее описание:</h4>
            <p style="white-space: pre-wrap; word-wrap: break-word;">
                {{ $thing->description ?: '(пусто)' }}
            </p>
        </div>

        <a href="{{ route('things.show', $thing) }}" 
           style="display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 20px 0;">
            Посмотреть вещь в системе
        </a>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">

        <p style="font-size: 12px; color: #999;">
            Это автоматическое письмо от системы управления хранилищем вещей.
            <br>
            Пожалуйста, не отвечайте на это письмо.
        </p>
    </div>
</body>
</html>
