<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ModelProfile;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request, $modelId)
    {
        $model = ModelProfile::active()->findOrFail($modelId);

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

        $validated['model_id'] = $modelId;
        
        // Объединяем message в event_description если есть
        if (!empty($validated['message'])) {
            $validated['event_description'] = $validated['message'];
            unset($validated['message']);
        }

        Booking::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Заявка успешно отправлена']);
        }

        return redirect()->back()->with('success', 'Заявка успешно отправлена! Мы свяжемся с вами в ближайшее время.');
    }
}
