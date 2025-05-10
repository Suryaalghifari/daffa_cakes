const ctx = document.getElementById("myBarChart");
if (ctx && window.barLabels && window.barData) {
    const warna = [
        "rgba(255, 99, 132, 0.8)",
        "rgba(54, 162, 235, 0.8)",
        "rgba(255, 206, 86, 0.8)",
        "rgba(75, 192, 192, 0.8)",
        "rgba(153, 102, 255, 0.8)",
        "rgba(255, 159, 64, 0.8)",
        "rgba(199, 199, 199, 0.8)"
    ];

    const borderWarna = warna.map(w => w.replace('0.8', '1'));

    const myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: window.barLabels,
            datasets: [{
                label: "Transaksi",
                data: window.barData,
                backgroundColor: warna.slice(0, window.barData.length),
                borderColor: borderWarna.slice(0, window.barData.length),
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 }},
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { maxTicksLimit: 7 }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return value + ' trx'; }
                    },
                    grid: {
                        color: "rgba(234, 236, 244, 1)",
                        zeroLineColor: "rgba(234, 236, 244, 1)",
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Jumlah: ${context.raw}`;
                        }
                    }
                }
            }
        }
    });
} else {
    console.warn("Grafik tidak bisa dibuat: data kosong atau elemen tidak ditemukan.");
}
