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
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333333;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            line-height: 1.6;
            color: #555555;
            margin-bottom: 30px;
        }
        .casting-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .casting-card h2 {
            color: #333333;
            font-size: 20px;
            margin: 0 0 20px 0;
            font-weight: 600;
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
            font-weight: 600;
            color: #555555;
            white-space: nowrap;
            width: 40%;
        }
        .info-value {
            display: table-cell;
            padding: 8px 0;
            color: #333333;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #777777;
            font-size: 14px;
            border-top: 1px solid #e0e0e0;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #cccccc, transparent);
            margin: 25px 0;
        }
        .highlight {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .highlight p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🌟 GOLDEN MODELS</h1>
            <p>Приглашение на кастинг</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Здравствуйте, {{ $model->first_name }}!
            </div>

            <div class="message">
                <p>Мы рады сообщить, что вы были отобраны для участия в кастинге!</p>
                <p>Ваш профиль идеально подходит под требования заказчика, и мы приглашаем вас принять участие.</p>
            </div>

            <!-- Casting Card -->
            <div class="casting-card">
                <h2>📋 Детали кастинга</h2>
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
                <h3 style="color: #333333; font-size: 16px; margin-bottom: 10px;">О проекте:</h3>
                <p style="color: #555555; line-height: 1.6; margin: 0;">{{ $casting->about }}</p>
            </div>
            @endif

            @if($casting->motivation)
            <div style="margin-bottom: 20px;">
                <h3 style="color: #333333; font-size: 16px; margin-bottom: 10px;">Детали:</h3>
                <p style="color: #555555; line-height: 1.6; margin: 0;">{{ $casting->motivation }}</p>
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

            <div style="text-align: center; color: #777777; font-size: 14px;">
                <p>Если у вас есть вопросы, свяжитесь с нами:</p>
                <p><strong>Email:</strong> {{ config('mail.from.address') }}</p>
                @if($casting->phone)
                <p><strong>Телефон заказчика:</strong> {{ $casting->phone }}</p>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Golden Models</strong></p>
            <p>Профессиональное модельное агентство</p>
            <p style="margin-top: 15px;">
                <a href="{{ url('/') }}">Наш сайт</a> | 
                <a href="{{ url('/models') }}">Каталог моделей</a> | 
                <a href="{{ url('/contact') }}">Контакты</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #999999;">
                Вы получили это письмо, потому что зарегистрированы в модельном агентстве Golden Models.
            </p>
        </div>
    </div>
</body>
</html>
