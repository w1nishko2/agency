{{-- Компонент для подключения системы inline-редактирования --}}

@if(isset($inlineEditMode) && $inlineEditMode)
    {{-- Подключаем стили inline-редактора --}}
    <link rel="stylesheet" href="{{ asset('css/inline-editor.css') }}">
    
    {{-- Подключаем скрипт inline-редактора --}}
    <script src="{{ asset('js/inline-editor.js') }}" defer></script>
    
    {{-- Устанавливаем data-атрибут для body --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.setAttribute('data-inline-edit', 'true');
        });
    </script>
@endif
