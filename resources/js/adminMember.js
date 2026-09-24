document.addEventListener('DOMContentLoaded', function () {
    const memberPage = document.querySelector('.member-page');
    const memberType = memberPage?.dataset.memberType || 'member';

    const searchInput = document.getElementById('memberSearch');
    const entriesSelect = document.getElementById('memberEntries');
    const pagination = document.getElementById('memberPagination');
    const info = document.getElementById('memberInfo');
    const searchEmpty = document.getElementById('memberSearchEmpty');
    const rows = Array.from(document.querySelectorAll('.member-row'));

    let currentPage = 1;
    let perPage = Number(entriesSelect?.value || 10);

    function getMemberName() {
        if (memberType === 'guru') return 'guru';
        if (memberType === 'siswa') return 'siswa';
        return 'member';
    }

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

        if (currentPage > totalPages) currentPage = totalPages;

        rows.forEach(function (row) {
            row.style.display = 'none';
        });

        const start = (currentPage - 1) * perPage;
        const end = start + perPage;
        const visibleRows = filteredRows.slice(start, end);

        visibleRows.forEach(function (row, index) {
            row.style.display = '';

            const number = row.querySelector('.member-number');
            if (number) number.textContent = start + index + 1;
        });

        if (searchEmpty) {
            searchEmpty.style.display =
                rows.length > 0 && filteredRows.length === 0
                    ? 'table-row'
                    : 'none';
        }

        renderInfo(filteredRows.length, start, end);
        renderPagination(filteredRows.length, totalPages);
    }

    function renderInfo(total, start, end) {
        if (!info) return;

        const type = getMemberName();

        if (total === 0) {
            info.textContent =
                rows.length === 0
                    ? `Belum ada data ${type}`
                    : `Tidak ada ${type} ditemukan`;
            return;
        }

        const actualEnd = Math.min(end, total);
        info.textContent = `Menampilkan ${start + 1} - ${actualEnd} dari ${total} ${type}`;
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

    const entriesFilter = document.querySelector('.entries-filter-select');

    if (entriesFilter) {
        const button = entriesFilter.querySelector('.entries-filter-button');
        const text = button?.querySelector('span');
        const options = entriesFilter.querySelectorAll('.entries-filter-options button');

        button?.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            entriesFilter.classList.toggle('open');
        });

        options.forEach(function (option) {
            option.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                options.forEach(function (item) {
                    item.classList.remove('active');
                });

                option.classList.add('active');

                const value = option.dataset.value;

                if (text) text.textContent = value;

                if (entriesSelect) {
                    entriesSelect.value = value;
                    entriesSelect.dispatchEvent(new Event('change'));
                }

                entriesFilter.classList.remove('open');
            });
        });

        document.addEventListener('click', function (event) {
            if (!entriesFilter.contains(event.target)) {
                entriesFilter.classList.remove('open');
            }
        });
    }

    const deleteModal = document.getElementById('memberDeleteModal');
    const cancelDelete = document.getElementById('memberCancelDelete');
    const confirmDelete = document.getElementById('memberConfirmDelete');
    const deleteButtons = document.querySelectorAll('.member-delete');

    let activeDeleteForm = null;

    function openDeleteModal(formId) {
        activeDeleteForm = document.getElementById(formId);

        if (!activeDeleteForm || !deleteModal) return;

        deleteModal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteModal?.classList.remove('show');
        document.body.style.overflow = '';
        activeDeleteForm = null;

        if (confirmDelete) {
            confirmDelete.disabled = false;
            confirmDelete.textContent = 'Hapus';
        }
    }

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            openDeleteModal(button.dataset.form);
        });
    });

    cancelDelete?.addEventListener('click', closeDeleteModal);

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

    const detailModal = document.getElementById('memberDetailModal');
    const detailButtons = document.querySelectorAll('.member-detail');
    const detailClose = document.getElementById('memberDetailClose');
    const detailCloseButton = document.getElementById('memberDetailCloseButton');

    const detailName = document.getElementById('detailName');
    const detailNis = document.getElementById('detailNis');
    const detailEmail = document.getElementById('detailEmail');
    const detailKelas = document.getElementById('detailKelas');
    const detailRole = document.getElementById('detailRole');
    const detailStatus = document.getElementById('detailStatus');
    const detailPhoto = document.getElementById('detailPhoto');
    const detailPhotoIcon = document.getElementById('detailPhotoIcon');
    const detailEditButton = document.getElementById('detailEditButton');
    const detailDeleteButton = document.getElementById('detailDeleteButton');

    let detailDeleteFormId = null;

    function closeDetailModal() {
        detailModal?.classList.remove('show');
        document.body.style.overflow = '';
        detailDeleteFormId = null;
    }

    detailButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            if (!detailModal) return;

            if (detailName) detailName.textContent = button.dataset.name || '-';
            if (detailNis) detailNis.textContent = button.dataset.nis || '-';
            if (detailEmail) detailEmail.textContent = button.dataset.email || '-';
            if (detailKelas) detailKelas.textContent = button.dataset.kelas || '-';
            if (detailRole) detailRole.textContent = button.dataset.role || '-';

            if (detailStatus) {
                const status = button.dataset.status || 'aktif';

                detailStatus.textContent =
                    status.charAt(0).toUpperCase() + status.slice(1);

                detailStatus.classList.remove('aktif', 'nonaktif');
                detailStatus.classList.add(status);
            }

            if (detailEditButton) {
                detailEditButton.href = button.dataset.edit || '#';
            }

            detailDeleteFormId = button.dataset.form || null;

            const foto = button.dataset.foto || '';

            if (detailPhoto) {
                if (foto) {
                    detailPhoto.src = foto;
                    detailPhoto.classList.add('show');

                    if (detailPhotoIcon) {
                        detailPhotoIcon.style.display = 'none';
                    }
                } else {
                    detailPhoto.removeAttribute('src');
                    detailPhoto.classList.remove('show');

                    if (detailPhotoIcon) {
                        detailPhotoIcon.style.display = '';
                    }
                }
            }

            detailModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    });

    detailClose?.addEventListener('click', closeDetailModal);
    detailCloseButton?.addEventListener('click', closeDetailModal);

    detailModal?.addEventListener('click', function (event) {
        if (event.target === detailModal) {
            closeDetailModal();
        }
    });

    detailDeleteButton?.addEventListener('click', function () {
        if (!detailDeleteFormId) return;

        const formId = detailDeleteFormId;

        closeDetailModal();
        openDeleteModal(formId);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;

        if (detailModal?.classList.contains('show')) {
            closeDetailModal();
            return;
        }

        if (deleteModal?.classList.contains('show')) {
            closeDeleteModal();
        }
    });

    const photoInput = document.getElementById('foto');
    const photoPreview = document.getElementById('photoPreview');
    const photoName = document.getElementById('photoName');

    photoInput?.addEventListener('change', function () {
        if (!this.files.length) return;

        const file = this.files[0];

        if (photoName) {
            photoName.textContent = file.name;
        }

        if (!photoPreview) return;

        const oldUrl = photoPreview.dataset.previewUrl;

        if (oldUrl) {
            URL.revokeObjectURL(oldUrl);
        }

        const previewUrl = URL.createObjectURL(file);

        photoPreview.src = previewUrl;
        photoPreview.dataset.previewUrl = previewUrl;
        photoPreview.classList.add('show');
    });

    const passwordButtons = document.querySelectorAll('.password-toggle');

    passwordButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.target);
            const icon = button.querySelector('i');

            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';

                icon?.classList.remove('bi-eye-slash');
                icon?.classList.add('bi-eye');
            } else {
                input.type = 'password';

                icon?.classList.remove('bi-eye');
                icon?.classList.add('bi-eye-slash');
            }
        });
    });

    const statusDropdown = document.querySelector('.form-filter-select');

    if (statusDropdown) {
        const button = statusDropdown.querySelector('.form-filter-button');
        const text = button?.querySelector('span');
        const options = statusDropdown.querySelectorAll('.form-filter-options button');
        const target = document.getElementById(statusDropdown.dataset.target);

        button?.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            statusDropdown.classList.toggle('open');
        });

        options.forEach(function (option) {
            option.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                options.forEach(function (item) {
                    item.classList.remove('active');
                });

                option.classList.add('active');

                if (text) {
                    text.textContent = option.textContent.trim();
                }

                if (target) {
                    target.value = option.dataset.value || '';
                }

                statusDropdown.classList.remove('open');
            });
        });

        document.addEventListener('click', function (event) {
            if (!statusDropdown.contains(event.target)) {
                statusDropdown.classList.remove('open');
            }
        });
    }

    const memberForm = document.querySelector('.member-form');

    memberForm?.addEventListener('submit', function () {
        const saveButton = memberForm.querySelector('.member-form-save');

        if (!saveButton) return;

        saveButton.disabled = true;
        saveButton.textContent = 'Menyimpan...';
    });

    
    const importGuruButton = document.getElementById('importGuruButton');
    const importGuruFile = document.getElementById('importGuruFile');
    const importGuruForm = document.getElementById('importGuruForm');

    if (importGuruButton && importGuruFile && importGuruForm) {
        importGuruButton.addEventListener('click', () => {
            importGuruFile.click();
        });

        importGuruFile.addEventListener('change', () => {
            if (importGuruFile.files.length > 0) {
                importGuruForm.submit();
            }
        });
    }

    const importSiswaButton = document.getElementById('importSiswaButton');
    const importSiswaFile = document.getElementById('importSiswaFile');
    const importSiswaForm = document.getElementById('importSiswaForm');

    if (importSiswaButton && importSiswaFile && importSiswaForm) {
        importSiswaButton.addEventListener('click', () => {
            importSiswaFile.click();
        });

        importSiswaFile.addEventListener('change', () => {
            if (importSiswaFile.files.length > 0) {
                importSiswaForm.submit();
            }
        });
    }
});