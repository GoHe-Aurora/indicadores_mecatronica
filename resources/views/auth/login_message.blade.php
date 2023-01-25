<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
@extends('layout.auth')
@section('contenido')
    @if (session('messageDelete'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <span class="text-sm"> <a href="javascript:;" class="alert-link">Lo sentimos</a>,
                {{ session('messageDelete') }}.</span>
            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <!-- Esta es la vista principal de Login los demas estilos estan en el Carpeta layout/auth/auth.blade.php -->
        INICIAR SESIÓN
        <br>
        <br>
        <div class="form-group">
            <input type="text" class="form-control rounded-left" class="@error('email') is-invalid @enderror"
                placeholder="Correo electrónico" name="email">
            @error('email')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            {{-- <input type="password" class="form-control rounded-left"  class="@error('password') is-invalid @enderror" placeholder="Contraseña" name="password" > --}}
            <div class="input-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" value="{{ old('password') }}">
                <button class="btn btn-outline" type="button" id="show_password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            @error('password')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div class="form-group d-md-flex">
            <div align="center">
                <a href="{{ route('password.request') }}">{{ '¿Olvidaste tu contraseña?' }}</a>
                <button type="submit" class="btn btn-primary btn-sm rounded submit mt-3" id="login">Iniciar sesión</button>
            </div>
        </div>
        <br>
        <br>
        <div class="card-footer">
            <div>
                <h6 align=center>Universidad Tecnol&oacute;gica del Valle de Toluca</h6>
                <h6 align=center>¡¡Siempre Cuervos!!</h6>
            </div>
        </div>
    </form>

    <script>
        //Script del boton de mostrar contraseña en el formulario de registro de unosolo item
        $('#show_password').on('click', function() {
            var password = $('#password');
            if (password.attr('type') === 'password') {
                password.attr('type', 'text');
            } else {
                password.attr('type', 'password');
            }
        });
         //Script que cierra la notificacion de error de login
        $('.btn-close').on('click', function() {
            $('.alert').hide();
        });
    </script>
    <script>
        //Script que redirecciona a la pagina de login si el usuario pulsa el boton de login
        $(document).ready(function() {
            $('#login').click(function() {
                window.location.href = "{{ route('login') }}";
            });
        });
    </script>
@endsection