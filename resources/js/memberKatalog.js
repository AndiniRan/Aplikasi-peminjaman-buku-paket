document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | KATALOG BUKU MEMBER
    |--------------------------------------------------------------------------
    */
    const searchInput = document.getElementById('memberKatalogSearch');
    const categoryInput = document.getElementById('memberKatalogKategori');

    const items = Array.from(
        document.querySelectorAll('.member-katalog-item')
    );

    const emptyState = document.getElementById(
        'memberKatalogEmpty'
    );

    const pagination = document.getElementById(
        'memberKatalogPagination'
    );

    let currentPage = 1;

    const perPage = 10;


    /*
    |--------------------------------------------------------------------------
    | FILTER DATA
    |--------------------------------------------------------------------------
    */
    function getFilteredItems() {

        const keyword =
            searchInput?.value
                .trim()
                .toLowerCase() || '';

        const category =
            categoryInput?.value
                .trim()
                .toLowerCase() || '';

        return items.filter(function (item) {

            const searchData =
                item.dataset.search?.toLowerCase() || '';

            const itemCategory =
                item.dataset.category?.toLowerCase() || '';

            const matchSearch =
                searchData.includes(keyword);

            const matchCategory =
                category === '' ||
                itemCategory === category;

            return matchSearch && matchCategory;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER KATALOG
    |--------------------------------------------------------------------------
    */
    function renderKatalog() {

        const filteredItems = getFilteredItems();

        const totalPages = Math.max(
            1,
            Math.ceil(
                filteredItems.length / perPage
            )
        );

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        items.forEach(function (item) {
            item.style.display = 'none';
        });

        const start =
            (currentPage - 1) * perPage;

        const end =
            start + perPage;

        const visibleItems =
            filteredItems.slice(start, end);

        visibleItems.forEach(function (item) {
            item.style.display = '';
        });

        if (emptyState) {
            emptyState.classList.toggle(
                'show',
                filteredItems.length === 0
            );
        }

        renderPagination(
            filteredItems.length,
            totalPages
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */
    function renderPagination(total, totalPages) {

        if (!pagination) {
            return;
        }

        pagination.innerHTML = '';

        if (total === 0) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | PREVIOUS
        |--------------------------------------------------------------------------
        */
        const previousButton =
            document.createElement('button');

        previousButton.type = 'button';

        previousButton.innerHTML =
            '<i class="bi bi-chevron-left"></i>';

        previousButton.disabled =
            currentPage === 1;

        previousButton.addEventListener(
            'click',
            function () {

                if (currentPage > 1) {
                    currentPage--;

                    renderKatalog();
                }
            }
        );

        pagination.appendChild(
            previousButton
        );


        /*
        |--------------------------------------------------------------------------
        | PAGE NUMBER
        |--------------------------------------------------------------------------
        */
        for (
            let page = 1;
            page <= totalPages;
            page++
        ) {

            const button =
                document.createElement('button');

            button.type = 'button';

            button.textContent = page;

            if (page === currentPage) {
                button.classList.add('active');
            }

            button.addEventListener(
                'click',
                function () {

                    currentPage = page;

                    renderKatalog();
                }
            );

            pagination.appendChild(button);
        }


        /*
        |--------------------------------------------------------------------------
        | NEXT
        |--------------------------------------------------------------------------
        */
        const nextButton =
            document.createElement('button');

        nextButton.type = 'button';

        nextButton.innerHTML =
            '<i class="bi bi-chevron-right"></i>';

        nextButton.disabled =
            currentPage === totalPages;

        nextButton.addEventListener(
            'click',
            function () {

                if (currentPage < totalPages) {
                    currentPage++;

                    renderKatalog();
                }
            }
        );

        pagination.appendChild(
            nextButton
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */
    searchInput?.addEventListener(
        'input',
        function () {

            currentPage = 1;

            renderKatalog();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | CATEGORY CHANGE
    |--------------------------------------------------------------------------
    */
    categoryInput?.addEventListener(
        'change',
        function () {

            currentPage = 1;

            renderKatalog();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | CUSTOM DROPDOWN KATEGORI
    |--------------------------------------------------------------------------
    */
    const filterSelect =
        document.querySelector(
            '.member-katalog-filter-select'
        );

    if (filterSelect) {

        const filterButton =
            filterSelect.querySelector(
                '.member-katalog-filter-button'
            );

        const filterText =
            filterButton?.querySelector('span');

        const filterOptions =
            filterSelect.querySelectorAll(
                '.member-katalog-filter-options button'
            );

        const targetId =
            filterSelect.dataset.target;

        const targetInput =
            document.getElementById(targetId);


        /*
        |--------------------------------------------------------------------------
        | BUKA / TUTUP DROPDOWN
        |--------------------------------------------------------------------------
        */
        filterButton?.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                event.stopPropagation();

                filterSelect.classList.toggle(
                    'open'
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | PILIH KATEGORI
        |--------------------------------------------------------------------------
        */
        filterOptions.forEach(
            function (option) {

                option.addEventListener(
                    'click',
                    function (event) {

                        event.preventDefault();

                        event.stopPropagation();

                        filterOptions.forEach(
                            function (item) {

                                item.classList.remove(
                                    'active'
                                );
                            }
                        );

                        option.classList.add(
                            'active'
                        );

                        if (filterText) {
                            filterText.textContent =
                                option.textContent.trim();
                        }

                        if (targetInput) {

                            targetInput.value =
                                option.dataset.value || '';

                            targetInput.dispatchEvent(
                                new Event(
                                    'change',
                                    {
                                        bubbles: true
                                    }
                                )
                            );
                        }

                        filterSelect.classList.remove(
                            'open'
                        );
                    }
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | TUTUP SAAT KLIK DI LUAR
        |--------------------------------------------------------------------------
        */
        document.addEventListener(
            'click',
            function (event) {

                if (
                    !filterSelect.contains(
                        event.target
                    )
                ) {
                    filterSelect.classList.remove(
                        'open'
                    );
                }
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER AWAL
    |--------------------------------------------------------------------------
    */
    renderKatalog();
});