@extends('layout')
@section('title', 'Cadastro de Usuário')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-half max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Cadastro de Usuário</h2>
        @if(session('success'))
            <div class="mb-4 p-2 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-2 bg-red-100 text-red-800 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="form" action="/api/users" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        minlength="3" maxlength="50"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" id="password" name="password" required
                        minlength="6" maxlength="20"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmação de Senha</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                        minlength="6" maxlength="20"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <button id="form-submit" type="submit"
                        class="w-full bg-blue-500 text-black py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors">
                    Cadastrar
                </button>
            </div>
        </form>
    </div>
</div>
<script>
class Form {
    init() {
        this.validate();
    }

    validate() {
        var button = document.querySelector('#form-submit');
        var self = this;

        button.addEventListener('click', function(event) {
            self._validateField('name', 'Nome', 3, 50);
            self._validateEmail();
            self._validateField('password', 'Senha', 6, 20);
            self._validateField('password_confirmation', 'Confirmação de Senha', 6, 20);
            self._checkPasswords();
        })
    }

    _validateField(fieldId, fieldName, minlength, maxlength) {
        var field = document.getElementById(fieldId);

        if (field.value.trim() == '') {
            alert('O campo '+fieldName+' não pode ficar em branco');
        } else if (field.value.trim().length < minlength ) {
            alert('O campo '+fieldName+' não pode conter menos que '+minlength+' caracteres');
        }
        if (field.value.trim().length > maxlength) {
            alert('O campo '+fieldName+' não pode conter mais do que '+maxlength+' caracteres');
        }
    }

    _validateEmail() {
        var email = document.getElementById("email");
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email.value.trim() == '') {
            alert('O campo e-mail não pode ficar em branco');
        } else if (!emailPattern.test(email.value.trim())) {
            alert('O campo e-mail não possui um valor válido.');
        }
    }

    _checkPasswords() {
        var password = document.getElementById("password");
        var password_confirmation = document.getElementById("password_confirmation");

        if (password_confirmation.value !== password.value) {
            alert('As senhas devem ser preenchidas com o mesmo valor.');
        }
    }
}
window.addEventListener('load', function() {
    (new Form()).init();
})
</script>
@endsection
