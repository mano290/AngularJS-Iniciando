app.controller("myPizzaCtrl", ['$scope', '$http', function ($scope, $http) {
    $scope.name = "My Pizza";
    $scope.editing = false;
    $scope.clients = [];

    // API cliente
    $scope.apiClient = {
        url: "apiClient.php",
        // Lista os clientes
        all: function () {
            $http.get(this.url).success(function (response) {
                $scope.clients = response;
            });
        },
        // Adiciona um novo cliente
        add: function (client) {
            $http.post(this.url, client).success(function () {
                $scope.apiClient.all();
            });
        },
        // Deleta um cliente
        delete: function (client) {
            $http.delete(this.url, {params: {
                clientId: client.id
            }}).success(function () {
                $scope.apiClient.all();
            });
        },
        // Edita um cliente
        edit: function (client) {
            $http.put(this.url, client).success(function () {
                $scope.apiClient.all();
            });
        }
    };

    // Init
    $scope.apiClient.all();

    // Add new client
    $scope.addClient = function (client) {
        $scope.apiClient.add(angular.copy(client));
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
        console.log($scope.client);
        $scope.apiClient.edit(angular.copy($scope.client));
        $scope.formClient.$setPristine();
        delete $scope.client;
        $scope.editing = false;
    };
    // Delete client
    $scope.destroyClient = function (client) {
        $scope.apiClient.delete(client);
    };
    // Order table
    $scope.orderTable = function (column) {
        $scope.column = column;
        $scope.order = !$scope.order;
    };
}]);