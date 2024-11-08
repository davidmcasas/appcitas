<x-app-layout>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 bg-gray-200 py-4">

        <h1 class="text-lg font-bold">Se ha registrado su cita correctamente.</h1>

        <span>Tipo de cita: {{ $appointment->type === "first" ? 'Primera Cita' : 'Revisión' }}</span>
        <br>
        <span>Fecha: {{ $appointment->date->format('d/m/Y') }}</span>
        <br>
        <span>Hora: {{ $appointment->date->format('H:i') }}</span>

        <div class="my-4">
            <a href="{{ route('appointments.new') }}" class="bg-blue-500 text-white px-6 py-2 rounded-lg border-2 border-blue-300">Volver</a>
        </div>

    </div>

</x-app-layout>
