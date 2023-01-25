@extends('layout.auth')
@section('contenido')
<style type="text/css">
.alert-warning{
	text-align: justify;
}

.btn-primary{
	border-radius: 5px;
}

.version{
	text-align: center;
	color: #D1D1D1;
	font-size: smaller;
}

.alert-warning{
	margin-top: 70px;
}

</style>

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="info-fill" fill="currentColor" viewBox="">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
  </symbol>
</svg>

<meta charset="UTF-8">
<title>Inicio de Sesión</title>
<form method="POST" action="{{ route('login') }}">
	@csrf
	<!-- Esta es la vista principal de Login los demas estilos estan en el Carpeta layout/auth/auth.blade.php -->

	<div class="form-group">
	<input type="text" class="form-control rounded-left"  class="@error('email') is-invalid @enderror" placeholder="Correo electrónico" name="email" >
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

	<div class="form-group">
	<div>
	<a href="{{ route('password.request') }}">{{ "¿Olvidaste tu contraseña?" }}</a>
	<button type="submit" class="btn btn-primary submit mt-4">Iniciar sesión</button>
	</div>
	</div>
	<div class="alert alert-warning d-flex align-items-center" role="alert">
  	<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
  	<div>
  	Para registrarte en la plataforma es necesario ponerte en contacto con <b>carlos.millan@utvtol.edu.mx</b>
  	</div>
	</div>
	<br>
	<p class="version"> v1.0 Alpha. <br> Versión estable para escritorio.</p> 
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
    </script>

@endsection
