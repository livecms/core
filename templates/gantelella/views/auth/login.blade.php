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
      @if (LC_CurrentConfig('allow_register', false))
      <a class="hiddenanchor" id="register"></a>
      @endif
      <a class="hiddenanchor" id="login"></a>
      <a class="hiddenanchor" id="reset"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form action="{{LC_Route('login.post')}}#login" method="POST">
              {!! csrf_field()!!}
              <h1>Login Form</h1>
              <div>
                <input type="email" name="email" class="form-control" placeholder="{{ __('Email') }}" value="{{old('email')}}" required="" />
              </div>
              <div>
                <input type="password" name="password"  class="form-control" placeholder="{{ __('Password') }}" required="" />
              </div>
              <div>
                <button type="submit" class="btn btn-default submit">Log in</button>
                <a class="reset_pass" href="#reset">Lost your password?</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                @if (LC_CurrentConfig('allow_register', false))
                <p class="change_link">New to site?
                  <a href="#register" class="to_register"> Create Account </a>
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

        <div id="reset" class="animate form reset_form">
          <section class="login_content">
            <form action="{{LC_Route('password.email')}}#reset" method="POST">
              {!! csrf_field()!!}
              <h1>Forget Form</h1>
              <div>
                <input type="email" name="email" class="form-control" placeholder="{{ __('Email') }}" required="" />
              </div>
              <div>
                <button type="submit" class="btn btn-default submit">Forget Password</button>
                <a class="reset_pass" href="#login">Login?</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                @if (LC_CurrentConfig('allow_register', false))
                <p class="change_link">New to site?
                  <a href="#register" class="to_register"> Create Account </a>
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

        @if (LC_CurrentConfig('allow_register', false))
        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form class="form-horizontal" method="POST" action="{{ LC_Route('register') }}#register">
              {{ csrf_field() }}
              <h1>Create Account</h1>
              <div>
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="{{ __('Name') }}" required autofocus>
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
              </div>
              <div>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required>
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
                <button type="submit" class="btn btn-default submit">Submit</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#login" class="to_register"> Log in </a>
                </p>

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
        @endif

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
