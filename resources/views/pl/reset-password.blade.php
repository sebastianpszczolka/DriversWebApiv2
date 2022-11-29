<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Set New Password') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Scripts -->
    <script type="text/javascript">

        function onPassValidate() {
            const password = document.querySelector('input[name=newPassword]');
            const confirm = document.querySelector('input[name=newPasswordConfirm]');
            let isError = false;

            if (confirm.value !== password.value) {
                isError = true
                password.setCustomValidity('Hasła nie pasują do siebie');
            }

            if (password.value.length < 8) {
                isError = true
                password.setCustomValidity("Hasło powinnno mieć minimalnie 8 znaków");
            }

            if (isError === false) {
                password.setCustomValidity('')
            }
        }

    </script>

</head>
<body>
<div class="font-sans text-gray-900 antialiased">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">

            <form method="POST"
                  action="{{$baselink}}reset-password/{{ $request->route('userId') }}/{{ $request->route('resetCode') }}">

                <!-- Password -->
                <div class="mt-4">
                    <x-label for="newPassword">Hasło</x-label>
                    <x-input onChange="onPassValidate()" id="newPassword" class="block mt-1 w-full" type="password"
                             name="newPassword" required/>
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-label for="newPasswordConfirm">Powtórz hasło</x-label>

                    <x-input onChange="onPassValidate()" id="newPasswordConfirm" class="block mt-1 w-full"
                             type="password"
                             name="newPasswordConfirm" required/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button>
                        Resetuj Hasło
                    </x-button>
                </div>

            </form>
        </div>
    </div>

</div>
</body>
</html>
