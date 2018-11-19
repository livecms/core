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
    <div class="login_wrapper">
        <section class="login_content">
            <div class="row text-center">
                <div class="col-md-12">
                    <div class="mb-20">
                        <h4>Please, Verify Your Email Address</h4>
                        <i class="fa fa-envelope fa-3x"></i>
                    </div>
            
                    <div class="fz-16">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                A fresh verification link has been sent to your email address.
                            </div>
                        @endif

                        Before proceeding, please check your email for a verification link.
                        If you did not receive the email, <a class="dark" href="{{ LC_Route('verification.resend') }}">click here to request another</a>.
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ LC_Asset() }}/js/main.js"></script>
    <script>
      @if ($errors->count())
      Swal('', '{!! addslashes($errors->first()) !!}', 'error');
      @endif
      @if ($status = session('status'))
      Swal('', '{!! $status !!}');
      @endif
    </script>
  </body>
</html>

