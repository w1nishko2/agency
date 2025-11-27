@extends('layouts.main')

@section('title', 'Спасибо за заявку - Golden Models')

@section('content')

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="mb-4">
                    <i class="bi bi-check-circle text-success" style="font-size: 5rem;"></i>
                </div>
                
                <h1 class="mb-4">Спасибо за вашу заявку!</h1>
                
                <p class="lead text-muted mb-4">
                    Ваша анкета успешно отправлена на модерацию
                </p>
                
                <p class="text-muted mb-5">
                    Наши специалисты рассмотрят вашу заявку в течение 1-2 рабочих дней. 
                    Мы свяжемся с вами через Telegram или по указанному телефону.
                </p>
                
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ url('/') }}" class="btn btn-outline-dark">На главную</a>
                    <a href="{{ url('/models') }}" class="btn btn-primary">Посмотреть модели</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
