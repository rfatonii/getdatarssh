var data3 = {
  labels: ['Jan', 'Feb', 'Mar','Apr','Mei','Jun', 'Jul','Agu', 'Sep','Okt','Nov','Des'],
  datasets: [{
    label: 'Data Pasien',
    data: [15, 25, 35],
    backgroundColor: '#54e69d',
  }]
};

// Opsi konfigurasi untuk Bar Chart 2
var options2 = {
  responsive: true,
  scales: {
    y: {
      beginAtZero: true
    }
  }
};

// Membuat instance Bar Chart 2
var ctx3 = document.getElementById('barChart2').getContext('2d');
var barChart2 = new Chart(ctx3, {
  type: 'bar',
  data: data3,
  options: options2
});
 
 // Data untuk Pie Chart
 var data = {
    labels: ['Poli..', 'Poli..', 'Poli..', 'Poli..', 'Poli..'],
    datasets: [{
      data: [10, 20, 15, 30, 25],
      backgroundColor: ['#FF6344', '#36A2EB', '#FFCE56', '#8E44AD', '#4CAF50']
    }]
  };

  // Opsi konfigurasi untuk Pie Chart
  var options = {
    responsive: true
  };

  // Membuat instance Pie Chart
  var ctx = document.getElementById('pieChart').getContext('2d');
  var pieChart = new Chart(ctx, {
    type: 'pie',
    data: data,
    options: options
  });

  
// Data untuk Pie Chart 2
var data2 = {
    labels: ['Poli..', 'Poli..', 'Poli..'],
    datasets: [{
        data: [20, 30, 50],
        backgroundColor: ['#8E44AD', '#4CAF50', '#FF6384']
    }]
    };

    // Opsi konfigurasi untuk Pie Chart 2
    var options2 = {
    responsive: true
    };

    // Membuat instance Pie Chart 2
    var ctx2 = document.getElementById('pieChart2').getContext('2d');
    var pieChart2 = new Chart(ctx2, {
    type: 'pie',
    data: data2,
    options: options2
    });

    // Data untuk Pie Chart 2
    var dataLine = {
      labels: ['Poli..', 'Poli..', 'Poli..'],
      datasets: [{
          data: [20, 30, 50],
          backgroundColor: ['#8E44AD', '#4CAF50', '#FF6384']
      }]
      };

      // Opsi konfigurasi untuk Pie Chart 2
      var optionsLine = {
      responsive: true
      };

      // Membuat instance Pie Chart 2
      var ctxLine = document.getElementById('lineChart').getContext('2d');
      var lineChart = new Chart(ctxLine, {
      type: 'line',
      data: dataLine,
      options: optionsLine
      });