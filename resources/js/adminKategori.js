document.addEventListener('DOMContentLoaded', function () {
    /*
    |--------------------------------------------------------------------------
    | DATA KATEGORI
    |--------------------------------------------------------------------------
    */
    const searchInput = document.getElementById('kategoriSearch');
    const entriesSelect = document.getElementById('kategoriEntries');
    const pagination = document.getElementById('kategoriPagination');
    const info = document.getElementById('kategoriInfo');
    const searchEmpty = document.getElementById('kategoriSearchEmpty');
    const rows = Array.from(document.querySelectorAll('.kategori-row'));

    let currentPage = 1;
    let perPage = Number(entriesSelect?.value || 10);

    function getFilteredRows() {
        const keyword = searchInput?.value.trim().toLowerCase() || '';

        return rows.filter(function (row) {
            const name = row.dataset.name?.toLowerCase() || '';
            return name.includes(keyword);
        });
    }

    function renderTable() {
        const filteredRows = getFilteredRows();
        const totalPages = Math.max(1, Math.ceil(filteredRows.length / perPage));

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        rows.forEach(function (row) {
            row.style.display = 'none';
        });

        const start = (currentPage - 1) * perPage;
        const end = start + perPage;
        const visibleRows = filteredRows.slice(start, end);

        visibleRows.forEach(function (row, index) {
            row.style.display = '';

            const number = row.querySelector('.kategori-number');

            if (number) {
                number.textContent = start + index + 1;
            }
        });

        if (searchEmpty) {
            searchEmpty.style.display = rows.length > 0 && filteredRows.length === 0
                ? 'table-row'
                : 'none';
        }

        renderPagination(filteredRows.length, totalPages);
        renderInfo(filteredRows.length, start, end);
    }

    function renderInfo(total, start, end) {
        if (!info) return;

        if (total === 0) {
            info.textContent = 'Tidak ada data kategori';
            return;
        }

        const actualEnd = Math.min(end, total);
        info.textContent = `Menampilkan ${start + 1} - ${actualEnd} dari ${total} kategori`;
    }

    function renderPagination(total, totalPages) {
        if (!pagination) return;

        pagination.innerHTML = '';

        if (total === 0) return;

        const prev = document.createElement('button');
        prev.type = 'button';
        prev.innerHTML = '<i class="bi bi-chevron-left"></i>';
        prev.disabled = currentPage === 1;

        prev.addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        });

        pagination.appendChild(prev);

        for (let page = 1; page <= totalPages; page++) {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = page;

            if (page === currentPage) {
                button.classList.add('active');
            }

            button.addEventListener('click', function () {
                currentPage = page;
                renderTable();
            });

            pagination.appendChild(button);
        }

        const next = document.createElement('button');
        next.type = 'button';
        next.innerHTML = '<i class="bi bi-chevron-right"></i>';
        next.disabled = currentPage === totalPages;

        next.addEventListener('click', function () {
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        });

        pagination.appendChild(next);
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH & ENTRIES
    |--------------------------------------------------------------------------
    */
    searchInput?.addEventListener('input', function () {
        currentPage = 1;
        renderTable();
    });

    entriesSelect?.addEventListener('change', function () {
        perPage = Number(this.value);
        currentPage = 1;
        renderTable();
    });

    if (searchInput || entriesSelect || rows.length) {
        renderTable();
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW ENTRIES DROPDOWN
    |--------------------------------------------------------------------------
    */
    const entriesFilterSelect = document.querySelector('.entries-filter-select');

    if (entriesFilterSelect) {
        const filterButton = entriesFilterSelect.querySelector('.entries-filter-button');
        const filterText = filterButton.querySelector('span');
        const filterOptions = entriesFilterSelect.querySelectorAll('.entries-filter-options button');
        const targetId = entriesFilterSelect.getAttribute('data-target');
        const targetInput = document.getElementById(targetId);

        /*
        |--------------------------------------------------------------------------
        | BUKA / TUTUP DROPDOWN
        |--------------------------------------------------------------------------
        */
        filterButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            entriesFilterSelect.classList.toggle('open');
        });

        /*
        |--------------------------------------------------------------------------
        | PILIH JUMLAH ENTRIES
        |--------------------------------------------------------------------------
        */
        filterOptions.forEach(function (option) {
            option.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                filterOptions.forEach(function (item) {
                    item.classList.remove('active');
                });

                option.classList.add('active');
                filterText.textContent = option.textContent.trim();

                if (targetInput) {
                    targetInput.value = option.getAttribute('data-value') ?? '';

                    targetInput.dispatchEvent(
                        new Event('change', {
                            bubbles: true
                        })
                    );
                }

                entriesFilterSelect.classList.remove('open');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | TUTUP SAAT KLIK DI LUAR
        |--------------------------------------------------------------------------
        */
        document.addEventListener('click', function (event) {
            if (!entriesFilterSelect.contains(event.target)) {
                entriesFilterSelect.classList.remove('open');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE KATEGORI
    |--------------------------------------------------------------------------
    */
    const modal = document.getElementById('kategoriDeleteModal');
    const cancel = document.getElementById('kategoriCancelDelete');
    const confirm = document.getElementById('kategoriConfirmDelete');
    const deleteButtons = document.querySelectorAll('.kategori-delete');

    let activeForm = null;

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const formId = button.dataset.form;
            activeForm = document.getElementById(formId);

            if (!activeForm || !modal) return;

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeDeleteModal() {
        modal?.classList.remove('show');
        document.body.style.overflow = '';
        activeForm = null;

        if (confirm) {
            confirm.disabled = false;
            confirm.textContent = 'Hapus';
        }
    }

    cancel?.addEventListener('click', function () {
        closeDeleteModal();
    });

    confirm?.addEventListener('click', function () {
        if (!activeForm) return;

        confirm.disabled = true;
        confirm.textContent = 'Menghapus...';
        activeForm.submit();
    });

    modal?.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal?.classList.contains('show')) {
            closeDeleteModal();
        }
    });
});