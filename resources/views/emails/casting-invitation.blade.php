<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Приглашение на кастинг</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #ffffff;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background-color: #000000;
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
            border-bottom: 1px solid #000000;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 14px;
            color: #000000;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .message {
            font-size: 14px;
            line-height: 1.8;
            color: #000000;
            margin-bottom: 30px;
        }
        .casting-card {
            background-color: #f8f8f8;
            border: 1px solid #000000;
            padding: 25px;
            margin: 30px 0;
        }
        .casting-card h2 {
            color: #000000;
            font-size: 16px;
            margin: 0 0 20px 0;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .casting-info {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            padding: 8px 15px 8px 0;
            font-weight: 700;
            color: #000000;
            white-space: nowrap;
            width: 40%;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-value {
            display: table-cell;
            padding: 8px 0;
            color: #000000;
            font-size: 14px;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 35px;
            background-color: #000000;
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            border: 2px solid #000000;
            transition: all 0.3s ease;
        }
        .button:hover {
            background-color: #ffffff;
            color: #000000;
        }
        .footer {
            background-color: #000000;
            padding: 30px;
            text-align: center;
            color: #ffffff;
            font-size: 12px;
            border-top: 1px solid #000000;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #ffffff;
            text-decoration: none;
            border-bottom: 1px solid #ffffff;
        }
        .divider {
            height: 1px;
            background-color: #000000;
            margin: 25px 0;
        }
        .highlight {
            background-color: #f8f8f8;
            border: 1px solid #000000;
            padding: 15px;
            margin: 20px 0;
        }
        .highlight p {
            margin: 0;
            color: #000000;
            font-size: 12px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>GOLDEN MODELS</h1>
            <p>Приглашение на кастинг</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Здравствуйте, {{ $model->first_name }}!
            </div>

            <div class="message">
                <p>Мы рады сообщить, что вы были отобраны для участия в кастинге.</p>
                <p>Ваш профиль идеально подходит под требования заказчика, и мы приглашаем вас принять участие.</p>
            </div>

            <!-- Casting Card -->
            <div class="casting-card">
                <h2>Детали кастинга</h2>
                <div class="casting-info">
                    <div class="info-row">
                        <div class="info-label">Номер заявки:</div>
                        <div class="info-value">#{{ $casting->id }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Заказчик:</div>
                        <div class="info-value">{{ $casting->full_name }}</div>
                    </div>
                    @if($casting->city)
                    <div class="info-row">
                        <div class="info-label">Город:</div>
                        <div class="info-value">{{ $casting->city }}</div>
                    </div>
                    @endif
                    @if($casting->categories_interest && count($casting->categories_interest) > 0)
                    <div class="info-row">
                        <div class="info-label">Категории:</div>
                        <div class="info-value">{{ implode(', ', $casting->categories_interest) }}</div>
                    </div>
                    @endif
                    <div class="info-row">
                        <div class="info-label">Дата создания:</div>
                        <div class="info-value">{{ $casting->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                </div>
            </div>

            @if($casting->about || $casting->motivation)
            <div class="divider"></div>
            
            @if($casting->about)
            <div style="margin-bottom: 20px;">
                <h3 style="color: #000000; font-size: 12px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 2px; font-weight: 700;">О проекте:</h3>
                <p style="color: #000000; line-height: 1.8; margin: 0; font-size: 14px;">{{ $casting->about }}</p>
            </div>
            @endif

            @if($casting->motivation)
            <div style="margin-bottom: 20px;">
                <h3 style="color: #000000; font-size: 12px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 2px; font-weight: 700;">Детали:</h3>
                <p style="color: #000000; line-height: 1.8; margin: 0; font-size: 14px;">{{ $casting->motivation }}</p>
            </div>
            @endif
            @endif

            <div class="highlight">
                <p><strong>Важно:</strong> В ближайшее время с вами свяжется менеджер нашего агентства для уточнения деталей и согласования времени кастинга.</p>
            </div>

            <div class="button-container">
                <a href="{{ route('models.show', $model->id) }}" class="button">Посмотреть мой профиль</a>
            </div>

            <div class="divider"></div>

            <div style="text-align: center; color: #000000; font-size: 12px;">
                <p style="text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Если у вас есть вопросы, свяжитесь с нами:</p>
                <p><strong>Email:</strong> {{ config('mail.from.address') }}</p>
                @if($casting->phone)
                <p><strong>Телефон заказчика:</strong> {{ $casting->phone }}</p>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="font-weight: 700; letter-spacing: 3px; text-transform: uppercase;">GOLDEN MODELS</p>
            <p style="font-size: 10px; letter-spacing: 2px; text-transform: uppercase;">Профессиональное модельное агентство</p>
            <p style="margin-top: 15px;">
                <a href="{{ url('/') }}">Наш сайт</a> | 
                <a href="{{ url('/models') }}">Каталог моделей</a> | 
                <a href="{{ url('/contact') }}">Контакты</a>
            </p>
            <p style="margin-top: 15px; font-size: 10px; opacity: 0.7;">
                Вы получили это письмо, потому что зарегистрированы в модельном агентстве Golden Models.
            </p>
        </div>
    </div>
</body>
</html>
