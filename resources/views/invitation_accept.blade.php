<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>دعوة</title>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon.png') }}"><!-- Favicon -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" type="text/css"><!-- Bootstrap CSS -->
    <link rel="stylesheet" media="all" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<!-- Content -->
<section class="page-wrapper">
    <div class="invitation-accept">
        <div class="container">
            <div class="logo-da3wa">
                <img src="{{ asset('assets/images/logo.png') }}" alt="">
                <input type="hidden" id="invitation_code" id="invitation_code" value="{{ $invitationExist->invitation_code }}">
            </div>
            <div class="ring-img">
                <img src="{{ asset('assets/images/Rings Illustration.svg') }}" alt="">
            </div>
            <h3 class="invitation-accept-title">
                الرجاء الانتظار
            </h3>
            <p class="invitation-accept-text">
                شكرا لقبولك الدعوة
                <br>
                سيتم إصدار بطاقة الدخول خلال ثوان ..
            </p>
        </div>
    </div>
    <div class="pattern-wrapper">
        <div class="shape-1"></div>
        <div class="shape-1p"></div>
        <div class="shape-1o"></div>
        <div class="shape-2"></div>
        <div class="shape-2p"></div>
        <div class="shape-2o"></div>
        <div class="shape-3"></div>
        <div class="shape-3p"></div>
        <div class="shape-3o"></div>
        <div class="shape-4"></div>
        <div class="shape-4p"></div>
        <div class="shape-4o"></div>
    </div>
</section>
<!--  Scripts-->
<script src="{{ asset('assets/js/jquery.min.js') }}" ></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" ></script>
<script>
    $(document).ready(function(){
      var invitation_code = $('#invitation_code').val(); 
      setTimeout(function() {
       window.location.href = '/invitation/detail/'+invitation_code;
      }, 5000);
    });
    
</script>
</body>
</html>
