<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Image Shop - Administration</title>

    <script type="text/javascript" src="{{ asset('bower_components/angular/angular.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bower_components/angular-ui-router/release/angular-ui-router.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bower_components/satellizer/satellizer.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bower_components/angular-permission/dist/angular-permission.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bower_components/angular-animate/angular-animate.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('views/adm/auth/auth.js') }}"></script>
    <script type="text/javascript" src="{{ asset('views/adm/home/home.js') }}"></script>
    <script type="text/javascript" src="{{ asset('views/adm/size/size.js') }}"></script>
    <script type="text/javascript" src="{{ asset('views/adm/album/album.js') }}"></script>
    <script type="text/javascript" src="{{ asset('views/adm/order/order.js') }}"></script>

    <script type="text/javascript" src="{{ asset('bower_components/angular-bootstrap/ui-bootstrap-tpls.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bower_components/jquery/dist/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.js') }}"></script>

    <link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('bower_components/components-font-awesome/css/font-awesome.css')}}">

    <style type="text/css">
        [ng\:cloak], [ng-cloak], .ng-cloak {
            display: none !important;
        }

        .footer {
            text-shadow: 0px 1px 1px #FFF;
            color: #9F9F9F;
            font-size: 10px;
        }
    </style>
</head>
<body ng-app="imageShopAdm" ng-cloak class="ng-cloak">
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Image Shop Administration</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li ui-sref-active='active'><a ui-sref="home">Home</a></li>
                <li ui-sref-active='active'><a ui-sref="albums">Album</a></li>
                <li ui-sref-active='active'><a ui-sref="sizes">Size</a></li>
                <li ui-sref-active='active'><a ui-sref="orders">Orders</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right" ng-show="currentUser != null">
                <li><a href="">Welcome, @{{currentUser.name}}</a></li>
                <li class="dropdown">
                    <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Profile <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a ng-click="logout()" style="cursor: pointer;">Logout</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right" ng-show="currentUser == null">
                <li ui-sref-active='active'><a ui-sref="auth">Login</a></li>
            </ul>
        </div>
    </div> <!-- .container-fluid -->
</nav>
<div ui-view class="container-fluid"></div>


<footer class="text-center footer">
    <p><script>document.write(new Date().getFullYear());</script> &copy; <a href="http://grisu118.ch" style="color: #9F9F9F; text-decoration: none;">http://grisu118.ch</a></p>
</footer>
</body>
</html>
