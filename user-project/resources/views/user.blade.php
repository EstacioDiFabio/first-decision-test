@extends('layout')
@section('title', 'Cadastro de Usuário')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="flex flex-col gap-6 items-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Cadastro de Usuário</h2>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->has('error'))
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <form id="form-submit"
                action="{{ route('store') }}" method="POST"
                class="space-y-4">
                @csrf
                <div>
                    <label for="name">Nome</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('name') border-red-500 @enderror">
                    @error('name')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="email">E-mail</label>
                    <input type="text" id="email" name="email" value="{{ old('email') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('password') border-red-500 @enderror">
                    @error('password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation">Confirmação de Senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <button type="submit"
                        class="w-full bg-blue-500 text-black py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors">
                    Cadastrar
                </button>
            </form>
        </div>
        <div id="tableData" style="display: none" class="bg-white p-8 rounded-lg shadow-md w-half max-w-md">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-slate-700 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-slate-700 uppercase tracking-wider">Nome</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-slate-700 uppercase tracking-wider">Email</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>

window.addEventListener('load', function() {
    (new Form()).init();
    (new DataTable()).init();
})

/**
 * Classe Form
 * Valida as entradas e envia a requisição
*/
class Form {

    init() {
        // descomentar para enviar os dados via api
        // remover os atributos action e method do <form> também

        // this.send();
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

        fetch('/api/user', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
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
                this.mock();
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

class DataTable {

    init() {
        this.obtainData();
    }

    obtainData() {
        fetch('/api/users', {
            method: 'GET',
        })
        .then(async res => {

            return await res.json();
        })
        .then(reqData => {
            if (reqData.data.length > 0) {
                this.createTable(reqData.data)
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    }

    createTable(usersData) {
        var table = document.querySelector('#tableData').removeAttribute('style');
        var tbody = document.getElementById('tableBody');

        tbody.innerHTML = "";

        usersData.forEach(user => {
            var tr = document.createElement('tr');

            tr.appendChild(this._createColumn(user.id));
            tr.appendChild(this._createColumn(user.name));
            tr.appendChild(this._createColumn(user.email));

            tbody.appendChild(tr);
        });
    }

    _createColumn(data) {
        var columnClasses = 'px-6 py-4 whitespace-nowrap text-sm text-slate-900'
        let column = document.createElement('td');
            column.className = columnClasses;
            column.textContent = data;

        return column;
    }
}
</script>
@endsection
