<x-app-layout>

    <script>
        $(document).ready(function() {
            $('#id_card').on('change', function() {

                $.ajax({
                    url: "{{ route('ajax-check-id-card') }}",
                    data: {
                        id_card: $('#id_card').val(),
                    },
                }).done(function(response) {
                    $('#input_revision').prop("disabled", !response.exists);
                });
            });
        });
    </script>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 bg-gray-200 py-4">

        <form method="post" action="{{ route('appointments.create') }}">
            @csrf

            <h1 class="text-lg font-bold mb-4">Formulario de solicitud de cita</h1>

            <div class="grid gap-6 mb-6 md:grid-cols-2">

                <div class="my-0">
                    <label>
                        <span class="block text-gray-600">Nombre</span>
                        <input required name="name" value="{{ old('name') ? : null }}" type="text" class="rounded-lg">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </label>
                </div>

                <div class="my-0">
                    <label>
                        <span class="block text-gray-600">DNI</span>
                        <input required id="id_card" name="id_card" value="{{ old('id_card') ? : null }}" type="text" class="rounded-lg">
                        <x-input-error :messages="$errors->get('id_card')" class="mt-2" />
                    </label>
                </div>

                <div class="my-0">
                    <label>
                        <span class="block text-gray-600">Teléfono</span>
                        <input required name="phone" value="{{ old('phone') ? : null }}" type="text" class="rounded-lg">
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </label>
                </div>

                <div class="my-0">
                    <label>
                        <span class="block text-gray-600">Email</span>
                        <input required name="email" value="{{ old('email') ? : null }}" type="email" class="rounded-lg">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </label>
                </div>

                <div class="my-0">

                    <span class="block">Tipo de Cita</span>
                    <fieldset id="appointment-type">

                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <label>
                            <span class="text-gray-600">Primera Cita</span>
                            <input type="radio" value="first" name="appointment_type" checked>
                        </label>
                        <label>
                            <span class="text-gray-600">Revisión</span>
                            <input id="input_revision" class="disabled:bg-gray-600" type="radio" value="revision" name="appointment_type" disabled>
                        </label>
                        </div>
                    </fieldset>
                </div>

                <div class="my-4">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg border-2 border-blue-300">Pedir Cita</button>
                </div>

            </div>

        </form>

    </div>


</x-app-layout>
