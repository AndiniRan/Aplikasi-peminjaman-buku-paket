document.addEventListener('DOMContentLoaded', function () {
    /*
    |--------------------------------------------------------------------------
    | DATA BUKU
    |--------------------------------------------------------------------------
    */
    const searchInput = document.getElementById('bukuSearch');
    const entriesSelect = document.getElementById('bukuEntries');
    const pagination = document.getElementById('bukuPagination');
    const info = document.getElementById('bukuInfo');
    const searchEmpty = document.getElementById('bukuSearchEmpty');
    const rows = Array.from(document.querySelectorAll('.buku-row'));

    let currentPage = 1;
    let perPage = Number(entriesSelect?.value || 10);

    function getFilteredRows() {
        const keyword = searchInput?.value.trim().toLowerCase() || '';

        return rows.filter(function (row) {
            const searchData = row.dataset.search?.toLowerCase() || '';
            return searchData.includes(keyword);
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

        visibleRows.forEach(function (row) {
            row.style.display = '';
        });

        if (searchEmpty) {
            searchEmpty.style.display = rows.length > 0 && filteredRows.length === 0
                ? 'table-row'
                : 'none';
        }

        renderTableInfo(filteredRows.length, start, end);
        renderTablePagination(filteredRows.length, totalPages);
    }

    function renderTableInfo(total, start, end) {
        if (!info) return;

        if (total === 0) {
            info.textContent = rows.length === 0
                ? 'Belum ada data buku'
                : 'Tidak ada buku ditemukan';
            return;
        }

        const actualEnd = Math.min(end, total);
        info.textContent = `Menampilkan ${start + 1} - ${actualEnd} dari ${total} buku`;
    }

    function renderTablePagination(total, totalPages) {
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
    | IMPORT EXCEL
    |--------------------------------------------------------------------------
    */
    const importButton = document.getElementById('importButton');
    const importFile = document.getElementById('importFile');
    const importForm = document.getElementById('importForm');

    importButton?.addEventListener('click', function () {
        importFile?.click();
    });

    importFile?.addEventListener('change', function () {
        if (!importFile.files.length || !importForm) return;

        if (importButton) {
            importButton.disabled = true;
            importButton.innerHTML = '<i class="bi bi-hourglass-split"></i><span>Mengimport...</span>';
        }

        importForm.submit();
    });

    /*
    |--------------------------------------------------------------------------
    | DELETE BUKU
    |--------------------------------------------------------------------------
    */
    const deleteModal = document.getElementById('bukuDeleteModal');
    const cancelDelete = document.getElementById('bukuCancelDelete');
    const confirmDelete = document.getElementById('bukuConfirmDelete');
    const deleteButtons = document.querySelectorAll('.buku-delete');

    let activeDeleteForm = null;

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const formId = button.dataset.form;
            activeDeleteForm = document.getElementById(formId);

            if (!activeDeleteForm || !deleteModal) return;

            deleteModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeDeleteModal() {
        deleteModal?.classList.remove('show');
        document.body.style.overflow = '';
        activeDeleteForm = null;

        if (confirmDelete) {
            confirmDelete.disabled = false;
            confirmDelete.textContent = 'Hapus';
        }
    }

    cancelDelete?.addEventListener('click', function () {
        closeDeleteModal();
    });

    confirmDelete?.addEventListener('click', function () {
        if (!activeDeleteForm) return;

        confirmDelete.disabled = true;
        confirmDelete.textContent = 'Menghapus...';
        activeDeleteForm.submit();
    });

    deleteModal?.addEventListener('click', function (event) {
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && deleteModal?.classList.contains('show')) {
            closeDeleteModal();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | FILE SAMPUL BUKU
    |--------------------------------------------------------------------------
    */
    const fileInput = document.querySelector('.buku-file input[type="file"]');
    const fileName = document.querySelector('.buku-file-name');

    fileInput?.addEventListener('change', function () {
        if (!fileName) return;

        fileName.textContent = this.files.length > 0
            ? this.files[0].name
            : 'Tidak ada file yang dipilih';
    });

    /*
    |--------------------------------------------------------------------------
    | FORM KATEGORI DROPDOWN
    |--------------------------------------------------------------------------
    */
    const formFilterSelect = document.querySelector('.form-filter-select');

    if (formFilterSelect) {
        const filterButton = formFilterSelect.querySelector('.form-filter-button');
        const filterText = filterButton.querySelector('span');
        const filterOptions = formFilterSelect.querySelectorAll('.form-filter-options button');
        const targetId = formFilterSelect.getAttribute('data-target');
        const targetInput = document.getElementById(targetId);

        /*
        |--------------------------------------------------------------------------
        | BUKA / TUTUP DROPDOWN
        |--------------------------------------------------------------------------
        */
        filterButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            formFilterSelect.classList.toggle('open');
        });

        /*
        |--------------------------------------------------------------------------
        | PILIH KATEGORI
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

                formFilterSelect.classList.remove('open');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | TUTUP SAAT KLIK DI LUAR
        |--------------------------------------------------------------------------
        */
        document.addEventListener('click', function (event) {
            if (!formFilterSelect.contains(event.target)) {
                formFilterSelect.classList.remove('open');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | KATALOG BUKU
    |--------------------------------------------------------------------------
    */
    const katalogSearch = document.getElementById('katalogSearch');
    const katalogKategori = document.getElementById('katalogKategori');
    const katalogItems = Array.from(document.querySelectorAll('.katalog-item'));
    const katalogEmpty = document.getElementById('katalogEmpty');
    const katalogPagination = document.getElementById('katalogPagination');
    const katalogInfo = document.getElementById('katalogInfo');

    let katalogPage = 1;
    const katalogPerPage = 10;

    function getFilteredKatalog() {
        const keyword = katalogSearch?.value.trim().toLowerCase() || '';
        const category = katalogKategori?.value.trim().toLowerCase() || '';

        return katalogItems.filter(function (item) {
            const searchData = item.dataset.search?.toLowerCase() || '';
            const itemCategory = item.dataset.category?.toLowerCase() || '';

            const matchSearch = searchData.includes(keyword);
            const matchCategory = category === '' || itemCategory === category;

            return matchSearch && matchCategory;
        });
    }

    function renderKatalog() {
        const filteredItems = getFilteredKatalog();
        const totalPages = Math.max(1, Math.ceil(filteredItems.length / katalogPerPage));

        if (katalogPage > totalPages) {
            katalogPage = totalPages;
        }

        katalogItems.forEach(function (item) {
            item.style.display = 'none';
        });

        const start = (katalogPage - 1) * katalogPerPage;
        const end = start + katalogPerPage;
        const visibleItems = filteredItems.slice(start, end);

        visibleItems.forEach(function (item) {
            item.style.display = '';
        });

        if (katalogEmpty) {
            katalogEmpty.classList.toggle('show', filteredItems.length === 0);
        }

        renderKatalogInfo(filteredItems.length, start, end);
        renderKatalogPagination(filteredItems.length, totalPages);
    }

    function renderKatalogInfo(total, start, end) {
        if (!katalogInfo) return;

        if (total === 0) {
            katalogInfo.textContent = katalogItems.length === 0
                ? 'Belum ada data buku'
                : 'Tidak ada buku ditemukan';
            return;
        }

        const actualEnd = Math.min(end, total);
        katalogInfo.textContent = `Menampilkan ${start + 1} - ${actualEnd} dari ${total} buku`;
    }

    function renderKatalogPagination(total, totalPages) {
        if (!katalogPagination) return;

        katalogPagination.innerHTML = '';

        if (total === 0) return;

        const prev = document.createElement('button');
        prev.type = 'button';
        prev.innerHTML = '<i class="bi bi-chevron-left"></i>';
        prev.disabled = katalogPage === 1;

        prev.addEventListener('click', function () {
            if (katalogPage > 1) {
                katalogPage--;
                renderKatalog();
            }
        });

        katalogPagination.appendChild(prev);

        for (let page = 1; page <= totalPages; page++) {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = page;

            if (page === katalogPage) {
                button.classList.add('active');
            }

            button.addEventListener('click', function () {
                katalogPage = page;
                renderKatalog();
            });

            katalogPagination.appendChild(button);
        }

        const next = document.createElement('button');
        next.type = 'button';
        next.innerHTML = '<i class="bi bi-chevron-right"></i>';
        next.disabled = katalogPage === totalPages;

        next.addEventListener('click', function () {
            if (katalogPage < totalPages) {
                katalogPage++;
                renderKatalog();
            }
        });

        katalogPagination.appendChild(next);
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM DROPDOWN KATEGORI KATALOG
    |--------------------------------------------------------------------------
    */
    const katalogFilterSelect = document.querySelector('.katalog-filter-select');

    if (katalogFilterSelect) {
        const filterButton = katalogFilterSelect.querySelector('.katalog-filter-button');
        const filterText = filterButton?.querySelector('span');
        const filterOptions = katalogFilterSelect.querySelectorAll('.katalog-filter-options button');
        const targetId = katalogFilterSelect.dataset.target;
        const targetInput = document.getElementById(targetId);

        /*
        |--------------------------------------------------------------------------
        | BUKA / TUTUP DROPDOWN
        |--------------------------------------------------------------------------
        */
        filterButton?.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            katalogFilterSelect.classList.toggle('open');
        });

        /*
        |--------------------------------------------------------------------------
        | PILIH KATEGORI
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

                if (filterText) {
                    filterText.textContent = option.textContent.trim();
                }

                if (targetInput) {
                    targetInput.value = option.dataset.value || '';

                    targetInput.dispatchEvent(
                        new Event('change', {
                            bubbles: true
                        })
                    );
                }

                katalogFilterSelect.classList.remove('open');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | TUTUP SAAT KLIK DI LUAR
        |--------------------------------------------------------------------------
        */
        document.addEventListener('click', function (event) {
            if (!katalogFilterSelect.contains(event.target)) {
                katalogFilterSelect.classList.remove('open');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | EVENT FILTER KATALOG
    |--------------------------------------------------------------------------
    */
    katalogSearch?.addEventListener('input', function () {
        katalogPage = 1;
        renderKatalog();
    });

    katalogKategori?.addEventListener('change', function () {
        katalogPage = 1;
        renderKatalog();
    });

    if (katalogSearch || katalogKategori || katalogItems.length) {
        renderKatalog();
    }
});