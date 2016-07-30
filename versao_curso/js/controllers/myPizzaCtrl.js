app.controller("myPizzaCtrl", ['$scope', '$http', function ($scope, $http) {
    $scope.name = "My Pizza";
    $scope.editing = false;
    $scope.clients = [];

    var listClients = function () {
        $http.get('post.php').success(function (response) {
            $scope.clients = response;
        });
    };

    var addClient = function (client) {
        $http.post('post.php', client).success(function () {
            listClients();
        });
    };

    var deleteClient = function (client) {
        client.delete = true;
        $http.post('post.php', client).success(function () {
            listClients();
        });
    };

    listClients();

    // Add new client
    $scope.addClient = function (client) {
        //$scope.clients.push(angular.copy(cliente));
        addClient(angular.copy(client));
        $scope.formClient.$setPristine();
        delete $scope.client;
    };
    // Edit client
    $scope.editClient = function (client) {
        $scope.client = client;
        $scope.editing = true;
    };
    // Save client
    $scope.saveClient = function () {
        addClient(angular.copy($scope.client));
        $scope.editing = false;
        $scope.formClient.$setPristine();
        delete $scope.client;
    };
    // Delete client
    $scope.destroyClient = function (client) {
        deleteClient(client);
    };
    // Order table
    $scope.orderTable = function (column) {
        $scope.column = column;
        $scope.order = !$scope.order;
    };
}]);