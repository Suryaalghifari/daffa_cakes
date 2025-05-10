// Ambil data dari variabel global PHP yang sudah di-echo di halaman utama
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('chartPenjualan').getContext('2d');

    // Ambil data dari element HTML dengan atribut data-*
    const labels = JSON.parse(document.getElementById('chartPenjualan').dataset.labels);
    const data = JSON.parse(document.getElementById('chartPenjualan').dataset.data);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Terjual',
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: context => `Terjual: ${context.raw}`
                    }
                }
            }
        }
    });
});
