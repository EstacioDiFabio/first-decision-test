@extends('layout')
@section('title', 'Cadastro de Usuário')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-half max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Cadastro de Usuário</h2>

        <form id="form" class="space-y-4">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        minlength="3" maxlength="50"
                       class="@error('name') is-invalid @enderror mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
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

/**
 * Classe Form
 * Valida as entradas e envia a requisição
*/
class Form {

    init() {
        this.send();
    }

    send() {
        var button = document.querySelector('#form-submit');
        var self = this;

        button.addEventListener('click', function(event) {
            event.preventDefault();

            if (self._validate()) {
                self._fetch();
            }
        })
    }

    _validate() {
        var validateClass = new FormValidation();
        validateClass.run();
        var pass = true;

        if (validateClass.getErrors()) {
            alert(validateClass.errorsTemplate())
            pass = false;
        }

        return pass;
    }

    _fetch() {
        var token = document.querySelector('meta[name="csrf-token"]')

        fetch('/api/user', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                'X-CSRF-TOKEN': token.getAttribute('content')
            },
            body: JSON.stringify(this._formData())
        })
        .then(async res => {

            return await res.json();
        })
        .then(data => {
            if (data.validationErrors) {
                alert(data.validationErrors);
            } else if (data.errors) {
                alert('Um erro ocorreu.');
                console.error(data.errors)
            } else {
                alert(data.message);
                this._formReset();
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    }

    _formData() {

        return {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('password_confirmation').value,
        }
    }

    _formReset() {
        document.getElementById('name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('password').value = '';
        document.getElementById('password_confirmation').value = '';
    }
}

/**
 * Classe FormValidation
 * Valida as entradas
*/
class FormValidation {

    constructor() {
        this.errors = [];
    }

    run() {
        this.errors = [];

        this._validateField('name', 'Nome', 3, 50);
        this._validateEmail();
        this._validateField('password', 'Senha', 6, 20);
        this._validateField('password_confirmation', 'Confirmação de Senha', 6, 20);
        this._checkPasswords();
    }

    _validateField(fieldId, fieldName, minlength, maxlength) {
        var field = document.getElementById(fieldId);

        if (field.value.trim() == '') {
            this.errors.push('O campo '+fieldName+' não pode ficar em branco');
        } else if (field.value.trim().length < minlength ) {
            this.errors.push('O campo '+fieldName+' não pode conter menos que '+minlength+' caracteres')
        }
        if (field.value.trim().length > maxlength) {
            this.errros.push('O campo '+fieldName+' não pode conter mais do que '+maxlength+' caracteres')
        }
    }

    _validateEmail() {
        var email = document.getElementById("email");
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email.value.trim() == '') {
            this.errors.push('O campo e-mail não pode ficar em branco');
        } else if (!emailPattern.test(email.value.trim())) {
            this.errors.push('O campo e-mail não possui um valor válido.');
        }
    }

    _checkPasswords() {
        var password = document.getElementById('password');
        var password_confirmation = document.getElementById('password_confirmation');

        if (password_confirmation.value !== password.value) {
            this.errors.push('As senhas devem ser preenchidas com o mesmo valor.');
        }
    }

    getErrors() {

        return this.errors.length > 0;
    }

    errorsTemplate() {

        return this.errors.map((error, i) => `${i + 1}. ${error}`).join('\n');
    }

}

window.addEventListener('load', function() {
    (new Form()).init();
})
</script>
@endsection
