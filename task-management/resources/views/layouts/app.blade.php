<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
   {{-- <h1 class="text-2xl text-center m-3">@yield('title')</h1>--}}

    @if(session()->has('success'))
        <div class="absolute top-0 left-0 m-4 p-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session()->get('success') }}</span>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="absolute top-0 left-0 m-4 p-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session()->get('error') }}</span>
        </div>
    @endif

    <div>@yield('content')</div>

</body>

</html>
