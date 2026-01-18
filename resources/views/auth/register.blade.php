@extends('layouts.main')

@section('title', 'Регистрация - Golden Models')

@section('content')

<section class="py-5" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <h2 class="mb-2">Создать аккаунт</h2>
                    <p class="text-muted">Зарегистрируйтесь, чтобы продолжить</p>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Имя</label>
                                <input id="name" 
                                       type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       autocomplete="name" 
                                       autofocus
                                       placeholder="Ваше имя">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email адрес</label>
                                <input id="email" 
                                       type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autocomplete="email"
                                       placeholder="your@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Пароль</label>
                                <input id="password" 
                                       type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="••••••••">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Минимум 8 символов</small>
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">Подтвердите пароль</label>
                                <input id="password-confirm" 
                                       type="password" 
                                       class="form-control" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="••••••••">
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Зарегистрироваться
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center mb-3">
                            <p class="text-muted small">Или зарегистрируйтесь через</p>
                        </div>

                        <!-- Яндекс OAuth -->
                        <div class="d-grid gap-2 mb-2">
                            <a href="{{ route('auth.yandex') }}" class="btn btn-outline-danger">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Войти через Яндекс
                            </a>
                        </div>

                        <!-- VK ID SDK Widget (VK, Mail.ru, OK.ru) -->
                        <div id="vk-auth-container" class="d-grid gap-2"></div>

                        <script src="https://unpkg.com/@vkid/sdk@<3.0.0/dist-sdk/umd/index.js?v={{ time() }}"></script>
                        <script type="text/javascript">
                            if ('VKIDSDK' in window) {
                                const VKID = window.VKIDSDK;

                                VKID.Config.init({
                                    app: {!! config('services.vkid.client_id') !!},
                                    redirectUrl: '{!! route('auth.vk.callback') !!}',
                                    responseMode: VKID.ConfigResponseMode.Callback,
                                    source: VKID.ConfigSource.LOWCODE,
                                    scope: 'email phone'
                                });

                                const oAuth = new VKID.OAuthList();

                                oAuth.render({
                                    container: document.getElementById('vk-auth-container'),
                                    oauthList: [
                                        'vkid',
                                        'mail_ru',
                                        'ok_ru'
                                    ]
                                })
                                .on(VKID.WidgetEvents.ERROR, vkidOnError)
                                .on(VKID.OAuthListInternalEvents.LOGIN_SUCCESS, function (payload) {
                                    const code = payload.code;
                                    const deviceId = payload.device_id;

                                    // Обмениваем код на токен через VK ID SDK
                                    VKID.Auth.exchangeCode(code, deviceId)
                                        .then(function(data) {
                                            // Отправляем данные пользователя на сервер
                                            const form = document.createElement('form');
                                            form.method = 'POST';
                                            form.action = '{!! route('auth.vk.callback') !!}';
                                            
                                            const csrfToken = document.createElement('input');
                                            csrfToken.type = 'hidden';
                                            csrfToken.name = '_token';
                                            csrfToken.value = '{{ csrf_token() }}';
                                            form.appendChild(csrfToken);
                                            
                                            const accessToken = document.createElement('input');
                                            accessToken.type = 'hidden';
                                            accessToken.name = 'access_token';
                                            accessToken.value = data.access_token;
                                            form.appendChild(accessToken);
                                            
                                            document.body.appendChild(form);
                                            form.submit();
                                        })
                                        .catch(vkidOnError);
                                });

                                function vkidOnSuccess(data) {
                                    console.log('VK ID Success:', data);
                                }

                                function vkidOnError(error) {
                                    console.error('VK ID Error:', error);
                                    alert('Ошибка авторизации через VK ID. Попробуйте снова.');
                                }
                            }
                        </script>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Уже есть аккаунт? 
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                    Войти
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
