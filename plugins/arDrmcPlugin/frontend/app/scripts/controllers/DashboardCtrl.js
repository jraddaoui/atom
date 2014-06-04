'use strict';

module.exports = function ($scope, $q, StatisticsService, FixityService) {

  var pull = function () {

    var queries = [
      // StatisticsService.getArtworkByMonthSummary(),
      StatisticsService.getDownloadActivity(),
      StatisticsService.getIngestionActivity(),
      StatisticsService.getIngestionSummary(),
      StatisticsService.getRunningTotalByDepartment(),
      StatisticsService.getRunningTotalByCodec(),
      StatisticsService.getRunningTotalByFormat(),
      StatisticsService.getArtworkSizesByYearSummary(),
      StatisticsService.getArtworkCountsAndTotalsByDate()
    ];

    $q.all(queries).then(function (responses) {
      $scope.downloadActivity = responses[0].data.results;
      $scope.ingestionActivity = responses[1].data.results;
      $scope.ingestionSummary = {
        accessKey: 'total',
        formatKey: 'type',
        data: responses[2].data.results
      };
      $scope.countByDepartment = {
        accessKey: 'count',
        formatKey: 'department',
        data: responses[3].data.results
      };
      $scope.storageCodecs = {
        accessKey: 'total',
        formatKey: 'codec',
        data: responses[4].data.results
      };
      $scope.storageFormats = {
        accessKey: 'total',
        formatKey: 'media_type',
        data: responses[5].data.results
      };
      $scope.artworkSizes = [{
        name: 'Average',
        color: 'steelblue',
        xProperty: 'year',
        yProperty: 'average',
        data: responses[6].data.results
      }];
      $scope.yearlyCountsByCollectionDate = [{
        name: 'Year',
        color: 'hotpink',
        xProperty: 'year',
        yProperty: 'count',
        data: responses[7].data.results.collection
      }];
      $scope.monthlyCountsByCreation = [{
        name: 'Month',
        color: 'hotpink',
        xProperty: 'month',
        xLabelFormat: 'yearAndMonth',
        yProperty: 'count',
        data: responses[7].data.results.creation
      }];
      $scope.yearlyTotalsByCollectionDate = [{
        name: 'Year',
        color: 'hotpink',
        xProperty: 'year',
        yProperty: 'total',
        data: responses[7].data.results.collection
      }];
      $scope.monthlyTotalsByCreation = [{
        name: 'Month',
        color: 'hotpink',
        xProperty: 'month',
        xLabelFormat: 'yearAndMonth',
        yProperty: 'total',
        data: responses[7].data.results.creation
      }];
    });

  };

  pull();

  // getStatusFixity accept parameter defining number of responses
  // of 'recent fixity checks' returned by service
  FixityService.getStatusFixity(5).success(function (data) {
    $scope.fixityStats = data;
  }).then(function () {
    if ($scope.fixityStats.lastFails.length > 0) {
      $scope.hasFails = true;
      $scope.showOverview = true;
    }
  }).then(function () {
    angular.forEach($scope.fixityStats.lastChecks, function (i) {
      // Convert boolean to human-friendly string
      if (i.outcome === false) {
        i.statusAlert = 'Failed';
      } else if (i.outcome === true) {
        i.statusAlert = 'Success';
      } else {
        return;
      }
    });
  });

  // Set visibility of fixity details to false by default
  // If failed fixity checks exist, this value will be set
  // to true in then() following service call
  $scope.showOverview = false;
  $scope.toggleOverview = function () {
    $scope.showOverview = !$scope.showOverview;
  };
};