<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ModelProfile;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request, $modelId)
    {
        // Валидация ID модели
        $validated_id = validator(['id' => $modelId], ['id' => 'required|integer|min:1'])->validate();
        $model = ModelProfile::active()->findOrFail($validated_id['id']);

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'company_name' => 'nullable|string|max:255',
            'event_type' => 'nullable|string',
            'event_description' => 'nullable|string|max:1000',
            'event_date' => 'nullable|date',
            'event_time' => 'nullable|string',
            'event_location' => 'nullable|string|max:255',
            'duration_hours' => 'nullable|integer',
            'budget' => 'nullable|numeric',
            'message' => 'nullable|string',
        ]);

        $validated['model_id'] = $validated_id['id'];
        
        // Санитизация текстовых полей
        if (!empty($validated['client_name'])) {
            $validated['client_name'] = strip_tags($validated['client_name']);
        }
        if (!empty($validated['company_name'])) {
            $validated['company_name'] = strip_tags($validated['company_name']);
        }
        if (!empty($validated['event_description'])) {
            $validated['event_description'] = strip_tags($validated['event_description']);
        }
        if (!empty($validated['event_location'])) {
            $validated['event_location'] = strip_tags($validated['event_location']);
        }
        
        // Объединяем message в event_description если есть
        if (!empty($validated['message'])) {
            $validated['event_description'] = strip_tags($validated['message']);
            unset($validated['message']);
        }

        Booking::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Заявка успешно отправлена']);
        }

        return redirect()->back()->with('success', 'Заявка успешно отправлена! Мы свяжемся с вами в ближайшее время.');
    }
}
