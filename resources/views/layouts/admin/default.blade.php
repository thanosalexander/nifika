<?= view($viewBasePath.'.includes.defaults') ?>
<!DOCTYPE html>
<html lang="<?= e(\App\Logic\Locales\AppLocales::getCurrentLocale())?>">
    <head>
        @yield('headMeta')
        @yield('headStyles')
        @yield('headScripts')
    </head>
    <body>
        @yield('bodyStart')
        <div id="wrapper">
            <!-- Navigation -->
            @yield('headerMenu')
            <div id="page-wrapper">
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col-lg-12">
                            @yield('contentTop')
                        </div>
                    </div>
                    <!-- /.row -->
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /#page-wrapper -->
        </div>
        @yield('bodyEnd')
    </body>
</html>