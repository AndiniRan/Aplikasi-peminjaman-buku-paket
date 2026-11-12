
document.addEventListener('DOMContentLoaded', function () {
    const filterSelects = document.querySelectorAll('.admin-filter-select');
    const canvas = document.getElementById('loanChart');
    const totalElement = document.getElementById('totalPeminjaman');
    const emptyElement = document.getElementById('chartEmpty');
    const statisticsCard = document.querySelector('.admin-statistics-card');

    if (!canvas || !statisticsCard) return;

    if (typeof Chart === 'undefined') {
        console.error('Chart.js tidak ditemukan.');
        return;
    }

    const periodeInput = document.getElementById('periodeFilter');
    const kategoriInput = document.getElementById('kategoriFilter');
    const url = statisticsCard.dataset.url;

    let chart = null;

    // Dropdown filter
    filterSelects.forEach(function (select) {
        const button = select.querySelector('.admin-filter-button');
        const optionsBox = select.querySelector('.admin-filter-options');
        const buttonText = button.querySelector('span');
        const targetInput = document.getElementById(select.dataset.target);

        button.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            filterSelects.forEach(function (other) {
                if (other !== select) other.classList.remove('open');
            });

            select.classList.toggle('open');
        });

        optionsBox.querySelectorAll('button').forEach(function (option) {
            option.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                optionsBox.querySelectorAll('button').forEach(function (item) {
                    item.classList.remove('active');
                });

                option.classList.add('active');
                buttonText.textContent = option.textContent.trim();

                if (targetInput) {
                    targetInput.value = option.dataset.value ?? '';
                    targetInput.dispatchEvent(new Event('change'));
                }

                select.classList.remove('open');
            });
        });
    });

    document.addEventListener('click', function (event) {
        filterSelects.forEach(function (select) {
            if (!select.contains(event.target)) {
                select.classList.remove('open');
            }
        });
    });

    // Memuat statistik
    async function loadStatistics() {
        const params = new URLSearchParams({
            periode: periodeInput?.value || 'bulan',
            kategori: kategoriInput?.value || ''
        });

        try {
            const response = await fetch(`${url}?${params.toString()}`, {
                headers: {
                    Accept: 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            const labels = data.labels || [];
            const values = data.values || [];
            const total = Number(data.total || 0);

            if (totalElement) totalElement.textContent = total;

            if (emptyElement) {
                emptyElement.style.display = total === 0 ? 'flex' : 'none';
                const message = emptyElement.querySelector('span');
                if (message) message.textContent = 'Belum ada data peminjaman.';
            }

            if (chart) {
                chart.data.labels = labels;
                chart.data.datasets[0].data = values;
                chart.update();
            } else {
                chart = new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Peminjaman',
                            data: values,
                            backgroundColor: '#65b7f3',
                            borderRadius: 5,
                            maxBarThickness: 38
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    font: { size: 9 },
                                    color: '#222'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    font: { size: 9 },
                                    color: '#333'
                                },
                                grid: { color: '#e3e8ec' },
                                border: { display: false }
                            }
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Gagal memuat statistik:', error);

            if (emptyElement) {
                emptyElement.style.display = 'flex';
                const message = emptyElement.querySelector('span');
                if (message) message.textContent = 'Gagal memuat statistik.';
            }
        }
    }

    periodeInput?.addEventListener('change', loadStatistics);
    kategoriInput?.addEventListener('change', loadStatistics);

    loadStatistics();
});
