document.addEventListener('DOMContentLoaded', function () {

    /* TABLE DROPDOWN */
    const dropdowns = document.querySelectorAll('[data-dropdown]');

    function closeDropdowns(except = null) {
        dropdowns.forEach(function (dropdown) {
            if (dropdown !== except) {
                dropdown.classList.remove('open');
            }
        });
    }

    /* CUSTOM FORM SELECT */
    const formSelects = document.querySelectorAll('[data-form-select]');

    function closeFormSelects(except = null) {
        formSelects.forEach(function (select) {
            if (select !== except) {
                select.classList.remove('open');
            }
        });
    }

    dropdowns.forEach(function (dropdown) {
        const button = dropdown.querySelector('[data-dropdown-button]');

        if (!button) return;

        button.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const isOpen = dropdown.classList.contains('open');

            closeDropdowns();
            closeFormSelects();

            if (!isOpen) {
                dropdown.classList.add('open');
            }
        });
    });

    function getDefaultSelectText(targetId) {
        if (targetId === 'transaksi_id') {
            return 'Pilih Transaksi Aktif';
        }

        if (targetId === 'buku_id') {
            return 'Pilih Buku';
        }

        return 'Pilih Member';
    }

    function setFormSelectValue(customSelect, value, triggerChange = true) {
        const targetId = customSelect.dataset.target;
        const nativeSelect = document.getElementById(targetId);
        const text = customSelect.querySelector('[data-form-select-text]');
        const options = customSelect.querySelectorAll('[data-form-select-option]');

        if (!nativeSelect) return;

        nativeSelect.value = value;

        let selectedOption = null;

        options.forEach(function (option) {
            const selected =
                String(option.dataset.value) === String(value);

            option.classList.toggle('active', selected);

            if (selected) {
                selectedOption = option;
            }
        });

        if (text) {
            if (selectedOption) {
                const main =
                    selectedOption.querySelector('.transaksi-option-main');

                const sub =
                    selectedOption.querySelector('.transaksi-option-sub');

                if (main && sub) {
                    text.textContent =
                        `${main.textContent.trim()} - ${sub.textContent.trim()}`;
                } else {
                    text.textContent =
                        selectedOption.textContent.trim();
                }
            } else {
                text.textContent =
                    getDefaultSelectText(targetId);
            }
        }

        if (triggerChange) {
            nativeSelect.dispatchEvent(
                new Event('change', {
                    bubbles: true
                })
            );
        }
    }

    formSelects.forEach(function (customSelect) {
        const button =
            customSelect.querySelector('[data-form-select-button]');

        const options =
            customSelect.querySelectorAll('[data-form-select-option]');

        const targetId =
            customSelect.dataset.target;

        const nativeSelect =
            document.getElementById(targetId);

        if (!button || !nativeSelect) return;

        button.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const isOpen =
                customSelect.classList.contains('open');

            closeDropdowns();
            closeFormSelects();

            if (!isOpen) {
                customSelect.classList.add('open');
            }
        });

        options.forEach(function (option) {
            option.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                setFormSelectValue(
                    customSelect,
                    option.dataset.value,
                    true
                );

                customSelect.classList.remove('open');
            });
        });

        setFormSelectValue(
            customSelect,
            nativeSelect.value,
            false
        );
    });

    document.addEventListener('click', function () {
        closeDropdowns();
        closeFormSelects();
    });

    /* TABLE */
    const table =
        document.getElementById('transaksiTable');

    const searchInput =
        document.getElementById('transaksiSearch');

    const infoText =
        document.getElementById('transaksiInfo');

    const pagination =
        document.getElementById('transaksiPagination');

    const searchEmpty =
        document.getElementById('transaksiSearchEmpty');

    let entriesPerPage = 10;
    let currentPage = 1;
    let selectedStatus = '';

    function getRows() {
        if (!table) return [];

        return Array.from(
            table.querySelectorAll('[data-transaksi-row]')
        );
    }

    function getFilteredRows() {
        const keyword = searchInput
            ? searchInput.value.trim().toLowerCase()
            : '';

        return getRows().filter(function (row) {
            const text =
                row.textContent.toLowerCase();

            const status =
                row.dataset.status || '';

            const matchSearch =
                keyword === '' ||
                text.includes(keyword);

            const matchStatus =
                selectedStatus === '' ||
                status === selectedStatus;

            return matchSearch && matchStatus;
        });
    }

    function createPageButton(
        text,
        page,
        active = false,
        disabled = false
    ) {
        const button =
            document.createElement('button');

        button.type = 'button';
        button.className = 'transaksi-page-btn';
        button.disabled = disabled;

        if (text === 'prev') {
            button.innerHTML =
                '<i class="bi bi-chevron-left"></i>';
        } else if (text === 'next') {
            button.innerHTML =
                '<i class="bi bi-chevron-right"></i>';
        } else {
            button.textContent = text;
        }

        if (active) {
            button.classList.add('active');
        }

        if (!disabled && page !== null) {
            button.addEventListener('click', function () {
                currentPage = page;
                renderTable();
            });
        }

        return button;
    }

    function renderPagination(totalPages) {
        if (!pagination) return;

        pagination.innerHTML = '';

        if (totalPages <= 1) return;

        pagination.appendChild(
            createPageButton(
                'prev',
                currentPage - 1,
                false,
                currentPage === 1
            )
        );

        for (let page = 1; page <= totalPages; page++) {
            pagination.appendChild(
                createPageButton(
                    String(page),
                    page,
                    page === currentPage
                )
            );
        }

        pagination.appendChild(
            createPageButton(
                'next',
                currentPage + 1,
                false,
                currentPage === totalPages
            )
        );
    }

    function renderTable() {
        if (!table) return;

        const allRows = getRows();
        const filteredRows = getFilteredRows();
        const totalItems = filteredRows.length;

        allRows.forEach(function (row) {
            row.style.display = 'none';
        });

        if (searchEmpty) {
            searchEmpty.style.display = 'none';
        }

        if (totalItems === 0) {
            currentPage = 1;

            const hasRealData =
                allRows.length > 0;

            if (searchEmpty && hasRealData) {
                searchEmpty.style.display = 'table-row';
            }

            if (infoText) {
                infoText.textContent = hasRealData
                    ? 'Tidak ada transaksi ditemukan'
                    : 'Belum ada data transaksi';
            }

            if (pagination) {
                pagination.innerHTML = '';
            }

            return;
        }

        const totalPages = Math.max(
            1,
            Math.ceil(totalItems / entriesPerPage)
        );

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        const startIndex =
            (currentPage - 1) * entriesPerPage;

        const endIndex = Math.min(
            startIndex + entriesPerPage,
            totalItems
        );

        const visibleRows =
            filteredRows.slice(
                startIndex,
                endIndex
            );

        visibleRows.forEach(function (row, index) {
            row.style.display = '';

            const number =
                row.querySelector('.transaksi-number');

            if (number) {
                number.textContent =
                    startIndex + index + 1;
            }
        });

        if (infoText) {
            infoText.textContent =
                `Menampilkan ${startIndex + 1} - ${endIndex} dari ${totalItems} transaksi`;
        }

        renderPagination(totalPages);
    }

    searchInput?.addEventListener('input', function () {
        currentPage = 1;
        renderTable();
    });

    /* SHOW ENTRIES */
    document.querySelectorAll('[data-entries]')
        .forEach(function (option) {
            option.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                entriesPerPage =
                    Number(option.dataset.entries) || 10;

                currentPage = 1;

                document.querySelectorAll('[data-entries]')
                    .forEach(function (item) {
                        item.classList.remove('active');
                    });

                option.classList.add('active');

                const text =
                    document.querySelector('[data-entries-text]');

                if (text) {
                    text.textContent =
                        entriesPerPage;
                }

                closeDropdowns();
                renderTable();
            });
        });

    /* STATUS FILTER */
    document.querySelectorAll('[data-status]')
        .forEach(function (option) {
            option.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                selectedStatus =
                    option.dataset.status || '';

                currentPage = 1;

                document.querySelectorAll('[data-status]')
                    .forEach(function (item) {
                        item.classList.remove('active');
                    });

                option.classList.add('active');

                const text =
                    document.querySelector('[data-status-text]');

                if (text) {
                    text.textContent =
                        option.textContent.trim();
                }

                closeDropdowns();
                renderTable();
            });
        });

    if (table) {
        renderTable();
    }

    /* DETAIL MODAL */
    const detailModal =
        document.getElementById('transaksiDetailModal');

    function setDetail(id, value) {
        const element =
            document.getElementById(id);

        if (!element) return;

        element.textContent =
            value && value.trim() !== ''
                ? value
                : '-';
    }

    document.querySelectorAll('[data-detail]')
        .forEach(function (button) {
            button.addEventListener('click', function () {
                if (!detailModal) return;

                setDetail(
                    'detailMember',
                    button.dataset.member
                );

                setDetail(
                    'detailKelas',
                    button.dataset.kelas
                );

                setDetail(
                    'detailBuku',
                    button.dataset.buku
                );

                setDetail(
                    'detailPenerbit',
                    button.dataset.penerbit
                );

                setDetail(
                    'detailKategori',
                    button.dataset.kategori
                );

                setDetail(
                    'detailPengajuan',
                    button.dataset.pengajuan
                );

                setDetail(
                    'detailPinjam',
                    button.dataset.pinjam
                );

                setDetail(
                    'detailJatuhTempo',
                    button.dataset.jatuhTempo
                );

                setDetail(
                    'detailKembali',
                    button.dataset.kembali
                );

                const sampul =
                    document.getElementById('detailSampul');

                if (sampul) {
                    sampul.src =
                        button.dataset.sampul || '';
                }

                const status =
                    document.getElementById('detailStatus');

                if (status) {
                    status.className =
                        'transaksi-badge';

                    if (button.dataset.status === 'dipinjam') {
                        status.classList.add(
                            'badge-dipinjam'
                        );

                        status.textContent =
                            'Dipinjam';
                    } else if (
                        button.dataset.status === 'terlambat'
                    ) {
                        status.classList.add(
                            'badge-terlambat'
                        );

                        status.textContent =
                            'Terlambat';
                    } else {
                        status.classList.add(
                            'badge-selesai'
                        );

                        status.textContent =
                            'Selesai';
                    }
                }

                detailModal.classList.add('open');
                document.body.style.overflow = 'hidden';
            });
        });

    function closeDetailModal() {
        if (!detailModal) return;

        detailModal.classList.remove('open');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('[data-modal-close]')
        .forEach(function (button) {
            button.addEventListener(
                'click',
                closeDetailModal
            );
        });

    document.addEventListener('keydown', function (event) {
        if (
            event.key === 'Escape' &&
            detailModal?.classList.contains('open')
        ) {
            closeDetailModal();
        }
    });

    /* DATE HELPER */
    function parseInputDate(value) {
        if (!value) return null;

        const parts =
            value.split('-');

        if (parts.length !== 3) return null;

        const year =
            Number(parts[0]);

        const month =
            Number(parts[1]);

        const day =
            Number(parts[2]);

        if (!year || !month || !day) {
            return null;
        }

        return new Date(
            year,
            month - 1,
            day
        );
    }

    function formatDateDash(date) {
        if (!date) {
            return '00-00-0000';
        }

        const day =
            String(date.getDate())
                .padStart(2, '0');

        const month =
            String(date.getMonth() + 1)
                .padStart(2, '0');

        const year =
            date.getFullYear();

        return `${day}-${month}-${year}`;
    }

    function formatDateSlash(value) {
        const date =
            parseInputDate(value);

        if (!date) return '';

        const day =
            String(date.getDate())
                .padStart(2, '0');

        const month =
            String(date.getMonth() + 1)
                .padStart(2, '0');

        const year =
            date.getFullYear();

        return `${day}/${month}/${year}`;
    }

    /* CUSTOM DATE */
    const monthNames = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    function formatDateInput(date) {
        const year = date.getFullYear();

        const month =
            String(date.getMonth() + 1)
                .padStart(2, '0');

        const day =
            String(date.getDate())
                .padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    function closeCalendars(except = null) {
        document.querySelectorAll('[data-date-picker]')
            .forEach(function (picker) {
                if (picker !== except) {
                    picker.classList.remove('active');
                }
            });
    }

    document.querySelectorAll('[data-date-picker]')
        .forEach(function (picker) {
            const display =
                picker.querySelector('[data-date-display]');

            const button =
                picker.querySelector('[data-date-button]');

            const input =
                picker.querySelector('[data-date-input]');

            const calendar =
                picker.querySelector('[data-calendar]');

            const title =
                picker.querySelector('[data-calendar-title]');

            const daysContainer =
                picker.querySelector('[data-calendar-days]');

            const prevButton =
                picker.querySelector('[data-calendar-prev]');

            const nextButton =
                picker.querySelector('[data-calendar-next]');

            if (
                !display ||
                !button ||
                !input ||
                !calendar ||
                !title ||
                !daysContainer ||
                !prevButton ||
                !nextButton
            ) {
                return;
            }

            const selectedDate =
                parseInputDate(input.value);

            const today =
                new Date();

            let currentMonth =
                selectedDate
                    ? selectedDate.getMonth()
                    : today.getMonth();

            let currentYear =
                selectedDate
                    ? selectedDate.getFullYear()
                    : today.getFullYear();

            function updateDisplay() {
                display.value =
                    formatDateSlash(input.value);
            }

            function renderCalendar() {
                title.textContent =
                    `${monthNames[currentMonth]} ${currentYear}`;

                daysContainer.innerHTML = '';

                const firstDay =
                    new Date(
                        currentYear,
                        currentMonth,
                        1
                    ).getDay();

                const totalDays =
                    new Date(
                        currentYear,
                        currentMonth + 1,
                        0
                    ).getDate();

                for (
                    let empty = 0;
                    empty < firstDay;
                    empty++
                ) {
                    const emptyCell =
                        document.createElement('div');

                    emptyCell.className =
                        'transaksi-calendar-empty';

                    daysContainer.appendChild(
                        emptyCell
                    );
                }

                for (
                    let day = 1;
                    day <= totalDays;
                    day++
                ) {
                    const date =
                        new Date(
                            currentYear,
                            currentMonth,
                            day
                        );

                    const dayButton =
                        document.createElement('button');

                    dayButton.type = 'button';

                    dayButton.className =
                        'transaksi-calendar-day';

                    dayButton.textContent =
                        day;

                    const value =
                        formatDateInput(date);

                    const todayValue =
                        formatDateInput(today);

                    if (value === todayValue) {
                        dayButton.classList.add(
                            'today'
                        );
                    }

                    if (value === input.value) {
                        dayButton.classList.add(
                            'selected'
                        );
                    }

                    dayButton.addEventListener(
                        'click',
                        function (event) {
                            event.preventDefault();
                            event.stopPropagation();

                            input.value = value;

                            input.dispatchEvent(
                                new Event('change', {
                                    bubbles: true
                                })
                            );

                            updateDisplay();

                            picker.classList.remove(
                                'active'
                            );

                            renderCalendar();
                        }
                    );

                    daysContainer.appendChild(
                        dayButton
                    );
                }
            }

            function openCalendar(event) {
                event.preventDefault();
                event.stopPropagation();

                const isOpen =
                    picker.classList.contains(
                        'active'
                    );

                closeCalendars();

                if (!isOpen) {
                    const currentSelected =
                        parseInputDate(input.value);

                    if (currentSelected) {
                        currentMonth =
                            currentSelected.getMonth();

                        currentYear =
                            currentSelected.getFullYear();
                    }

                    renderCalendar();

                    picker.classList.add(
                        'active'
                    );
                }
            }

            display.addEventListener(
                'click',
                openCalendar
            );

            button.addEventListener(
                'click',
                openCalendar
            );

            prevButton.addEventListener(
                'click',
                function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    currentMonth--;

                    if (currentMonth < 0) {
                        currentMonth = 11;
                        currentYear--;
                    }

                    renderCalendar();
                }
            );

            nextButton.addEventListener(
                'click',
                function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    currentMonth++;

                    if (currentMonth > 11) {
                        currentMonth = 0;
                        currentYear++;
                    }

                    renderCalendar();
                }
            );

            calendar.addEventListener(
                'click',
                function (event) {
                    event.stopPropagation();
                }
            );

            input.addEventListener(
                'change',
                function () {
                    updateDisplay();
                }
            );

            updateDisplay();
            renderCalendar();
        });

    document.addEventListener(
        'click',
        function () {
            closeCalendars();
        }
    );

    /* PEMINJAMAN FORM */
    const bukuSelect =
        document.getElementById('buku_id');

    const stokTersedia =
        document.getElementById('stokTersedia');

    const tanggalPinjam =
        document.getElementById('tanggal_pinjam');

    const jatuhTempoPreview =
        document.getElementById('jatuhTempoPreview');

    function updateStock() {
        if (!bukuSelect || !stokTersedia) {
            return;
        }

        const option =
            bukuSelect.options[
                bukuSelect.selectedIndex
            ];

        stokTersedia.value =
            option?.dataset.stock || '0';
    }

    function updateDueDate() {
        if (
            !tanggalPinjam ||
            !jatuhTempoPreview
        ) {
            return;
        }

        const date =
            parseInputDate(
                tanggalPinjam.value
            );

        if (!date) {
            jatuhTempoPreview.value =
                '00-00-0000';

            return;
        }

        date.setDate(
            date.getDate() + 7
        );

        jatuhTempoPreview.value =
            formatDateDash(date);
    }

    bukuSelect?.addEventListener(
        'change',
        updateStock
    );

    tanggalPinjam?.addEventListener(
        'change',
        updateDueDate
    );

    if (bukuSelect) {
        updateStock();
    }

    if (tanggalPinjam) {
        updateDueDate();
    }

    /* PENGEMBALIAN FORM */
    const transaksiSelect =
        document.getElementById('transaksi_id');

    const returnMember =
        document.getElementById('returnMember');

    const returnBuku =
        document.getElementById('returnBuku');

    const returnTanggalPinjam =
        document.getElementById('returnTanggalPinjam');

    const returnJatuhTempo =
        document.getElementById('returnJatuhTempo');

    const tanggalKembali =
        document.getElementById('tanggal_kembali');

    const statusTepat =
        document.getElementById('statusTepat');

    const statusTerlambat =
        document.getElementById('statusTerlambat');

    function resetReturnStatus() {
        statusTepat?.classList.remove('active');
        statusTerlambat?.classList.remove('active');
    }

    function updateReturnData() {
        if (!transaksiSelect) return;

        const option =
            transaksiSelect.options[
                transaksiSelect.selectedIndex
            ];

        if (!option || !option.value) {
            if (returnMember) {
                returnMember.value = '-';
            }

            if (returnBuku) {
                returnBuku.value = '-';
            }

            if (returnTanggalPinjam) {
                returnTanggalPinjam.value = '-';
            }

            if (returnJatuhTempo) {
                returnJatuhTempo.value = '-';
            }

            resetReturnStatus();
            return;
        }

        if (returnMember) {
            returnMember.value =
                option.dataset.member || '-';
        }

        if (returnBuku) {
            returnBuku.value =
                option.dataset.buku || '-';
        }

        if (returnTanggalPinjam) {
            returnTanggalPinjam.value =
                formatDateSlash(
                    option.dataset.pinjam
                ) || '-';
        }

        if (returnJatuhTempo) {
            returnJatuhTempo.value =
                formatDateSlash(
                    option.dataset.jatuhTempo
                ) || '-';
        }

        updateReturnStatus();
    }

    function updateReturnStatus() {
        resetReturnStatus();

        if (
            !transaksiSelect ||
            !tanggalKembali ||
            !transaksiSelect.value ||
            !tanggalKembali.value
        ) {
            return;
        }

        const option =
            transaksiSelect.options[
                transaksiSelect.selectedIndex
            ];

        const dueDate =
            parseInputDate(
                option?.dataset.jatuhTempo
            );

        const returnDate =
            parseInputDate(
                tanggalKembali.value
            );

        if (!dueDate || !returnDate) {
            return;
        }

        if (returnDate > dueDate) {
            statusTerlambat?.classList.add('active');
        } else {
            statusTepat?.classList.add('active');
        }
    }

    transaksiSelect?.addEventListener(
        'change',
        updateReturnData
    );

    tanggalKembali?.addEventListener(
        'change',
        updateReturnStatus
    );

    if (transaksiSelect) {
        updateReturnData();
    }
});