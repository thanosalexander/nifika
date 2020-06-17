@include("{$viewBasePath}.sections.defaults")
<!DOCTYPE html>
<html id="@yield('htmlId')">
    <head>
        @yield('headMeta')
        @yield('headStyles')
        @yield('headScripts')
    </head>
    <body class="@yield('bodyClasses')">
        @yield('afterBodyStart')
        @yield('content')
        @yield('beforeBodyEnd')
    </body>
</html>
