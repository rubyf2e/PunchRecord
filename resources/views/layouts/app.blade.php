 @include('layouts.body_header')
 <body class="layout-boxed sidebar-mini skin-blue-light">
 <div class="wrapper">
    @include('layouts.header')
    @guest
    @else
    @include('layouts.sidebar')
    @endguest
    <div class="content-wrapper">
       @yield('content')
   </div>
</div>
<!-- ./wrapper -->
@include('layouts.body_footer')