<!DOCTYPE html>
<html ng-app="mainApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<meta http-equiv="Refresh" content="1">-->
    <title>SIPONTREN - Sistem Informasi Pondok Pesantren</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!--Plugins-->
    <link rel="stylesheet" href="assets/plugins/ng-table/ng-table.min.css">
    <link rel="stylesheet" href="assets/plugins/animate/animate.css">

    <!-- Angular Material style sheet -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic">
    <link rel="stylesheet" href="assets/angular-material/angular-material.min.css">
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">

    <!--Custom CSS-->
    <link rel="stylesheet" href="assets/dist/app.css">

    <!-- Angular Material requires Angular.js Libraries -->
    <script src="assets/angular/angular.min.js"></script>
    <script src="assets/angular/angular-animate.min.js"></script>
    <script src="assets/angular/angular-aria.min.js"></script>
    <script src="assets/angular/angular-messages.min.js"></script>
    <script src="assets/angular/angular-route.min.js"></script>
    <script src="assets/angular/angular-sanitize.min.js"></script>

    <!-- Angular Material Library -->
    <script type="text/javascript" src="assets/angular-material/angular-material.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap/popper.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="assets/plugins/ng-table/ng-table.min.js"></script>
    <script src="assets/plugins/moment/moment-with-locales.min.js"></script>
    <script src="assets/plugins/moment/locale/id.js"></script>
    <script src="assets/plugins/exportXLS/xlsx.core.min.js"></script>
    <script src="assets/plugins/exportXLS/alasql.min.js"></script>

    <!-- Angular Controller -->
    <script src="assets/dist/locale.js"></script>
    <script src="assets/dist/app.js"></script>
    <script src="assets/dist/login.js"></script>
    <script src="assets/dist/controllers.js"></script>
    <?php // if ($this->session->userdata('ID_USER')) echo '<script src="assets/dist/controllers.js"></script>'; ?>

</head>
<body ng-cloak class="kk-bg-dark-body">
    <md-content flex="100" class="kk-bg-dark">
            <!--<div id="toaster"></div>-->
            <div layout="row" ng-if="showMenu" class="navbar md-whiteframe-3dp" ng-include="'template/show/template-nav_bar.html'" ng-controller="headerController" ng-cloak>
            </div>
            <div class="ajax-bar">
                <md-progress-linear class="kk-progress-linear" md-mode="indeterminate" ng-show="ajaxRunning"></md-progress-linear>
            </div>
            <ng-view></ng-view>
        </md-content>
    </body>
    </html>
