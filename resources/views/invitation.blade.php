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
    <div class="invitation-page ">
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
            <div class="wedding-date">
                وذلك بمشيئة الرحمن
                <span>يوم الجمعة</span>
                الموافق
                <span>١٧ فبراير ٢٠٢٣</span>
                 في تمام
                <span>الساعة الثامنة مساءاً</span>

                <div>
                    في دار عجبة
                </div>
                <div>
                    الزهراء قطعة ٢ شارع ٢١٩ منزل ٥٥٥
                </div>
            </div>
            <div class="text-center">
                <div class="invited-name">
                    السيد /
                    <span>وسيــم صــافي</span>
                </div>
            </div>
            <div class="with-attend">
                بحضوركم يتم الفرح والسرور
            </div>
            <form action="" class="form-style">
                <div class="d-flex align-items-center justify-content-between">
                    <a id="accept-invitation-button" class="btn btn-success w-100 m-2">قبول</a>
                    <a id="decline-invitation-button" class="btn btn-danger w-100 m-2">اعتذار</a>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <a id="pending-invitation-button" class="btn btn-warning w-100 m-2">لم أقرر</a>
                </div>
            </form>
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
    $('#accept-invitation-button').click(function() {

        var invitation_code = $('#invitation_code').val(); 
        var dataString   = {'invitation_code': invitation_code, 'type': 'accept'};

        $.ajax({ 
            type: "POST",
            url: "{{url('api/actionInvitation')}}",
            data: dataString, 
            success: function (res) {
                if (res) {
                    window.location.href = '/invitation/accepted/'+invitation_code;
                }
            }
        });
    });

    $('#decline-invitation-button').click(function() {

        var invitation_code = $('#invitation_code').val(); 
        var dataString   = {'invitation_code': invitation_code, 'type': 'decline'};

        $.ajax({ 
            type: "POST",
            url: "{{url('api/actionInvitation')}}",
            data: dataString, 
            success: function (res) {
                if (res) {
                    window.location.href = '/invitation/decline/'+invitation_code;
                }
            }
        });
    });

    $('#pending-invitation-button').click(function() {

        var invitation_code = $('#invitation_code').val(); 
        var dataString   = {'invitation_code': invitation_code, 'type': 'pending'};

        $.ajax({ 
            type: "POST",
            url: "{{url('api/actionInvitation')}}",
            data: dataString, 
            success: function (res) {
                if (res) {
                    window.location.href = '/invitation/pending/'+invitation_code;
                }
            }
        });
    });
 
</script>
</body>
</html>
