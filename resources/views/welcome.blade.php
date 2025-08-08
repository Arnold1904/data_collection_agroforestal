<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingeniería Agroforestal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg" style="background-color: #1F2937;">
        <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <img src="{{ asset('images/agroforestal.webp') }}" alt="Logo Agroforestal" width="auto" height="80px" class="d-inline-block align-text-top" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="border-color: white;">
            <span class="navbar-toggler-icon" style="background-image: url('data:image/svg+xml;charset=utf8,%3csvg viewBox=%270 0 30 30%27 xmlns=%27http://www.w3.org/2000/svg%27%3e%3cpath stroke=%27rgba(255, 255, 255, 1)%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-miterlimit=%2710%27 d=%27M4 7h22M4 15h22M4 23h22%27/%3e%3c/svg%3e');"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0 align-items-center gap-2">
            @if (Route::has('login'))
                <li class="nav-item">
                <a class="btn btn-light px-3 py-1" href="{{ route('login') }}">Iniciar Sesión</a>
                </li>
                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="btn btn-light px-3 py-1" href="{{ route('register') }}">Registrarme</a>
                </li>
                @endif
            @endif
            <li class="nav-item">
                <!-- Icono usuario SVG -->
                <span class="d-inline-block  rounded-circle p-1" style="width:32px; height:32px;">
                <svg width="28" height="28" viewBox="0 0 40 40" fill="none"><circle cx="20" cy="20" r="18" stroke="#191400" stroke-width="2"/><circle cx="20" cy="16" r="6" stroke="#191400" stroke-width="2"/><path d="M8 32c0-4 8-6 12-6s12 2 12 6" stroke="#191400" stroke-width="2" fill="none"/></svg>
                </span>
            </li>
            </ul>
        </div>
        </div>
    </nav>
    <div class="container my-5">
        {{-- Título Principal --}}
        <h1 class="text-center display-4 mb-5">DATA COLLECTION</h1>

    {{-- Fila de Imágenes --}}
    <div class="row text-center">
        <div class="col-12 col-md-4 mb-4">
            <img src="{{ asset('images/ingenieria-forestal-foto.webp') }}" 
                class="img-fluid rounded shadow" 
                style="height: 250px; width: 100%; object-fit: cover;" 
                alt="Investigadores en bosque">
        </div>
        <div class="col-12 col-md-4 mb-4">
            <img src="{{ asset('images/reforestacion-realizada-por-grupo-voluntario.webp') }}" 
                class="img-fluid rounded shadow" 
                style="height: 250px; width: 100%; object-fit: cover;" 
                alt="Plantando un árbol">
        </div>
        <div class="col-12 col-md-4 mb-4">
            <img src="{{ asset('images/biologo-en-un-bosque.webp') }}" 
                class="img-fluid rounded shadow" 
                style="height: 250px; width: 100%; object-fit: cover;" 
                alt="Examinando una hoja con lupa">
        </div>
    </div>

        {{-- Párrafo de Bienvenida --}}
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <p class="lead text-center text-muted">
                    Bienvenido a Data Collection, es una herramienta lista para obtener datos sobre 
                    la ingeniería agroforestal. Utilizada en proyectos de investigación en la universidad del 
                    trópico americano Unitropico, esta herramienta permite gestionar la información para que sea 
                    mucho más legible y amigable con el usuario.
                </p>
            </div>
        </div>
    
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
