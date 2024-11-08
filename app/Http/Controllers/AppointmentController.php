<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Notifications\AppointmentCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function new() {
        return view('appointments.new');
    }

    public function create(AppointmentRequest $request) {

        $appointment = Appointment::query()->lockForUpdate()->create([
            'name' => $request->name,
            'id_card' => $request->id_card,
            'phone' => $request->phone,
            'email' => $request->email,
            'type' => $request->appointment_type,
            'date' => $this->getNextDateTime(),
        ]);

        // Prevenir citas con hora duplicada por peticiones simultáneas
        while ($this->isDateTimeDuplicated($appointment)) {
            $appointment->date = $this->getNextDateTime();
            $appointment->save();
        }

        $appointment->notify(new AppointmentCreated($appointment));

        return view('appointments.created', compact('appointment'));
    }

    public function ajaxCheckIdCard(Request $request)
    {
        $exists = Appointment::query()->where('id_card', $request->id_card)->exists();
        return response()->json(['exists' => $exists]);
    }

    private function getNextDateTime(): \Illuminate\Support\Carbon
    {
        $dateTime = now()->startOfDay();

        do {
            $dateTime = $dateTime->addDay();
            $appointments = Appointment::query()->whereDate('date', $dateTime->toDateString())->count();
        } while ($appointments >= 12);

        $hour = 10 + $appointments;
        $dateTime->setHour($hour);

        return $dateTime;
    }


    private function isDateTimeDuplicated($appointment): bool
    {
        return Appointment::query()
            ->where('date', $appointment->date->toDateString())
            ->whereNot('id', $appointment->id)
            ->exists();
    }
}
