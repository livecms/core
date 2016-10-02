@extends('livecms::themes.adminLTE.template')

<?php
$title = 'Login';
$userImages = LiveCMS\Models\Core\User::whereNotNull('background')->get();
$loginBackground = count($userImages) ? $userImages->random()->background_large_cover : '/background/'.globalParams('background_image', 'keyboard.jpg');
?>


@section('templateBody')
<body class="hold-transition login-page" style="background-image: url({{$loginBackground}})">
<div class="login-box">
  <div class="login-logo">
    <a href="/">{{ globalParams('site_name', 'Live CMS') }}</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg text-black">Sign in to start your session</p>

    {!! Form::open() !!}
        @if($errors->first('email')) <span class="lead label label-danger">{{ $errors->first('email') }}</span>@endif 
        <div class="form-group has-feedback">
            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        @if($errors->first('password')) <span class="lead label label-danger">{{ $errors->first('password') }}</span>@endif 
        <div class="form-group has-feedback">
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                    {!! Form::checkbox('remember') !!}
                    Ingat Saya
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-unlock"></i> Login</button>
            </div>
        <!-- /.col -->
        </div>
    {!! Form::close() !!}

    <!-- /.social-auth-links -->

    <a href="{{ url('/password/reset') }}">I forgot my password</a><br>


  </div>
  <!-- /.login-box-body -->
  <div class="login-footer">
    <p class="login-box-msg">Powered by <a href="{{url('/')}}">{{globalParams('site_name', 'Live CMS')}}</a></p>
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="{{asset('backend/plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{asset('backend/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('backend/plugins/iCheck/icheck.min.js')}}"></script>
<!-- Sweet Alert -->
<script src="{{asset('backend/plugins/sweetalert/sweetalert.min.js')}}"></script>
@if (Session::has('sweet_alert.alert'))
    <script>
        swal({!! Session::get('sweet_alert.alert') !!});
    </script>
@endif
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', '{{env('UA_BACKEND')}}', 'auto');
  ga('send', 'pageview');

</script>

</body>
@stop
