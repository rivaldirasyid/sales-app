$(function() {
  /* ChartJS
   * -------
   * Data and config for chartjs
   */
  'use strict';
  function getPerformanceData() {
    return $.ajax({
      url: '/performance/data', // URL ke endpoint di controller
      type: 'GET',
      dataType: 'json'
    });
  }

  function getDashboardData() {
    return $.ajax({
      url: '/dashboard/data', // URL ke endpoint di controller
      type: 'GET',
      dataType: 'json'
    });
  }

  getDashboardData().done(function(response) {
    if (response.error) {
      console.error(response.error); // Debug jika ada error dari server
      return;
    }

    var customerRevenueAllUsers = response.customerRevenueAllUsers;

    // Data untuk Estimated Revenue (All Users)
    var estimatedRevenueByTypeDataAllUsers = {
      labels: customerRevenueAllUsers.KSG.labels,
      datasets: [{
          label: 'KSG Estimated Revenue',
          data: customerRevenueAllUsers['KSG'].estimated_revenue,
          backgroundColor: '#98BDFF'
      }, {
          label: 'Non-KSG Estimated Revenue',
          data: customerRevenueAllUsers['Non-KSG'].estimated_revenue,
          backgroundColor: '#4B49AC'
      }]
    };

    var estimatedRevenueByTypeOptionsAllUsers = {
      cornerRadius: 5,
      responsive: true,
      maintainAspectRatio: true,
      layout: {
          padding: {
              left: 0,
              right: 0,
              top: 0,
              bottom: 0
          }
      },
      scales: {
          yAxes: [{
              display: true,
              gridLines: {
                  display: true,
                  drawBorder: false,
                  color: "#F2F2F2"
              },
              ticks: {
                  display: true,
                  beginAtZero: true,
                  callback: function(value) {
                      return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); // Format Rupiah
                  },
                  autoSkip: true,
                  maxTicksLimit: 10,
                  fontColor: "#6C7383"
              }
          }],
          xAxes: [{
              stacked: false,
              ticks: {
                  beginAtZero: true,
                  fontColor: "#6C7383"
              },
              gridLines: {
                  color: "rgba(0, 0, 0, 0)",
                  display: false
              },
              barPercentage: 1
          }]
      },
      legend: {
          display: true // Show legend
      },
      elements: {
          point: {
              radius: 0
          }
      },
      tooltips: {
        callbacks: {
            label: function(tooltipItem, data) {
                // Mengubah format tooltip menjadi Rupiah
                var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                var value = tooltipItem.yLabel;
                return datasetLabel + ': Rp ' + new Intl.NumberFormat('id-ID').format(value);
            }
        }
    }
  };

  if ($("#estimatedRevenueByTypeChartAllUsers").length) {
      var estimatedRevenueByTypeChartAllUsersCanvas = $("#estimatedRevenueByTypeChartAllUsers").get(0).getContext("2d");
      var estimatedRevenueByTypeChartAllUsers = new Chart(estimatedRevenueByTypeChartAllUsersCanvas, {
          type: 'bar',
          data: estimatedRevenueByTypeDataAllUsers,
          options: estimatedRevenueByTypeOptionsAllUsers
      });
  }

  // Data untuk Actual Revenue (All Users)
  var actualRevenueByTypeDataAllUsers = {
      labels: customerRevenueAllUsers.KSG.labels,
      datasets: [{
          label: 'KSG Actual Revenue',
          data: customerRevenueAllUsers['KSG'].actual_revenue,
          backgroundColor: '#FFCE56'
      }, {
          label: 'Non-KSG Actual Revenue',
          data: customerRevenueAllUsers['Non-KSG'].actual_revenue,
          backgroundColor: '#FF6384'
      }]
  };

  var actualRevenueByTypeOptionsAllUsers = {
      cornerRadius: 5,
      responsive: true,
      maintainAspectRatio: true,
      layout: {
          padding: {
              left: 0,
              right: 0,
              top: 0,
              bottom: 0
          }
      },
      scales: {
          yAxes: [{
              display: true,
              gridLines: {
                  display: true,
                  drawBorder: false,
                  color: "#F2F2F2"
              },
              ticks: {
                  display: true,
                  beginAtZero: true,
                  callback: function(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); // Format Rupiah
                  },
                  autoSkip: true,
                  maxTicksLimit: 10,
                  fontColor: "#6C7383"
              }
          }],
          xAxes: [{
              stacked: false,
              ticks: {
                  beginAtZero: true,
                  fontColor: "#6C7383"
              },
              gridLines: {
                  color: "rgba(0, 0, 0, 0)",
                  display: false
              },
              barPercentage: 1
          }]
      },
      legend: {
          display: true // Show legend
      },
      elements: {
          point: {
              radius: 0
          }
      },
      tooltips: {
        callbacks: {
            label: function(tooltipItem, data) {
                // Mengubah format tooltip menjadi Rupiah
                var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                var value = tooltipItem.yLabel;
                return datasetLabel + ': Rp ' + new Intl.NumberFormat('id-ID').format(value);
            }
        }
    }
  };

  if ($("#actualRevenueByTypeChartAllUsers").length) {
      var actualRevenueByTypeChartAllUsersCanvas = $("#actualRevenueByTypeChartAllUsers").get(0).getContext("2d");
      var actualRevenueByTypeChartAllUsers = new Chart(actualRevenueByTypeChartAllUsersCanvas, {
          type: 'bar',
          data: actualRevenueByTypeDataAllUsers,
          options: actualRevenueByTypeOptionsAllUsers
      });
  }
    
  }).fail(function() {
    console.error("Failed to fetch performance data");
  });

  // Ambil data performa dari server
  getPerformanceData().done(function(response) {
    if (response.error) {
      console.error(response.error); // Debug jika ada error dari server
      return;
    }

    var salesPerformance = response.salesPerformance;
    var financialSummary = response.financialSummary;
    var customerRevenue = response.customerRevenue;
    var totalProspects = response.totalProspects; // Data total prospects dari controller

    console.log(totalProspects); // Cek apakah totalProspects berisi data yang benar
    // Data untuk estimated revenue (Line Chart)
    var estimatedRevenueData = {
      labels: salesPerformance.labels,
      datasets: [{
        label: 'Total Estimated Revenue',
        data: salesPerformance.estimated_revenue,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 2,
        fill: true
      }]
    };

    var estimatedRevenueOptions = {
      responsive: true,
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            callback: function(value) {
              return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); // Format Rupiah
            }
          }
        }]
      },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            return 'Total Estimated Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(tooltipItem.yLabel);
          }
        }
      }
    };

    if ($("#salesChart").length) {
      var salesChartCanvas = $("#salesChart").get(0).getContext("2d");
      var salesChart = new Chart(salesChartCanvas, {
        type: 'line',
        data: estimatedRevenueData,
        options: estimatedRevenueOptions
      });
    }

    // Data untuk actual revenue (Bar Chart)
    var actualRevenueData = {
      labels: financialSummary.labels,
      datasets: [{
        label: 'Total Actual Revenue',
        data: financialSummary.actual_revenue,
        backgroundColor: 'rgba(153, 102, 255, 0.6)',
        borderColor: 'rgba(153, 102, 255, 1)',
        borderWidth: 1,
        fill: true
      }]
    };

    var actualRevenueOptions = {
      responsive: true,
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            callback: function(value) {
              return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
            }
          }
        }]
      },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            return 'Total Actual Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(tooltipItem.yLabel);
          }
        }
      }
    };

    if ($("#financialChart").length) {
      var financialChartCanvas = $("#financialChart").get(0).getContext("2d");
      var financialChart = new Chart(financialChartCanvas, {
        type: 'bar',
        data: actualRevenueData,
        options: actualRevenueOptions
      });
    }
    // 1. Total Estimated Revenue Bulanan berdasarkan tipe (Bar Chart)
    var estimatedRevenueByTypeData = {
      labels: salesPerformance.labels,
      datasets: [{
          label: 'KSG Estimated Revenue',
          data: customerRevenue['KSG'].estimated_revenue,
          backgroundColor: '#98BDFF'
      }, {
          label: 'Non-KSG Estimated Revenue',
          data: customerRevenue['Non-KSG'].estimated_revenue,
          backgroundColor: '#4B49AC'
      }]
  };

  var estimatedRevenueByTypeOptions = {
      cornerRadius: 5,
      responsive: true,
      maintainAspectRatio: true,
      layout: {
          padding: {
              left: 0,
              right: 0,
              top: 0,
              bottom: 0
          }
      },
      scales: {
          yAxes: [{
              display: true,
              gridLines: {
                  display: true,
                  drawBorder: false,
                  color: "#F2F2F2"
              },
              ticks: {
                  display: true,
                  beginAtZero: true,
                  callback: function(value) {
                      return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); // Format Rupiah
                  },
                  autoSkip: true,
                  maxTicksLimit: 10,
                  fontColor: "#6C7383"
              }
          }],
          xAxes: [{
              stacked: false,
              ticks: {
                  beginAtZero: true,
                  fontColor: "#6C7383"
              },
              gridLines: {
                  color: "rgba(0, 0, 0, 0)",
                  display: false
              },
              barPercentage: 1
          }]
      },
      legend: {
          display: true // Show legend
      },
      elements: {
          point: {
              radius: 0
          }
      },
      tooltips: {
        callbacks: {
            label: function(tooltipItem, data) {
                // Mengubah format tooltip menjadi Rupiah
                var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                var value = tooltipItem.yLabel;
                return datasetLabel + ': Rp ' + new Intl.NumberFormat('id-ID').format(value);
            }
        }
    }
  };

  if ($("#estimatedRevenueByTypeChart").length) {
      var estimatedRevenueByTypeChartCanvas = $("#estimatedRevenueByTypeChart").get(0).getContext("2d");
      var estimatedRevenueByTypeChart = new Chart(estimatedRevenueByTypeChartCanvas, {
          type: 'bar',
          data: estimatedRevenueByTypeData,
          options: estimatedRevenueByTypeOptions
      });
  }

  // 2. Total Actual Revenue Bulanan berdasarkan tipe (Bar Chart)
  var actualRevenueByTypeData = {
      labels: salesPerformance.labels,
      datasets: [{
          label: 'KSG Actual Revenue',
          data: customerRevenue['KSG'].actual_revenue,
          backgroundColor: '#FFCE56'
      }, {
          label: 'Non-KSG Actual Revenue',
          data: customerRevenue['Non-KSG'].actual_revenue,
          backgroundColor: '#FF6384'
      }]
  };

  var actualRevenueByTypeOptions = {
      cornerRadius: 5,
      responsive: true,
      maintainAspectRatio: true,
      layout: {
          padding: {
              left: 0,
              right: 0,
              top: 0,
              bottom: 0
          }
      },
      scales: {
          yAxes: [{
              display: true,
              gridLines: {
                  display: true,
                  drawBorder: false,
                  color: "#F2F2F2"
              },
              ticks: {
                  display: true,
                  beginAtZero: true,
                  callback: function(value) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); // Format Rupiah
                  },
                  autoSkip: true,
                  maxTicksLimit: 10,
                  fontColor: "#6C7383"
              }
          }],
          xAxes: [{
              stacked: false,
              ticks: {
                  beginAtZero: true,
                  fontColor: "#6C7383"
              },
              gridLines: {
                  color: "rgba(0, 0, 0, 0)",
                  display: false
              },
              barPercentage: 1
          }]
      },
      legend: {
          display: true // Show legend
      },
      elements: {
          point: {
              radius: 0
          }
      },
      tooltips: {
        callbacks: {
            label: function(tooltipItem, data) {
                // Mengubah format tooltip menjadi Rupiah
                var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                var value = tooltipItem.yLabel;
                return datasetLabel + ': Rp ' + new Intl.NumberFormat('id-ID').format(value);
            }
        }
    }
  };

  if ($("#actualRevenueByTypeChart").length) {
      var actualRevenueByTypeChartCanvas = $("#actualRevenueByTypeChart").get(0).getContext("2d");
      var actualRevenueByTypeChart = new Chart(actualRevenueByTypeChartCanvas, {
          type: 'bar',
          data: actualRevenueByTypeData,
          options: actualRevenueByTypeOptions
      });
  }

  var totalProspectsData = {
    labels: ['KSG', 'Non-KSG'],
    datasets: [{
        label: 'Total Prospects',
        data: [totalProspects.KSG, totalProspects.NonKSG], // Jumlah total prospects untuk KSG dan Non-KSG
        backgroundColor: ['#4CAF50', '#FF9800'], // Warna berbeda untuk KSG dan Non-KSG
        hoverBackgroundColor: ['#45A049', '#FF7043'] // Warna saat hover
    }]
};

