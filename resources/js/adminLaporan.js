document.addEventListener('DOMContentLoaded', () => {

    let entriesPerPage = 10;
    let currentPage = 1;

    const tableBody =
        document.querySelector('.laporan-table-body');

    const searchInput =
        document.querySelector('.laporan-search-input');

    const infoText =
        document.querySelector('.laporan-info');

    const pagination =
        document.querySelector('.laporan-pagination');

    /* FILTER DROPDOWN */
    const filterDropdowns =
        document.querySelectorAll('.laporan-filter-select');

    function closeFilterDropdowns(except = null) {
        filterDropdowns.forEach(dropdown => {
            if (dropdown !== except) {
                dropdown.classList.remove('open');
            }
        });
    }

    filterDropdowns.forEach(dropdown => {
        const input =
            dropdown.querySelector('.laporan-filter-input');

        const button =
            dropdown.querySelector('.laporan-filter-button');

        const text =
            dropdown.querySelector('.laporan-filter-text');

        const options =
            dropdown.querySelectorAll(
                '.laporan-filter-options button'
            );

        if (!input || !button || !text) {
            return;
        }

        options.forEach(option => {
            option.classList.toggle(
                'active',
                option.dataset.value === input.value
            );

            option.addEventListener('click', event => {
                event.stopPropagation();

                input.value =
                    option.dataset.value || '';

                text.textContent =
                    option.textContent.trim();

                options.forEach(item => {
                    item.classList.remove('active');
                });

                option.classList.add('active');

                dropdown.classList.remove('open');
            });
        });

        button.addEventListener('click', event => {
            event.stopPropagation();

            const willOpen =
                !dropdown.classList.contains('open');

            closeFilterDropdowns(dropdown);
            closeEntriesDropdowns();
            closeDatePickers();
            closeExportDropdowns();

            dropdown.classList.toggle(
                'open',
                willOpen
            );
        });
    });

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

    const datePickers =
        document.querySelectorAll('[data-date-picker]');

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

        const parts =
            value.split('-').map(Number);

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
        const input =
            picker.querySelector('[data-date-value]');

        const button =
            picker.querySelector('[data-date-button]');

        const text =
            picker.querySelector('[data-date-text]');

        const title =
            picker.querySelector('[data-calendar-title]');

        const grid =
            picker.querySelector('[data-calendar-grid]');

        const prev =
            picker.querySelector('[data-calendar-prev]');

        const next =
            picker.querySelector('[data-calendar-next]');

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

        text.textContent =
            formatDate(selectedDate);

        function renderCalendar() {
            const year =
                viewDate.getFullYear();

            const month =
                viewDate.getMonth();

            title.textContent =
                `${monthNames[month]} ${year}`;

            grid.innerHTML = '';

            const firstDay =
                new Date(year, month, 1).getDay();

            const totalDays =
                new Date(
                    year,
                    month + 1,
                    0
                ).getDate();

            for (
                let index = 0;
                index < firstDay;
                index++
            ) {
                const empty =
                    document.createElement('span');

                empty.className =
                    'laporan-calendar-empty';

                grid.appendChild(empty);
            }

            const today = new Date();

            for (
                let day = 1;
                day <= totalDays;
                day++
            ) {
                const value =
                    dateToValue(
                        year,
                        month,
                        day
                    );

                const dayButton =
                    document.createElement('button');

                dayButton.type = 'button';

                dayButton.className =
                    'laporan-calendar-day';

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

                dayButton.addEventListener(
                    'click',
                    event => {
                        event.stopPropagation();

                        selectedDate = value;
                        input.value = value;

                        text.textContent =
                            formatDate(value);

                        picker.classList.remove('open');

                        renderCalendar();
                    }
                );

                grid.appendChild(dayButton);
            }
        }

        button.addEventListener('click', event => {
            event.stopPropagation();

            const willOpen =
                !picker.classList.contains('open');

            closeDatePickers(picker);
            closeFilterDropdowns();
            closeEntriesDropdowns();
            closeExportDropdowns();

            picker.classList.toggle(
                'open',
                willOpen
            );

            if (willOpen) {
                viewDate =
                    parseDate(selectedDate);

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

    /* EXPORT */
    const exportDropdowns =
        document.querySelectorAll('.laporan-export');

    function closeExportDropdowns(except = null) {
        exportDropdowns.forEach(dropdown => {
            if (dropdown !== except) {
                dropdown.classList.remove('open');
            }
        });
    }

    function updateExportLinks() {
        const keyword =
            searchInput?.value.trim() || '';

        document
            .querySelectorAll('[data-export-link]')
            .forEach(link => {
                const url =
                    new URL(link.href);

                if (keyword) {
                    url.searchParams.set(
                        'search',
                        keyword
                    );
                } else {
                    url.searchParams.delete('search');
                }

                link.href = url.toString();
            });
    }

    exportDropdowns.forEach(dropdown => {
        const button =
            dropdown.querySelector('[data-export-button]');

        if (!button) {
            return;
        }

        button.addEventListener('click', event => {
            event.stopPropagation();

            const willOpen =
                !dropdown.classList.contains('open');

            closeExportDropdowns(dropdown);
            closeFilterDropdowns();
            closeEntriesDropdowns();
            closeDatePickers();

            updateExportLinks();

            dropdown.classList.toggle(
                'open',
                willOpen
            );
        });

        dropdown.addEventListener('click', event => {
            event.stopPropagation();
        });
    });

    /* ENTRIES */
    const entriesDropdowns =
        document.querySelectorAll(
            '.entries-filter-select'
        );

    function closeEntriesDropdowns(except = null) {
        entriesDropdowns.forEach(dropdown => {
            if (dropdown !== except) {
                dropdown.classList.remove('open');
            }
        });
    }

    entriesDropdowns.forEach(dropdown => {
        const button =
            dropdown.querySelector(
                '.entries-filter-button'
            );

        const value =
            dropdown.querySelector(
                '.entries-value'
            );

        const options =
            dropdown.querySelectorAll(
                '.entries-filter-options button'
            );

        if (!button || !value) {
            return;
        }

        button.addEventListener('click', event => {
            event.stopPropagation();

            const willOpen =
                !dropdown.classList.contains('open');

            closeEntriesDropdowns(dropdown);
            closeFilterDropdowns();
            closeDatePickers();
            closeExportDropdowns();

            dropdown.classList.toggle(
                'open',
                willOpen
            );
        });

        options.forEach(option => {
            option.addEventListener('click', () => {
                entriesPerPage =
                    Number(option.dataset.value);

                value.textContent =
                    option.dataset.value;

                options.forEach(item => {
                    item.classList.remove('active');
                });

                option.classList.add('active');

                currentPage = 1;

                dropdown.classList.remove('open');

                renderTable();
            });
        });
    });

    /* TABLE */
    function getRows() {
        if (!tableBody) {
            return [];
        }

        return Array.from(
            tableBody.querySelectorAll(
                '.laporan-row'
            )
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
            'laporan-page-button';

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
            'laporan-page-button';

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
                start =
                    totalPages - 4;

                end =
                    totalPages - 1;
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
                    'Belum ada data';
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
                updateExportLinks();
            }
        );
    }

    /* CLOSE */
    document.addEventListener('click', () => {
        closeFilterDropdowns();
        closeEntriesDropdowns();
        closeDatePickers();
        closeExportDropdowns();
    });

    renderTable();
    updateExportLinks();
});