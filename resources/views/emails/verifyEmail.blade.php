<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    hi, {{$token}}


    @include('layouts.header')
    <div class="d-flex justify-content-center align-items-center p-5" style="background: aliceblue;width:100%">
        <div class="bg-white rounded" style="width:500px">
            <div class="header">
                <img class="w-100" src="{{ asset('tools/dist/img/social.jpg') }}" alt="">
            </div>
            <div class="actions">
                <div class="d-flex justify-content-between p-3">
                    <div>
                        <h3 class="fw-bold" style="color:#06283D;">
                            Welcome to E-volve Technology Systems.
                        </h3>
                    </div>
                    <div class="text-end w-50">
                        <img class="img-fluid rounded" src="{{ asset('tools/evolve/Logo/Logo-01.png') }}" alt="">
                    </div>
                </div>
                <div class="d-flex mb-2 ms-3">
                    Code: {{$token}}
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')

</body>
</html>
