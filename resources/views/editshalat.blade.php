<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Edit Jadwal Shalat</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

        @vite(['resources/js/app.js', 'resources/css/app.css'])
    </head>
    <body class="font-sans antialiased bg-gray-900 overflow-hidden">
        <div id="app">
            <editshalat-component 
            current-path="/{{ request()->path() }}">
            </editshalat-component>
        </div>
    </body>
</html>
