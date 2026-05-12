<!DOCTYPE html>
<html lang="en">
<head>

    @include('partials.header')

</head>
<body>

<div class="d-flex">

    @include('partials.sidebar')

    <div class="p-4 w-100">

        @yield('content')

    </div>

</div>

@include('partials.footer')

</body>
</html>