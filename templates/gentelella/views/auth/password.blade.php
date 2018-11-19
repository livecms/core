<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ LC_GetTitle() }}</title>

    <!-- Custom Theme Style -->
    <link href="{{ LC_Asset() }}/css/main.css" rel="stylesheet">
    @if ($action ?? null)
    <script>
      window.location.hash = '{{$action}}';
    </script>
    @endif
  </head>

  <body class="login">
    <div>

      <div class="login_wrapper">

        <div id="password" class="animate form password_form">
          <section class="login_content">
            <form action="{{LC_Route('password.reset.post')}}" method="POST">
              {!! csrf_field()!!}

              <input type="hidden" name="token" value="{{ $token }}">

              <h1>Create New Password</h1>

              <div>
                <input id="email" type="email" class="form-control" name="email" placeholder="{{ __('Email') }}" value="{{ $email or old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
              </div>
              <div>
                <input id="password" type="password" class="form-control" name="password" placeholder="{{ __('Password') }}" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
              </div>
              <div>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Password Confirmation') }}" required>
              </div>
              <div>
                <button type="submit" class="btn btn-default submit">Save Password</button>
                <a class="reset_pass" href="{{LC_Route('login')}}">Login?</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                @if (LC_CurrentConfig('allow_register', false))
                <p class="change_link">New to site?
                  <a href="{{LC_Route('register')}}" class="to_register"> Create Account </a>
                </p>
                @endif

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1>{{LC_CurrentConfig('name')}}</h1>
                  <p>&copy; {{date('Y')}} Powered by <a href="https://github.com/livecms">LiveCMS</a></p>
                </div>
              </div>
            </form>
          </section>
        </div>

      </div>
    </div>

    <script src="{{ LC_Asset() }}/js/main.js"></script>
    <script>
      @if ($errors->count())
      Swal('', '{!! addslashes($errors->first()) !!}', 'error');
      @endif
      @if ($status = session('status'))
      Swal('', '{!! $status !!}');
      @endif
      @if ($success = session('success'))
      Swal('', '{!! $success !!}');
      @endif
    </script>
  </body>
</html>
