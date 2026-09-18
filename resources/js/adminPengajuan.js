document.addEventListener('DOMContentLoaded', () => {

    const tableBody = document.getElementById('pengajuanTableBody');
    const searchInput = document.getElementById('pengajuanSearch');
    const infoText = document.getElementById('pengajuanInfo');
    const pagination = document.getElementById('pengajuanPagination');

    let entriesPerPage = 10;
    let currentPage = 1;

    /* STATUS FILTER */
    const statusDropdown = document.getElementById('statusFilterDropdown');
    const statusButton = document.getElementById('statusFilterButton');
    const statusInput = document.getElementById('statusFilterInput');
    const statusText = document.getElementById('statusFilterText');

    function closeStatusDropdown() {
        statusDropdown?.classList.remove('open');
    }

    function getStatusLabel(value) {
        const labels = {
            menunggu: 'Menunggu',
            siap: 'Siap',
            disetujui: 'Disetujui',
            ditolak: 'Ditolak'
        };

        return labels[value] || 'Semua';
    }

    if (
        statusDropdown &&
        statusButton &&
        statusInput &&
        statusText
    ) {
        const statusOptions = statusDropdown.querySelectorAll(
            '.pengajuan-filter-options button'
        );

        statusText.textContent = getStatusLabel(statusInput.value);

        statusOptions.forEach(option => {
            option.classList.toggle(
                'active',
                option.dataset.value === statusInput.value
            );

            option.addEventListener('click', () => {
                statusInput.value = option.dataset.value || '';
                statusText.textContent = option.textContent.trim();

                statusOptions.forEach(item => {
                    item.classList.remove('active');
                });

                option.classList.add('active');
                closeStatusDropdown();
            });
        });

        statusButton.addEventListener('click', event => {
            event.stopPropagation();

            closeEntriesDropdown();
            closeDatePickers(statusDropdown);

            statusDropdown.classList.toggle('open');
        });
    }

    /* ENTRIES */
    const entriesDropdown = document.getElementById('entriesDropdown');
    const entriesButton = document.getElementById('entriesButton');
    const entriesValue = document.getElementById('entriesValue');

    function closeEntriesDropdown() {
        entriesDropdown?.classList.remove('open');
    }

    if (
        entriesDropdown &&
        entriesButton &&
        entriesValue
    ) {
        const entryOptions = entriesDropdown.querySelectorAll(
            '.entries-filter-options button'
        );

        entriesButton.addEventListener('click', event => {
            event.stopPropagation();

            closeStatusDropdown();
            closeDatePickers(entriesDropdown);

            entriesDropdown.classList.toggle('open');
        });

        entryOptions.forEach(option => {
            option.addEventListener('click', () => {
                entriesPerPage = Number(option.dataset.value);
                entriesValue.textContent = option.dataset.value;

                entryOptions.forEach(item => {
                    item.classList.remove('active');
                });

                option.classList.add('active');

                currentPage = 1;

                closeEntriesDropdown();
                renderTable();
            });
        });
    }

    /* DATE PICKER */
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

    const datePickers = document.querySelectorAll('[data-date-picker]');

    function closeDatePickers(except = null) {
        datePickers.forEach(picker => {
            if (picker !== except) {
                picker.classList.remove('open');
            }
        });
    }

    function formatDate(value) {
        if (!value) {
            return 'Pilih tanggal';
        }

        const parts = value.split('-');

        if (parts.length !== 3) {
            return 'Pilih tanggal';
        }

        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }

    function parseDate(value) {
        if (!value) {
            return new Date();
        }

        const parts = value.split('-').map(Number);

        if (parts.length !== 3) {
            return new Date();
        }

        return new Date(
            parts[0],
            parts[1] - 1,
            parts[2]
        );
    }

    function dateToValue(year, month, day) {
        return [
            year,
            String(month + 1).padStart(2, '0'),
            String(day).padStart(2, '0')
        ].join('-');
    }

    datePickers.forEach(picker => {
        const input = picker.querySelector('[data-date-value]');
        const button = picker.querySelector('[data-date-button]');
        const text = picker.querySelector('[data-date-text]');
        const title = picker.querySelector('[data-calendar-title]');
        const grid = picker.querySelector('[data-calendar-grid]');
        const prev = picker.querySelector('[data-calendar-prev]');
        const next = picker.querySelector('[data-calendar-next]');

        if (
            !input ||
            !button ||
            !text ||
            !title ||
            !grid ||
            !prev ||
            !next
        ) {
            return;
        }

        let selectedDate = input.value || '';
        let viewDate = parseDate(selectedDate);

        text.textContent = formatDate(selectedDate);

        function renderCalendar() {
            const year = viewDate.getFullYear();
            const month = viewDate.getMonth();

            title.textContent = `${monthNames[month]} ${year}`;
            grid.innerHTML = '';

            const firstDay = new Date(year, month, 1).getDay();
            const totalDays = new Date(year, month + 1, 0).getDate();

            for (let index = 0; index < firstDay; index++) {
                const empty = document.createElement('span');

                empty.className = 'pengajuan-calendar-empty';

                grid.appendChild(empty);
            }

            const today = new Date();

            for (let day = 1; day <= totalDays; day++) {
                const value = dateToValue(year, month, day);

                const dayButton = document.createElement('button');

                dayButton.type = 'button';
                dayButton.className = 'pengajuan-calendar-day';
                dayButton.textContent = day;

                const isToday =
                    today.getFullYear() === year &&
                    today.getMonth() === month &&
                    today.getDate() === day;

                if (isToday) {
                    dayButton.classList.add('today');
                }

                if (value === selectedDate) {
                    dayButton.classList.add('selected');
                }

                dayButton.addEventListener('click', event => {
                    event.stopPropagation();

                    selectedDate = value;
                    input.value = value;
                    text.textContent = formatDate(value);

                    picker.classList.remove('open');

                    renderCalendar();
                });

                grid.appendChild(dayButton);
            }
        }

        button.addEventListener('click', event => {
            event.stopPropagation();

            closeStatusDropdown();
            closeEntriesDropdown();

            const willOpen = !picker.classList.contains('open');

            closeDatePickers(picker);

            picker.classList.toggle('open', willOpen);

            if (willOpen) {
                viewDate = parseDate(selectedDate);
                renderCalendar();
            }
        });

        prev.addEventListener('click', event => {
            event.stopPropagation();

            viewDate = new Date(
                viewDate.getFullYear(),
                viewDate.getMonth() - 1,
                1
            );

            renderCalendar();
        });

        next.addEventListener('click', event => {
            event.stopPropagation();

            viewDate = new Date(
                viewDate.getFullYear(),
                viewDate.getMonth() + 1,
                1
            );

            renderCalendar();
        });

        picker.addEventListener('click', event => {
            event.stopPropagation();
        });

        renderCalendar();
    });

    /* CLOSE DROPDOWNS */
    document.addEventListener('click', event => {
        if (
            statusDropdown &&
            !statusDropdown.contains(event.target)
        ) {
            closeStatusDropdown();
        }

        if (
            entriesDropdown &&
            !entriesDropdown.contains(event.target)
        ) {
            closeEntriesDropdown();
        }

        closeDatePickers();
    });

    /* TABLE */
    function getRows() {
        if (!tableBody) {
            return [];
        }

        return Array.from(
            tableBody.querySelectorAll('.pengajuan-row')
        );
    }

    function getFilteredRows() {
        const rows = getRows();
        const keyword = searchInput?.value
            .trim()
            .toLowerCase() || '';

        if (!keyword) {
            return rows;
        }

        return rows.filter(row => {
            const text = (row.dataset.search || '')
                .toLowerCase();

            return text.includes(keyword);
        });
    }

    function createPageButton(
        content,
        page,
        active = false,
        disabled = false
    ) {
        const button = document.createElement('button');

        button.type = 'button';
        button.className = 'pengajuan-page-button';
        button.disabled = disabled;

        if (active) {
            button.classList.add('active');
        }

        if (content === 'prev' || content === 'next') {
            const icon = document.createElement('i');

            icon.className = content === 'prev'
                ? 'bi bi-chevron-left'
                : 'bi bi-chevron-right';

            button.appendChild(icon);
        } else {
            button.textContent = content;
        }

        if (!disabled && page) {
            button.addEventListener('click', () => {
                currentPage = page;
                renderTable();
            });
        }

        return button;
    }

    function createDots() {
        const button = document.createElement('button');

        button.type = 'button';
        button.className = 'pengajuan-page-button';
        button.textContent = '...';
        button.disabled = true;

        return button;
    }

    function renderPagination(totalPages) {
        if (!pagination) {
            return;
        }

        pagination.innerHTML = '';

        if (totalPages <= 1) {
            return;
        }

        pagination.appendChild(
            createPageButton(
                'prev',
                currentPage - 1,
                false,
                currentPage === 1
            )
        );

        const pages = [];

        if (totalPages <= 7) {
            for (let page = 1; page <= totalPages; page++) {
                pages.push(page);
            }
        } else {
            pages.push(1);

            if (currentPage > 4) {
                pages.push('start-dots');
            }

            let start = Math.max(2, currentPage - 1);
            let end = Math.min(totalPages - 1, currentPage + 1);

            if (currentPage <= 4) {
                start = 2;
                end = 5;
            }

            if (currentPage >= totalPages - 3) {
                start = totalPages - 4;
                end = totalPages - 1;
            }

            for (let page = start; page <= end; page++) {
                pages.push(page);
            }

            if (currentPage < totalPages - 3) {
                pages.push('end-dots');
            }

            pages.push(totalPages);
        }

        pages.forEach(page => {
            if (
                page === 'start-dots' ||
                page === 'end-dots'
            ) {
                pagination.appendChild(createDots());
                return;
            }

            pagination.appendChild(
                createPageButton(
                    String(page),
                    page,
                    page === currentPage
                )
            );
        });

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
        const allRows = getRows();
        const filteredRows = getFilteredRows();
        const totalItems = filteredRows.length;

        allRows.forEach(row => {
            row.style.display = 'none';
        });

        if (totalItems === 0) {
            currentPage = 1;

            if (infoText) {
                infoText.textContent =
                    'Belum ada data pengajuan';
            }

            if (pagination) {
                pagination.innerHTML = '';
            }

            return;
        }

        const totalPages = Math.ceil(
            totalItems / entriesPerPage
        );

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        if (currentPage < 1) {
            currentPage = 1;
        }

        const startIndex =
            (currentPage - 1) * entriesPerPage;

        const endIndex = Math.min(
            startIndex + entriesPerPage,
            totalItems
        );

        const visibleRows = filteredRows.slice(
            startIndex,
            endIndex
        );

        visibleRows.forEach((row, index) => {
            row.style.display = '';

            const numberCell =
                row.querySelector('.row-number');

            if (numberCell) {
                numberCell.textContent =
                    startIndex + index + 1;
            }
        });

        if (infoText) {
            infoText.textContent =
                `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} entries`;
        }

        renderPagination(totalPages);
    }

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            currentPage = 1;
            renderTable();
        });
    }

    renderTable();

    /* MODAL */
    const modal = document.getElementById('pengajuanModal');

    const closeModalButton =
        document.getElementById('closePengajuanModal');

    const closeModalFooter =
        document.getElementById(
            'closePengajuanModalFooter'
        );

    const modalOverlay =
        modal?.querySelector('.pengajuan-modal-overlay');

    const detailId =
        document.getElementById('detailPengajuanId');

    const detailCover =
        document.getElementById('detailCover');

    const detailCoverPlaceholder =
        document.getElementById(
            'detailCoverPlaceholder'
        );

    const detailJudul =
        document.getElementById('detailJudulBuku');

    const detailPengarang =
        document.getElementById('detailPengarang');

    const detailPenerbit =
        document.getElementById('detailPenerbit');

    const detailKategori =
        document.getElementById('detailKategori');

    const detailTanggalPengajuan =
        document.getElementById(
            'detailTanggalPengajuan'
        );

    const detailTanggalDisiapkan =
        document.getElementById(
            'detailTanggalDisiapkan'
        );

    const detailBatasPengambilan =
        document.getElementById(
            'detailBatasPengambilan'
        );

    const detailTanggalDiambil =
        document.getElementById(
            'detailTanggalDiambil'
        );

    const detailNama =
        document.getElementById('detailNama');

    const detailKelas =
        document.getElementById('detailKelas');

    const detailStatus =
        document.getElementById('detailStatus');

    const detailAlasanDitolak =
        document.getElementById(
            'detailAlasanDitolak'
        );

    const rowTanggalDisiapkan =
        document.getElementById(
            'rowTanggalDisiapkan'
        );

    const rowBatasPengambilan =
        document.getElementById(
            'rowBatasPengambilan'
        );

    const rowTanggalDiambil =
        document.getElementById(
            'rowTanggalDiambil'
        );

    const sectionMenunggu =
        document.getElementById('sectionMenunggu');

    const sectionSiap =
        document.getElementById('sectionSiap');

    const sectionDisetujui =
        document.getElementById(
            'sectionDisetujui'
        );

    const sectionDitolak =
        document.getElementById('sectionDitolak');

    const formSiap =
        document.getElementById('formSiap');

    const formTolak =
        document.getElementById('formTolak');

    const formDiambil =
        document.getElementById('formDiambil');

    const rejectReason =
        document.getElementById('rejectReason');

    const rejectReasonInput =
        document.getElementById(
            'rejectReasonInput'
        );

    function hideStatusSections() {
        [
            sectionMenunggu,
            sectionSiap,
            sectionDisetujui,
            sectionDitolak
        ].forEach(section => {
            section?.classList.remove('active');
        });

        [
            formSiap,
            formTolak,
            formDiambil
        ].forEach(form => {
            form?.classList.remove('active');
        });
    }

    function hideDateRows() {
        if (rowTanggalDisiapkan) {
            rowTanggalDisiapkan.style.display = 'none';
        }

        if (rowBatasPengambilan) {
            rowBatasPengambilan.style.display = 'none';
        }

        if (rowTanggalDiambil) {
            rowTanggalDiambil.style.display = 'none';
        }
    }

    function setDetailStatus(status) {
        if (!detailStatus) {
            return;
        }

        detailStatus.className = 'pengajuan-status';

        const statuses = {
            menunggu: 'Menunggu',
            siap: 'Siap',
            disetujui: 'Disetujui',
            ditolak: 'Ditolak'
        };

        if (!statuses[status]) {
            detailStatus.textContent = '-';
            return;
        }

        detailStatus.classList.add(status);
        detailStatus.textContent = statuses[status];
    }

    function showCover(url) {
        if (
            !detailCover ||
            !detailCoverPlaceholder
        ) {
            return;
        }

        if (url) {
            detailCover.src = url;
            detailCover.style.display = 'block';
            detailCoverPlaceholder.style.display = 'none';
            return;
        }

        detailCover.removeAttribute('src');
        detailCover.style.display = 'none';
        detailCoverPlaceholder.style.display = 'flex';
    }

    function setText(element, value) {
        if (!element) {
            return;
        }

        const text = typeof value === 'string'
            ? value.trim()
            : '';

        element.textContent = text || '-';
    }

    function openModal(row) {
        if (!modal || !row) {
            return;
        }

        const status = row.dataset.status || '';

        setText(
            detailId,
            `ID Pengajuan #${row.dataset.id}`
        );

        setText(detailJudul, row.dataset.buku);
        setText(detailPengarang, row.dataset.pengarang);
        setText(detailPenerbit, row.dataset.penerbit);
        setText(detailKategori, row.dataset.kategori);

        setText(
            detailTanggalPengajuan,
            row.dataset.tanggalPengajuan
        );

        setText(
            detailTanggalDisiapkan,
            row.dataset.tanggalDisiapkan
        );

        setText(
            detailBatasPengambilan,
            row.dataset.batasPengambilan
        );

        setText(
            detailTanggalDiambil,
            row.dataset.tanggalDiambil
        );

        setText(detailNama, row.dataset.nama);
        setText(detailKelas, row.dataset.kelas);

        setDetailStatus(status);
        showCover(row.dataset.cover);

        hideStatusSections();
        hideDateRows();

        if (rejectReason) {
            rejectReason.value = '';
        }

        if (status === 'menunggu') {
            sectionMenunggu?.classList.add('active');
            formSiap?.classList.add('active');
            formTolak?.classList.add('active');

            if (formSiap) {
                formSiap.action = row.dataset.urlSiap;
            }

            if (formTolak) {
                formTolak.action = row.dataset.urlTolak;
            }
        }

        if (status === 'siap') {
            sectionSiap?.classList.add('active');
            formDiambil?.classList.add('active');

            if (rowTanggalDisiapkan) {
                rowTanggalDisiapkan.style.display = 'grid';
            }

            if (rowBatasPengambilan) {
                rowBatasPengambilan.style.display = 'grid';
            }

            if (formDiambil) {
                formDiambil.action = row.dataset.urlDiambil;
            }
        }

        if (status === 'disetujui') {
            sectionDisetujui?.classList.add('active');

            if (rowTanggalDisiapkan) {
                rowTanggalDisiapkan.style.display = 'grid';
            }

            if (rowTanggalDiambil) {
                rowTanggalDiambil.style.display = 'grid';
            }
        }

        if (status === 'ditolak') {
            sectionDitolak?.classList.add('active');

            setText(
                detailAlasanDitolak,
                row.dataset.alasan
            );

            if (
                row.dataset.tanggalDisiapkan &&
                rowTanggalDisiapkan
            ) {
                rowTanggalDisiapkan.style.display = 'grid';
            }

            if (
                row.dataset.batasPengambilan &&
                rowBatasPengambilan
            ) {
                rowBatasPengambilan.style.display = 'grid';
            }
        }

        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');

        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        if (!modal) {
            return;
        }

        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');

        document.body.style.overflow = '';
    }

    document
        .querySelectorAll('.btn-detail-pengajuan')
        .forEach(button => {
            button.addEventListener('click', () => {
                const row = button.closest('.pengajuan-row');

                openModal(row);
            });
        });

    closeModalButton?.addEventListener(
        'click',
        closeModal
    );

    closeModalFooter?.addEventListener(
        'click',
        closeModal
    );

    modalOverlay?.addEventListener(
        'click',
        closeModal
    );

    document.addEventListener('keydown', event => {
        if (
            event.key === 'Escape' &&
            modal?.classList.contains('show')
        ) {
            closeModal();
        }
    });

    /* REJECT REASON */
    if (
        formTolak &&
        rejectReason &&
        rejectReasonInput
    ) {
        formTolak.addEventListener('submit', () => {
            rejectReasonInput.value =
                rejectReason.value.trim();
        });
    }

});