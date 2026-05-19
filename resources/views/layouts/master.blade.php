<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="Keywords"
        content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4" />
    
    @include('layouts.head')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Jost:wght@100;300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&family=Shantell+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            direction: {{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }};
            writing-mode: horizontal-tb;
        }
    </style>
</head>
<body class="main-body app sidebar-mini master" style="direction:{{ App::getLocale()=='ar' ? 'rtl' : 'ltr' }}">
    <div id="global-loader">
        <img src="{{ URL::asset('assets/img/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <div class="main-content app-content">
        @include('layouts.main-sidebar')
        @include('layouts.main-header')
        <div class="container-fluid">
            @yield('page-header')
            @yield('content')
            @include('layouts.sidebar')
            @include('layouts.models')
            @include('layouts.footer')
            @include('layouts.footer-scripts')
        </div>
    </div>        
</body>

</html>
<script src="{{ asset('assets/js/share-modal.js') }}"></script>
<script>

setInterval(myTimer, 1000);

function myTimer() {
    const d = new Date();
    // استخدام padStart لضمان تطابق صيغة الوقت الثنائية دائماً (04 بدلاً من 4)
    const hours = String(d.getHours()).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');
    const seconds = String(d.getSeconds()).padStart(2, '0');
    
    const currenttime = hours + ':' + minutes + ':' + seconds;

    if (currenttime == "16:25:20") {
        window.open("{{ URL::to('our_backup_database') }}", '_blank');
        alert('تنبية : جاري تحميل النسخة الاحتياطية الان \n Attention: Downloading the backup now');
    }
}
</script>