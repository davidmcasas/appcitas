<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Citas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="relative overflow-x-auto mb-8">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-700">
                            <thead class="text-xs uppercase bg-gray-300 text-gray-800">
                            <tr>
                                <th scope="col" class="px-4 py-3">Fecha</th>
                                <th scope="col" class="px-4 py-3">Tipo</th>
                                <th scope="col" class="px-4 py-3">Nombre</th>
                                <th scope="col" class="px-4 py-3">DNI</th>
                                <th scope="col" class="px-4 py-3">Teléfono</th>
                                <th scope="col" class="px-4 py-3">Email</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\App\Models\Appointment::all() as $appointment)
                                <tr class="odd:bg-gray-100 even:bg-gray-200 border-b border-gray-200">
                                    <td class="px-4 py-2">{{ $appointment->date }}</td>
                                    <td class="px-4 py-2">{{ $appointment->type === 'first' ? 'Primera Cita' : 'Revisión' }}</td>
                                    <td class="px-4 py-2">{{ $appointment->name }}</td>
                                    <td class="px-4 py-2">{{ $appointment->id_card }}</td>
                                    <td class="px-4 py-2">{{ $appointment->phone }}</td>
                                    <td class="px-4 py-2">{{ $appointment->email }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