var totalProspectsOptions = {
    responsive: true,
    maintainAspectRatio: true,
    legend: {
        position: 'bottom', // Posisi legenda di bawah chart
        labels: {
            fontColor: '#6C7383',
            usePointStyle: true // Gunakan titik warna sebagai style di legend
        }
    },
    tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var currentLabel = data.labels[tooltipItem.index]; // Ambil label saat ini (KSG atau Non-KSG)
            var currentValue = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]; // Ambil jumlah untuk label ini

            // Tampilkan hanya label dan nilai tanpa total atau persentase
            return currentLabel + ': ' + currentValue + ' prospek';
        }
        }
    }
};

// Render pie chart jika elemen dengan id #totalProspectsChart ada
if ($("#totalProspectsChart").length) {
    var totalProspectsChartCanvas = $("#totalProspectsChart").get(0).getContext("2d");
    var totalProspectsChart = new Chart(totalProspectsChartCanvas, {
        type: 'pie',
        data: totalProspectsData,
        options: totalProspectsOptions
    });
}
    
    
  }).fail(function() {
    console.error("Failed to fetch performance data");
  });

  var multiLineData = {
    labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
    datasets: [{
        label: 'Dataset 1',
        data: [12, 19, 3, 5, 2, 3],
        borderColor: [
          '#587ce4'
        ],
        borderWidth: 2,
        fill: false
      },
      {
        label: 'Dataset 2',
        data: [5, 23, 7, 12, 42, 23],
        borderColor: [
          '#ede190'
        ],
        borderWidth: 2,
        fill: false
      },
      {
        label: 'Dataset 3',
        data: [15, 10, 21, 32, 12, 33],
        borderColor: [
          '#f44252'
        ],
        borderWidth: 2,
        fill: false
      }
    ]
  };
  
  var doughnutPieData = {
    datasets: [{
      data: [30, 40, 30],
      backgroundColor: [
        'rgba(255, 99, 132, 0.5)',
        'rgba(54, 162, 235, 0.5)',
        'rgba(255, 206, 86, 0.5)',
        'rgba(75, 192, 192, 0.5)',
        'rgba(153, 102, 255, 0.5)',
        'rgba(255, 159, 64, 0.5)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
    }],

    // These labels appear in the legend and in the tooltips when hovering different arcs
    labels: [
      'Pink',
      'Blue',
      'Yellow',
    ]
  };
  var doughnutPieOptions = {
    responsive: true,
    animation: {
      animateScale: true,
      animateRotate: true
    }
  };
  var areaData = {
    labels: ["2013", "2014", "2015", "2016", "2017"],
    datasets: [{
      label: '# of Votes',
      data: [12, 19, 3, 5, 2, 3],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1,
      fill: true, // 3: no fill
    }]
  };

  var areaOptions = {
    plugins: {
      filler: {
        propagate: true
      }
    }
  }

  var multiAreaData = {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
        label: 'Facebook',
        data: [8, 11, 13, 15, 12, 13, 16, 15, 13, 19, 11, 14],
        borderColor: ['rgba(255, 99, 132, 0.5)'],
        backgroundColor: ['rgba(255, 99, 132, 0.5)'],
        borderWidth: 1,
        fill: true
      },
      {
        label: 'Twitter',
        data: [7, 17, 12, 16, 14, 18, 16, 12, 15, 11, 13, 9],
        borderColor: ['rgba(54, 162, 235, 0.5)'],
        backgroundColor: ['rgba(54, 162, 235, 0.5)'],
        borderWidth: 1,
        fill: true
      },
      {
        label: 'Linkedin',
        data: [6, 14, 16, 20, 12, 18, 15, 12, 17, 19, 15, 11],
        borderColor: ['rgba(255, 206, 86, 0.5)'],
        backgroundColor: ['rgba(255, 206, 86, 0.5)'],
        borderWidth: 1,
        fill: true
      }
    ]
  };

  var multiAreaOptions = {
    plugins: {
      filler: {
        propagate: true
      }
    },
    elements: {
      point: {
        radius: 0
      }
    },
    scales: {
      xAxes: [{
        gridLines: {
          display: false
        }
      }],
      yAxes: [{
        gridLines: {
          display: false
        }
      }]
    }
  }

  var scatterChartData = {
    datasets: [{
        label: 'First Dataset',
        data: [{
            x: -10,
            y: 0
          },
          {
            x: 0,
            y: 3
          },
          {
            x: -25,
            y: 5
          },
          {
            x: 40,
            y: 5
          }
        ],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)'
        ],
        borderColor: [
          'rgba(255,99,132,1)'
        ],
        borderWidth: 1
      },
      {
        label: 'Second Dataset',
        data: [{
            x: 10,
            y: 5
          },
          {
            x: 20,
            y: -30
          },
          {
            x: -25,
            y: 15
          },
          {
            x: -10,
            y: 5
          }
        ],
        backgroundColor: [
          'rgba(54, 162, 235, 0.2)',
        ],
        borderColor: [
          'rgba(54, 162, 235, 1)',
        ],
        borderWidth: 1
      }
    ]
  }

  var scatterChartOptions = {
    scales: {
      xAxes: [{
        type: 'linear',
        position: 'bottom'
      }]
    }
  }
  
  // Get context with jQuery - using jQuery's .get() method.
  if ($("#barChart").length) {
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: data,
      options: options
    });
  }

  if ($("#linechart-multi").length) {
    var multiLineCanvas = $("#linechart-multi").get(0).getContext("2d");
    var lineChart = new Chart(multiLineCanvas, {
      type: 'line',
      data: multiLineData,
      options: options
    });
  }

  if ($("#areachart-multi").length) {
    var multiAreaCanvas = $("#areachart-multi").get(0).getContext("2d");
    var multiAreaChart = new Chart(multiAreaCanvas, {
      type: 'line',
      data: multiAreaData,
      options: multiAreaOptions
    });
  }

  if ($("#doughnutChart").length) {
    var doughnutChartCanvas = $("#doughnutChart").get(0).getContext("2d");
    var doughnutChart = new Chart(doughnutChartCanvas, {
      type: 'doughnut',
      data: doughnutPieData,
      options: doughnutPieOptions
    });
  }

  if ($("#pieChart").length) {
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: doughnutPieData,
      options: doughnutPieOptions
    });
  }

  if ($("#areaChart").length) {
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    var areaChart = new Chart(areaChartCanvas, {
      type: 'line',
      data: areaData,
      options: areaOptions
    });
  }

  if ($("#scatterChart").length) {
    var scatterChartCanvas = $("#scatterChart").get(0).getContext("2d");
    var scatterChart = new Chart(scatterChartCanvas, {
      type: 'scatter',
      data: scatterChartData,
      options: scatterChartOptions
    });
  }

  if ($("#browserTrafficChart").length) {
    var doughnutChartCanvas = $("#browserTrafficChart").get(0).getContext("2d");
    var doughnutChart = new Chart(doughnutChartCanvas, {
      type: 'doughnut',
      data: browserTrafficData,
      options: doughnutPieOptions
    });
  }
});