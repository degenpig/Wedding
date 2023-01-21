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
    <div class="invitation-page summary-page">
        <div class="container">
            <div class="logo-da3wa">
                <img src="{{ asset('assets/images/logo.png') }}" alt="">
                <input type="hidden" id="invitation_code" id="invitation_code" value="{{ $invitationExist->invitation_code }}">
            </div>
            <p class="prayer-text">
                اللهم بارك لهما وبارك عليهما واجمع
                بينهما في خير
            </p>
            <p class="honored-text">
                تتشرف
            </p>
            <div class="organizers-block">
                <div class="organizer-name">
                    عايدة سالم علي
                    السالم الصباح
                </div>
                <div class="divider"><span>و</span></div>
                <div class="organizer-name">
                    نورية أحمد صباح
                    السالم الصباح
                </div>
            </div>
            <p class="invite-text">
                بدعوتكم لحضور حفل زفاف نجليهما
            </p>
            <div class="groom-bride">
                <div>
                    <span class="first-name">
                        الجوهرة
                    </span>
                    <span class="last-Name">
                        صباح الخالد الصباح
                    </span>

                </div>
                <div>
                    <span class="first-name">
                        جابر
                    </span>
                    <span class="last-Name">
                        ثامر الجابر الصباح
                    </span>
                </div>
            </div>
            <div class="text-center">
                <div class="invited-name">
                    السيد /
                    <span>وسيــم صــافي</span>
                </div>
            </div>
            <hr>

            <div class="section-title">
                التاريخ والوقت
            </div>
            <div class="section-info">
                <img src="{{ asset('assets/images/Date Icon.svg') }}" alt="">
                الأحد 14 فبراير 2023
            </div>
            <div class="section-info">
                <img src="{{ asset('assets/images/Time Icon.svg') }}" alt="">
                الساعة 8 مساء
            </div>
            <br>
            <div class="section-title">
                المكان والموقع
            </div>
            <div class="section-info">
                <img src="{{ asset('assets/images/Location Icon.svg') }}" alt="">
                دار عجبة، الجابرية ق8 شارع 63
            </div>
            <div id="map"></div>
            <div class="section-title">
                بطاقة الدخول
            </div>
            <div class="qr-block">
                <div class="qr-img">
                    <img src="{{ asset('assets/images/QR Code Image.png') }}" alt="">
                </div>
                <div class="seat-title">
                    المقعد
                </div>
                <div class="seat-number">
                    المدرج الأول - المقعد A1
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success w-100">حفظ البطاقة لألبوم الصور</button>
            </div>
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
<!-- Start of Google Maps -->
<script>
    function myMap() {
        var mapCanvas = document.getElementById("map");
        var myCenter = new google.maps.LatLng(29.3761015, 47.9993763);
        var mapOptions = { center: myCenter, zoom: 10 };
        var map = new google.maps.Map(mapCanvas, mapOptions);
        var marker = new google.maps.Marker({
            position: myCenter,
            animation: google.maps.Animation.BOUNCE
        });
        marker.setMap(map);
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBquDo_Py_LXm6FtPQEo-LXeLjFzIopLSg&amp;callback=myMap"></script>
<!-- End of Google Maps -->
</body>
</html>
