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
                    Ваш запрос на подбор модели успешно отправлен
                </p>
                
                <p class="text-muted mb-5">
                    Наш менеджер свяжется с вами в течение 24 часов и предложит подходящие кандидатуры, 
                    соответствующие вашим критериям. Мы отправим вам портфолио моделей на email 
                    или свяжемся по указанному телефону.
                </p>
                
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ url('/') }}" class="btn btn-outline-dark">На главную</a>
                    <a href="{{ url('/models') }}" class="btn btn-primary">Посмотреть наших моделей</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
