document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | FILTER DROPDOWN
    |--------------------------------------------------------------------------
    */
    const filterSelects = document.querySelectorAll('.admin-filter-select');
    filterSelects.forEach(function (filterSelect) {
        const filterButton = filterSelect.querySelector('.admin-filter-button');
        const filterOptions = filterSelect.querySelector('.admin-filter-options');
        const filterText = filterButton.querySelector('span');
        const options = filterOptions.querySelectorAll('button');
        const targetId = filterSelect.getAttribute('data-target');
        const targetInput = document.getElementById(targetId);

        /*
        |--------------------------------------------------------------------------
        | BUKA / TUTUP DROPDOWN
        |--------------------------------------------------------------------------
        */
        filterButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            filterSelects.forEach(function (otherSelect) {
                if (otherSelect !== filterSelect) {
                    otherSelect.classList.remove('open');
                }
            });

            filterSelect.classList.toggle('open');
        });

        /*
        |--------------------------------------------------------------------------
        | PILIH FILTER
        |--------------------------------------------------------------------------
        */
        options.forEach(function (option) {
            option.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                options.forEach(function (item) {
                    item.classList.remove('active');
                });

                option.classList.add('active');

                filterText.textContent = option.textContent.trim();

                if (targetInput) {
                    targetInput.value = option.getAttribute('data-value') ?? '';

                    /*
                    |--------------------------------------------------------------------------
                    | TRIGGER CHANGE
                    |--------------------------------------------------------------------------
                    | Berguna nanti saat filter sudah mengambil data database.
                    */
                    targetInput.dispatchEvent(
                        new Event('change', {
                            bubbles: true
                        })
                    );
                }

                filterSelect.classList.remove('open');
            });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | TUTUP FILTER SAAT KLIK DI LUAR
    |--------------------------------------------------------------------------
    */
    document.addEventListener('click', function (event) {
        filterSelects.forEach(function (filterSelect) {
            if (!filterSelect.contains(event.target)) {
                filterSelect.classList.remove('open');
            }
        });
    });

    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */
    const canvas = document.getElementById('loanChart');
    const totalElement = document.getElementById('totalPeminjaman');
    const emptyElement =  document.getElementById('chartEmpty');

    /*
    |--------------------------------------------------------------------------
    | DATA SEMENTARA
    |--------------------------------------------------------------------------
    | Nantinya data ini diganti dari database.
    */
    const labels = [
        'Matematika',
        'Bahasa Indonesia',
        'IPA',
        'IPS',
        'Bahasa Inggris',
        'PKN'
    ];

    const values = [
        0,
        0,
        0,
        0,
        0,
        0
    ];

    /*
    |--------------------------------------------------------------------------
    | TOTAL PEMINJAMAN
    |--------------------------------------------------------------------------
    */
    const total = values.reduce(
        (sum, value) => sum + Number(value),
        0
    );

    if (totalElement) {
        totalElement.textContent =
            total;
    }

    /*
    |--------------------------------------------------------------------------
    | EMPTY STATE
    |--------------------------------------------------------------------------
    */
    if (emptyElement) {
        emptyElement.style.display =
            total === 0
                ? 'flex'
                : 'none';
    }

    /*
    |--------------------------------------------------------------------------
    | CHART
    |--------------------------------------------------------------------------
    */
    if (!canvas) {
        return;
    }

    if (typeof Chart === 'undefined') {
        console.error(
            'Chart.js tidak ditemukan.'
        );
        return;
    }

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: '#65b7f3',
                    borderRadius: 5,
                    maxBarThickness: 38
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {display: false}
            },

            scales: {
                x: {
                    grid: {
                        display: false
                    },

                    ticks: {
                        font: {
                            size: 9
                        },
                        color: '#222'
                    }
                },

                y: {
                    beginAtZero: true,
                    suggestedMax: 20,

                    ticks: {
                        stepSize: 5,
                        font: {
                            size: 9
                        },
                        color: '#333'
                    },

                    grid: {
                        color: '#e3e8ec'
                    },

                    border: {
                        display: false
                    }
                }
            }
        }
    });
});