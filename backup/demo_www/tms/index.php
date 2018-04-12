<?php  

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

    if( isset($request) ){
        $token = $request->token;

        switch($token)
        {
            case 'getEmail':  
                echo $request->email;
                break;
            case 'getPassword':  
                echo $request->pass;
                break;
            default:
                echo "default";
                break;
        }

        die();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>AngularJs Post</title>
        <script src="angular.js"></script>
        <script>
        var app = angular.module('angular_post', []);
        app.controller('form_login', function ($scope, $http) {
            $scope.btnGetEmail = function () {
                $scope.message = "";

                var request = $http({
                    method: "post",
                    url: "index.php",
                    data: { token : 'getEmail', email: $scope.email, pass: $scope.password },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                });
                /* Check whether the HTTP Request is Successfull check_credentials or not. */
                request.success(function (data) {
                    $scope.message = " file : "+data;
                    alert($scope.message);
                });
            }
        });
        </script>
    </head>
    <body>
        <div id="container">
            <h1> This is demo of AngularJS ajax POST call to PHP file</h1>
             <h3> For tutorial visit this link.</h3>
            <div id="login" ng-app='angular_post' ng-controller='form_login'>
                <input type="text" size="40" ng-model="email" placeholder="Type your email"><br>
                <input type="password" size="40" ng-model="password" placeholder="Type your password"><br>
                <button id="btnGetEmail" ng-click="btnGetEmail()">Get Email</button><br>
                <button ng-click="check_credentials()">Login</button><br>
                <span id="message">{{message}}</span>
            </div>
        </div>
    </body>
</html>
