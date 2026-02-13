{{-- 
    Компонент для редактируемого текста
    
    Использование:
    <x-editable-text :page="$page" field="title" tag="h1" class="hero-title">
        {{ $page->title }}
    </x-editable-text>
--}}

@props([
    'page',
    'field',
    'tag' => 'span',
    'class' => '',
])

@php
    $attributes = $attributes->merge([
        'class' => $class,
    ]);
    
    // Добавляем атрибуты для редактирования только в режиме inline-edit
    if (isset($inlineEditMode) && $inlineEditMode) {
        $attributes = $attributes->merge([
            'data-editable' => 'true',
            'data-page-id' => $page->id,
            'data-field' => $field,
        ]);
    }
@endphp

<{{ $tag }} {{ $attributes }}>{{ $slot }}</{{ $tag }}>
