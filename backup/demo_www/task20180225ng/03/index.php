<body ng-app="myApp">

<p><a href="#/!">Main</a></p>

<a href="#!Insert">Insert</a>
<a href="#!Update">Update</a>
<a href="#!Search">Search</a>

<div style="width:500px;height:500px;" ng-view></div>

 
<script type="text/javascript" src="angular.min.js"></script>
<script src="angular-route.js"></script>

<script>
var app = angular.module("myApp", ["ngRoute"]);
app.config(function($routeProvider) {
    $routeProvider
    .when("/", { templateUrl : "main.htm" })
    .when("/Insert", { templateUrl : "insert.htm" })
    .when("/Update", { templateUrl : "update.htm" })
    .when("/Search", { templateUrl : "search.htm" });
});
</script>
</body>