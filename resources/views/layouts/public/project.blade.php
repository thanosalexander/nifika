@include("{$viewBasePath}.sections.project.defaults")
<!DOCTYPE html>
<html>
    <head>
        @yield('headMeta')
        @yield('headStyles')
        @yield('headScripts')
    </head>
    <body>
        @yield('afterBodyStart')
        @yield('header')
        @yield('contentTop')
        @yield('content')
        @yield('footer')
        @yield('beforeBodyEnd')
    </body>
</html>