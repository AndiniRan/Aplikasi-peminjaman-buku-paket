document.addEventListener('DOMContentLoaded', function () {

    /* DROPDOWN */
    const dropdowns =
        document.querySelectorAll('[data-dropdown]');

    function closeDropdowns(except = null) {
        dropdowns.forEach(function (dropdown) {
            if (dropdown !== except) {
                dropdown.classList.remove('open');
            }
        });
    }

    dropdowns.forEach(function (dropdown) {
        const button =
            dropdown.querySelector('[data-dropdown-button]');

        if (!button) return;

        button.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const isOpen =
                dropdown.classList.contains('open');

            closeDropdowns();

            if (!isOpen) {
                dropdown.classList.add('open');
            }
        });
    });

    document.addEventListener('click', function () {
        closeDropdowns();
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

    const refreshButton =
        document.getElementById('transaksiRefresh');

    let entriesPerPage = 10;
    let currentPage = 1;
    let selectedStatus = '';

    function getRows() {
        if (!table) {
            return [];
        }

        return Array.from(
            table.querySelectorAll('[data-transaksi-row]')
        );
    }

    function getFilteredRows() {
        const keyword =
            searchInput
                ? searchInput.value
                    .trim()
                    .toLowerCase()
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
            button.addEventListener(
                'click',
                function () {
                    currentPage = page;
                    renderTable();
                }
            );
        }

        return button;
    }

    function renderPagination(totalPages) {
        if (!pagination) return;

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

        const maxVisiblePages = 5;

        let startPage =
            Math.max(
                1,
                currentPage - 2
            );

        let endPage =
            Math.min(
                totalPages,
                startPage + maxVisiblePages - 1
            );

        if (
            endPage - startPage + 1 <
            maxVisiblePages
        ) {
            startPage =
                Math.max(
                    1,
                    endPage - maxVisiblePages + 1
                );
        }

        if (startPage > 1) {
            pagination.appendChild(
                createPageButton(
                    '1',
                    1,
                    currentPage === 1
                )
            );

            if (startPage > 2) {
                const dots =
                    document.createElement('button');

                dots.type = 'button';
                dots.className =
                    'transaksi-page-btn';

                dots.textContent = '...';
                dots.disabled = true;

                pagination.appendChild(dots);
            }
        }

        for (
            let page = startPage;
            page <= endPage;
            page++
        ) {
            pagination.appendChild(
                createPageButton(
                    String(page),
                    page,
                    page === currentPage
                )
            );
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const dots =
                    document.createElement('button');

                dots.type = 'button';
                dots.className =
                    'transaksi-page-btn';

                dots.textContent = '...';
                dots.disabled = true;

                pagination.appendChild(dots);
            }

            pagination.appendChild(
                createPageButton(
                    String(totalPages),
                    totalPages,
                    currentPage === totalPages
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

        const allRows =
            getRows();

        const filteredRows =
            getFilteredRows();

        const totalItems =
            filteredRows.length;

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

            if (
                searchEmpty &&
                hasRealData
            ) {
                searchEmpty.style.display =
                    'table-row';
            }

            if (infoText) {
                infoText.textContent =
                    hasRealData
                        ? 'Tidak ada transaksi ditemukan'
                        : 'Belum ada data transaksi';
            }

            if (pagination) {
                pagination.innerHTML = '';
            }

            return;
        }

        const totalPages =
            Math.max(
                1,
                Math.ceil(
                    totalItems /
                    entriesPerPage
                )
            );

        if (currentPage > totalPages) {
            currentPage = totalPages;
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
            function (row, index) {
                row.style.display = '';

                const number =
                    row.querySelector(
                        '.transaksi-number'
                    );

                if (number) {
                    number.textContent =
                        startIndex +
                        index +
                        1;
                }
            }
        );

        if (infoText) {
            infoText.textContent =
                `Menampilkan ${startIndex + 1} - ${endIndex} dari ${totalItems} transaksi`;
        }

        renderPagination(totalPages);
    }

    /* SEARCH */
    searchInput?.addEventListener(
        'input',
        function () {
            currentPage = 1;
            renderTable();
        }
    );

    /* SHOW ENTRIES */
    document.querySelectorAll('[data-entries]')
        .forEach(function (option) {
            option.addEventListener(
                'click',
                function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    entriesPerPage =
                        Number(
                            option.dataset.entries
                        ) || 10;

                    currentPage = 1;

                    document
                        .querySelectorAll(
                            '[data-entries]'
                        )
                        .forEach(
                            function (item) {
                                item.classList.remove(
                                    'active'
                                );
                            }
                        );

                    option.classList.add(
                        'active'
                    );

                    const text =
                        document.querySelector(
                            '[data-entries-text]'
                        );

                    if (text) {
                        text.textContent =
                            entriesPerPage;
                    }

                    closeDropdowns();
                    renderTable();
                }
            );
        });

    /* STATUS FILTER */
    document.querySelectorAll('[data-status]')
        .forEach(function (option) {
            option.addEventListener(
                'click',
                function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    selectedStatus =
                        option.dataset.status || '';

                    currentPage = 1;

                    document
                        .querySelectorAll(
                            '[data-status]'
                        )
                        .forEach(
                            function (item) {
                                item.classList.remove(
                                    'active'
                                );
                            }
                        );

                    option.classList.add(
                        'active'
                    );

                    const text =
                        document.querySelector(
                            '[data-status-text]'
                        );

                    if (text) {
                        text.textContent =
                            option.textContent.trim();
                    }

                    closeDropdowns();
                    renderTable();
                }
            );
        });

    /* REFRESH */
    refreshButton?.addEventListener(
        'click',
        function () {
            selectedStatus = '';
            entriesPerPage = 10;
            currentPage = 1;

            if (searchInput) {
                searchInput.value = '';
            }

            document
                .querySelectorAll(
                    '[data-status]'
                )
                .forEach(
                    function (item) {
                        item.classList.remove(
                            'active'
                        );
                    }
                );

            const allStatus =
                document.querySelector(
                    '[data-status=""]'
                );

            if (allStatus) {
                allStatus.classList.add(
                    'active'
                );
            }

            const statusText =
                document.querySelector(
                    '[data-status-text]'
                );

            if (statusText) {
                statusText.textContent =
                    'Semua';
            }

            document
                .querySelectorAll(
                    '[data-entries]'
                )
                .forEach(
                    function (item) {
                        item.classList.remove(
                            'active'
                        );
                    }
                );

            const defaultEntries =
                document.querySelector(
                    '[data-entries="10"]'
                );

            if (defaultEntries) {
                defaultEntries.classList.add(
                    'active'
                );
            }

            const entriesText =
                document.querySelector(
                    '[data-entries-text]'
                );

            if (entriesText) {
                entriesText.textContent =
                    '10';
            }

            closeDropdowns();
            renderTable();

            const icon =
                refreshButton.querySelector('i');

            if (icon) {
                icon.classList.remove(
                    'refresh-spin'
                );

                void icon.offsetWidth;

                icon.classList.add(
                    'refresh-spin'
                );

                setTimeout(function () {
                    icon.classList.remove(
                        'refresh-spin'
                    );
                }, 450);
            }
        }
    );

    /* DETAIL MODAL */
    const detailModal =
        document.getElementById(
            'transaksiDetailModal'
        );

    function setDetail(id, value) {
        const element =
            document.getElementById(id);

        if (!element) return;

        element.textContent =
            value &&
            value.trim() !== ''
                ? value
                : '-';
    }

    document
        .querySelectorAll('[data-detail]')
        .forEach(function (button) {
            button.addEventListener(
                'click',
                function () {
                    if (!detailModal) {
                        return;
                    }

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
                        document.getElementById(
                            'detailSampul'
                        );

                    if (sampul) {
                        sampul.src =
                            button.dataset.sampul ||
                            '';
                    }

                    const status =
                        document.getElementById(
                            'detailStatus'
                        );

                    if (status) {
                        status.className =
                            'transaksi-badge';

                        if (
                            button.dataset.status ===
                            'dipinjam'
                        ) {
                            status.classList.add(
                                'badge-dipinjam'
                            );

                            status.textContent =
                                'Dipinjam';

                        } else if (
                            button.dataset.status ===
                            'terlambat'
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

                    detailModal.classList.add(
                        'open'
                    );

                    document.body.style.overflow =
                        'hidden';
                }
            );
        });

    function closeDetailModal() {
        if (!detailModal) {
            return;
        }

        detailModal.classList.remove(
            'open'
        );

        document.body.style.overflow =
            '';
    }

    document
        .querySelectorAll(
            '[data-modal-close]'
        )
        .forEach(function (button) {
            button.addEventListener(
                'click',
                closeDetailModal
            );
        });

    document.addEventListener(
        'keydown',
        function (event) {
            if (
                event.key === 'Escape' &&
                detailModal?.classList.contains(
                    'open'
                )
            ) {
                closeDetailModal();
            }
        }
    );

    if (table) {
        renderTable();
    }
});