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
    const statusForm = document.getElementById('pengajuanStatusForm');

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
        const options = statusDropdown.querySelectorAll(
            '.pengajuan-filter-options button'
        );

        statusText.textContent = getStatusLabel(
            statusInput.value
        );

        options.forEach(option => {
            option.classList.toggle(
                'active',
                option.dataset.value === statusInput.value
            );

            option.addEventListener('click', () => {
                statusInput.value =
                    option.dataset.value || '';

                statusText.textContent =
                    option.textContent.trim();

                options.forEach(item => {
                    item.classList.remove('active');
                });

                option.classList.add('active');

                closeStatusDropdown();

                statusForm?.submit();
            });
        });

        statusButton.addEventListener('click', event => {
            event.stopPropagation();

            closeEntriesDropdown();

            statusDropdown.classList.toggle('open');
        });
    }


    /* ENTRIES */
    const entriesDropdown =
        document.getElementById('entriesDropdown');

    const entriesButton =
        document.getElementById('entriesButton');

    const entriesValue =
        document.getElementById('entriesValue');

    function closeEntriesDropdown() {
        entriesDropdown?.classList.remove('open');
    }

    if (
        entriesDropdown &&
        entriesButton &&
        entriesValue
    ) {
        const options = entriesDropdown.querySelectorAll(
            '.entries-filter-options button'
        );

        entriesButton.addEventListener('click', event => {
            event.stopPropagation();

            closeStatusDropdown();

            entriesDropdown.classList.toggle('open');
        });

        options.forEach(option => {
            option.addEventListener('click', () => {
                entriesPerPage = Number(
                    option.dataset.value
                );

                entriesValue.textContent =
                    option.dataset.value;

                options.forEach(item => {
                    item.classList.remove('active');
                });

                option.classList.add('active');

                currentPage = 1;

                closeEntriesDropdown();

                renderTable();
            });
        });
    }


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

        const keyword =
            searchInput?.value
                .trim()
                .toLowerCase() || '';

        if (!keyword) {
            return rows;
        }

        return rows.filter(row => {
            const text =
                (row.dataset.search || '')
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
        const button =
            document.createElement('button');

        button.type = 'button';
        button.className =
            'pengajuan-page-button';

        button.disabled = disabled;

        if (active) {
            button.classList.add('active');
        }

        if (
            content === 'prev' ||
            content === 'next'
        ) {
            const icon =
                document.createElement('i');

            icon.className =
                content === 'prev'
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
        const button =
            document.createElement('button');

        button.type = 'button';
        button.className =
            'pengajuan-page-button';

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
            for (
                let page = 1;
                page <= totalPages;
                page++
            ) {
                pages.push(page);
            }
        } else {
            pages.push(1);

            if (currentPage > 4) {
                pages.push('start-dots');
            }

            let start =
                Math.max(
                    2,
                    currentPage - 1
                );

            let end =
                Math.min(
                    totalPages - 1,
                    currentPage + 1
                );

            if (currentPage <= 4) {
                start = 2;
                end = 5;
            }

            if (
                currentPage >=
                totalPages - 3
            ) {
                start = totalPages - 4;
                end = totalPages - 1;
            }

            for (
                let page = start;
                page <= end;
                page++
            ) {
                pages.push(page);
            }

            if (
                currentPage <
                totalPages - 3
            ) {
                pages.push('end-dots');
            }

            pages.push(totalPages);
        }

        pages.forEach(page => {
            if (
                page === 'start-dots' ||
                page === 'end-dots'
            ) {
                pagination.appendChild(
                    createDots()
                );

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
        const filteredRows =
            getFilteredRows();

        const totalItems =
            filteredRows.length;

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

        const totalPages =
            Math.ceil(
                totalItems /
                entriesPerPage
            );

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        if (currentPage < 1) {
            currentPage = 1;
        }

        const startIndex =
            (currentPage - 1) *
            entriesPerPage;

        const endIndex =
            Math.min(
                startIndex +
                entriesPerPage,
                totalItems
            );

        const visibleRows =
            filteredRows.slice(
                startIndex,
                endIndex
            );

        visibleRows.forEach(
            (row, index) => {
                row.style.display = '';

                const numberCell =
                    row.querySelector(
                        '.row-number'
                    );

                if (numberCell) {
                    numberCell.textContent =
                        startIndex +
                        index +
                        1;
                }
            }
        );

        if (infoText) {
            infoText.textContent =
                `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} entries`;
        }

        renderPagination(totalPages);
    }

    if (searchInput) {
        searchInput.addEventListener(
            'input',
            () => {
                currentPage = 1;

                renderTable();
            }
        );
    }

    renderTable();


    /* MODAL */
    const modal =
        document.getElementById(
            'pengajuanModal'
        );

    const closeButton =
        document.getElementById(
            'closePengajuanModal'
        );

    const closeFooter =
        document.getElementById(
            'closePengajuanModalFooter'
        );

    const overlay =
        modal?.querySelector(
            '.pengajuan-modal-overlay'
        );

    const detailId =
        document.getElementById(
            'detailPengajuanId'
        );

    const detailCover =
        document.getElementById(
            'detailCover'
        );

    const coverPlaceholder =
        document.getElementById(
            'detailCoverPlaceholder'
        );

    const detailJudul =
        document.getElementById(
            'detailJudulBuku'
        );

    const detailPengarang =
        document.getElementById(
            'detailPengarang'
        );

    const detailPenerbit =
        document.getElementById(
            'detailPenerbit'
        );

    const detailKategori =
        document.getElementById(
            'detailKategori'
        );

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
        document.getElementById(
            'detailNama'
        );

    const detailKelas =
        document.getElementById(
            'detailKelas'
        );

    const detailStatus =
        document.getElementById(
            'detailStatus'
        );

    const detailAlasan =
        document.getElementById(
            'detailAlasanDitolak'
        );

    const rowDisiapkan =
        document.getElementById(
            'rowTanggalDisiapkan'
        );

    const rowBatas =
        document.getElementById(
            'rowBatasPengambilan'
        );

    const rowDiambil =
        document.getElementById(
            'rowTanggalDiambil'
        );

    const sectionMenunggu =
        document.getElementById(
            'sectionMenunggu'
        );

    const sectionSiap =
        document.getElementById(
            'sectionSiap'
        );

    const sectionDisetujui =
        document.getElementById(
            'sectionDisetujui'
        );

    const sectionDitolak =
        document.getElementById(
            'sectionDitolak'
        );

    function hideSections() {
        [
            sectionMenunggu,
            sectionSiap,
            sectionDisetujui,
            sectionDitolak
        ].forEach(section => {
            if (section) {
                section.style.display =
                    'none';
            }
        });
    }

    function setRowVisibility(
        element,
        visible
    ) {
        if (!element) {
            return;
        }

        element.style.display =
            visible ? '' : 'none';
    }

    function setStatus(status) {
        if (!detailStatus) {
            return;
        }

        detailStatus.className =
            `pengajuan-status ${status}`;

        const labels = {
            menunggu: 'Menunggu',
            siap: 'Siap',
            disetujui: 'Disetujui',
            ditolak: 'Ditolak'
        };

        detailStatus.textContent =
            labels[status] || status;
    }

    function openModal(row) {
        if (!modal) {
            return;
        }

        const status =
            row.dataset.status || '';

        if (detailId) {
            detailId.textContent =
                `ID Pengajuan #${row.dataset.id}`;
        }

        if (detailJudul) {
            detailJudul.textContent =
                row.dataset.buku || '-';
        }

        if (detailPengarang) {
            detailPengarang.textContent =
                row.dataset.pengarang || '-';
        }

        if (detailPenerbit) {
            detailPenerbit.textContent =
                row.dataset.penerbit || '-';
        }

        if (detailKategori) {
            detailKategori.textContent =
                row.dataset.kategori || '-';
        }

        if (detailNama) {
            detailNama.textContent =
                row.dataset.nama || '-';
        }

        if (detailKelas) {
            detailKelas.textContent =
                row.dataset.kelas || '-';
        }

        if (detailTanggalPengajuan) {
            detailTanggalPengajuan.textContent =
                row.dataset.tanggalPengajuan ||
                '-';
        }

        if (detailTanggalDisiapkan) {
            detailTanggalDisiapkan.textContent =
                row.dataset.tanggalDisiapkan ||
                '-';
        }

        if (detailBatasPengambilan) {
            detailBatasPengambilan.textContent =
                row.dataset.batasPengambilan ||
                '-';
        }

        if (detailTanggalDiambil) {
            detailTanggalDiambil.textContent =
                row.dataset.tanggalDiambil ||
                '-';
        }

        if (
            detailCover &&
            coverPlaceholder
        ) {
            const cover =
                row.dataset.cover || '';

            if (cover) {
                detailCover.src = cover;
                detailCover.style.display =
                    'block';

                coverPlaceholder.style.display =
                    'none';
            } else {
                detailCover.removeAttribute(
                    'src'
                );

                detailCover.style.display =
                    'none';

                coverPlaceholder.style.display =
                    'flex';
            }
        }

        setStatus(status);

        hideSections();

        setRowVisibility(
            rowDisiapkan,
            Boolean(
                row.dataset.tanggalDisiapkan
            )
        );

        setRowVisibility(
            rowBatas,
            Boolean(
                row.dataset.batasPengambilan
            )
        );

        setRowVisibility(
            rowDiambil,
            Boolean(
                row.dataset.tanggalDiambil
            )
        );

        if (
            status === 'menunggu' &&
            sectionMenunggu
        ) {
            sectionMenunggu.style.display =
                'block';
        }

        if (
            status === 'siap' &&
            sectionSiap
        ) {
            sectionSiap.style.display =
                'block';
        }

        if (
            status === 'disetujui' &&
            sectionDisetujui
        ) {
            sectionDisetujui.style.display =
                'block';
        }

        if (
            status === 'ditolak' &&
            sectionDitolak
        ) {
            sectionDitolak.style.display =
                'block';

            if (detailAlasan) {
                detailAlasan.textContent =
                    row.dataset.alasan ||
                    'Tidak ada alasan penolakan.';
            }
        }

        modal.classList.add('show');

        modal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.style.overflow =
            'hidden';
    }

    function closeModal() {
        if (!modal) {
            return;
        }

        modal.classList.remove('show');

        modal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.style.overflow =
            '';
    }

    document
        .querySelectorAll(
            '.btn-detail-pengajuan'
        )
        .forEach(button => {
            button.addEventListener(
                'click',
                () => {
                    const row =
                        button.closest(
                            '.pengajuan-row'
                        );

                    if (row) {
                        openModal(row);
                    }
                }
            );
        });

    closeButton?.addEventListener(
        'click',
        closeModal
    );

    closeFooter?.addEventListener(
        'click',
        closeModal
    );

    overlay?.addEventListener(
        'click',
        closeModal
    );

    document.addEventListener(
        'keydown',
        event => {
            if (
                event.key === 'Escape' &&
                modal?.classList.contains(
                    'show'
                )
            ) {
                closeModal();
            }
        }
    );
});