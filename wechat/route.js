var myApp = angular.module('myApp', ['ngRoute', 'ngResource', 'ngCookies']);

myApp.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
	$routeProvider
	.when("/orders/", {templateUrl: "template/home.html", controller: "ordersCtrl"})
	.when("/message/", {templateUrl: "template/msg.html", controller: "orderMsgCtrl"})
	.when("/dishes/", {templateUrl: "template/dish/list.html", controller: "dishListCtrl"})
	.when("/dishes/:DishId", {templateUrl: "template/dish/detail.html", controller: "dishDetailCtrl"})
	.when("/myorders/", {templateUrl: "template/orders/list.html", controller: "myordersListCtrl"})
	.when("/myorders/sn/:sn", {templateUrl: "template/orders/detail.html", controller: "myordersDetailCtrl"})
	.when("/cart", {templateUrl: "template/cart.html", controller: "cartCtrl"})
	.otherwise({ redirectTo: '/message/' });
}]);