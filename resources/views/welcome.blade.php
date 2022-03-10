<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{config('app.name')}}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('styling/login.css')}}">
    </head>
    <body class="antialiased">
        <div class="container">

            <div class="login-container">



                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <img src="/images/logo.jpeg" class="logo" alt="logo">
                <form action="{{route('admin.login')}}" method="POST">
                @csrf
                <div class="seperator">
                    <input class="form-element" type="text" name="email" placeholder="username" required>
                    <div>
                    @if($errors->has('email'))
                        <small class="error"><strong>{{$errors->first('email')}}</strong></small>
                    @endif
                    </div>
                </div>
                 <div class="seperator">
                    <input type="password" class="form-element " name="password" placeholder=" password" required>
                    <div>
                    @if($errors->has('password'))
                        <small class="error"><strong>{{$errors->first('password')}}</strong></small>
                    @endif
                    </div>
                </div>
                <div class="seperator button-container">
                    <button class="btn"  type="submit">Login</button>
                </div>
                </form>
            </div>
        </div>
    </body>
</html>
