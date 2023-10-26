(function (angular) {
    var module = angular.module('agent-search', ['ngSanitize']);

    // modifica as requisições POST para que sejam lidas corretamente pelo Slim
    module.config(['$httpProvider', function ($httpProvider) {
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.common['X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $httpProvider.defaults.transformRequest = function (data) {
            var result = angular.isObject(data) && String(data) !== '[object File]' ? $.param(data) : data;

            return result;
        };
    }]);

    // Seriço que executa no servidor as requisições HTTP
    module.factory('AgentSearchService', ['$http', function ($http) {
        return {
            getData: function(data) {
                return $http.post( MapasCulturais.baseURL + 'pesquisar/searchAgent', data)
                
            }
        };
    }]);

    // Controlador da interface
    module.controller('AgentSearchController', ['$scope', 'AgentSearchService', function ($scope, AgentSearchService) {
        $scope.data = {
            form: [
                {id: 1, title: 'Título 343'},
                {id: 2, title: 'Título 2'}
              ]
        };
        $scope.selectedSearch = '';
        $scope.inputSearch = '';
        $scope.result = []

        $scope.searchForm = function() {
            $scope.result = [];
            //Criando objeto para envio na requisição
            var data = {
                type: $scope.selectedSearch,
                value: $scope.inputSearch
            }
            AgentSearchService.getData(data)
            .success(function (data, status) {                
                for (const [key, value] of Object.entries(data)) {
                    $scope.result.push(value)
                }
             }).
             error(function (data, status) {
                 console.log(data)
                 MapasCulturais.Messages.error('Ocorreu um erro inesperado');
             });
        }

        $scope.searchItem = function(userId) {
          var url = MapasCulturais.baseURL + 'painel/userManagement/?userId=' + userId
          window.open(url, '_blank'); 
        }

        $scope.clearInput = function () {
            $scope.inputSearch = '';
            $scope.result = [];
        }
    }]);
})(angular);
