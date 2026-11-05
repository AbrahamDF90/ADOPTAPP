<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- DNI -->
<div class="mt-4">
    <x-input-label for="dni" :value="__('DNI (sin puntos)')" />
    <x-text-input id="dni" class="block mt-1 w-full" type="text" name="dni" :value="old('dni')" required maxlength="8" />
    <x-input-error :messages="$errors->get('dni')" class="mt-2" />
</div>

<!-- Teléfono -->
<div class="mt-4">
    <x-input-label for="telefono" :value="__('Teléfono')" />
    <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" required />
    <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
</div>

<!-- Dirección -->
<div class="mt-4">
    <x-input-label for="direccion" :value="__('Dirección')" />
    <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion')" required />
    <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
</div>

<!-- Foto -->
<div class="mt-4">
    <x-input-label for="foto" :value="__('Foto de perfil (JPG/PNG)')" />
    <input id="foto" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="file" name="foto" accept="image/*" required />
    <x-input-error :messages="$errors->get('foto')" class="mt-2" />
</div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
