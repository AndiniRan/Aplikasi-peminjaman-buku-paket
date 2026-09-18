document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */
    const searchInput = document.getElementById('landingKatalogSearch');
    const categoryInput = document.getElementById('landingKatalogKategori');
    const filterSelect = document.querySelector(
        '.landing-katalog-filter-select'
    );
    const filterButton = document.getElementById(
        'landingKatalogFilterButton'
    );
    const filterOptions = document.querySelectorAll(
        '.landing-katalog-filter-options button'
    );
    const items = Array.from(
        document.querySelectorAll('.landing-katalog-item')
    );
    const emptyState = document.getElementById(
        'landingKatalogEmpty'
    );
    const pagination = document.getElementById(
        'landingKatalogPagination'
    );

    /*
    |--------------------------------------------------------------------------
    | CONFIG
    |--------------------------------------------------------------------------
    */
    const itemsPerPage = 10;
    let currentPage = 1;
    let filteredItems = [...items];

    /*
    |--------------------------------------------------------------------------
    | FILTER DATA
    |--------------------------------------------------------------------------
    */
    function filterItems() {
        const search = searchInput.value
            .toLowerCase()
            .trim();
        const category = categoryInput.value
            .toLowerCase()
            .trim();

        filteredItems = items.filter(item => {
            const searchData = item.dataset.search || '';
            const categoryData = item.dataset.category || '';
            const matchSearch =
                search === '' ||
                searchData.includes(search);
            const matchCategory =
                category === '' ||
                categoryData === category;
            return matchSearch && matchCategory;
        });

        currentPage = 1;
        renderItems();
    }

    /*
    |--------------------------------------------------------------------------
    | RENDER ITEM
    |--------------------------------------------------------------------------
    */
    function renderItems() {
        items.forEach(item => {
            item.style.display = 'none';
        });

        const totalPages = Math.ceil(
            filteredItems.length / itemsPerPage
        );

        if (
            currentPage > totalPages &&
            totalPages > 0
        ) {
            currentPage = totalPages;
        }

        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const currentItems = filteredItems.slice(start, end);
        currentItems.forEach(item => {
            item.style.display = '';
        });

        if (filteredItems.length === 0) {
            emptyState.classList.add('show');
            pagination.innerHTML = '';
        } else {
            emptyState.classList.remove('show');
            renderPagination(
                totalPages
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */
    function renderPagination(totalPages) {

        pagination.innerHTML = '';

        if (totalPages <= 1) {
            return;
        }

        const previousButton = document.createElement('button');
        previousButton.type = 'button';
        previousButton.innerHTML = '<i class="bi bi-chevron-left"></i>';
        previousButton.disabled = currentPage === 1;
        previousButton.addEventListener(
            'click',
            () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderItems();
                    scrollToCatalog();
                }
            }
        );

        pagination.appendChild(
            previousButton
        );
        const pages = getVisiblePages(
            currentPage,
            totalPages
        );

        pages.forEach(page => {
            if (page === '...') {
                const dots = document.createElement('button');
                dots.type = 'button';
                dots.textContent = '...';
                dots.disabled = true;
                pagination.appendChild(
                    dots
                );
                return;
            }

            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = page;

            if (page === currentPage) {
                button.classList.add(
                    'active'
                );
            }
            button.addEventListener(
                'click',
                () => {
                    currentPage = page;
                    renderItems();
                    scrollToCatalog();
                }
            );
            pagination.appendChild(
                button
            );
        });

        const nextButton = document.createElement('button');
        nextButton.type = 'button';
        nextButton.innerHTML = '<i class="bi bi-chevron-right"></i>';
        nextButton.disabled = currentPage === totalPages;
        nextButton.addEventListener(
            'click',
            () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderItems();
                    scrollToCatalog();
                }
            }
        );
        pagination.appendChild(
            nextButton
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAGE RANGE
    |--------------------------------------------------------------------------
    */
    function getVisiblePages(
        current,
        total
    ) {
        if (total <= 7) {
            return Array.from(
                { length: total },
                (_, index) => index + 1
            );
        }

        if (current <= 4) {
            return [
                1,
                2,
                3,
                4,
                5,
                '...',
                total
            ];
        }

        if (current >= total - 3) {
            return [
                1,
                '...',
                total - 4,
                total - 3,
                total - 2,
                total - 1,
                total
            ];
        }

        return [
            1,
            '...',
            current - 1,
            current,
            current + 1,
            '...',
            total
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SCROLL
    |--------------------------------------------------------------------------
    */
    function scrollToCatalog() {
        const page = document.querySelector(
                '.landing-katalog-page'
            );
        if (!page) {
            return;
        }

        window.scrollTo({
            top: page.offsetTop,
            behavior: 'smooth'
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */
    if (searchInput) {
        searchInput.addEventListener(
            'input',
            filterItems
        );
    }

    /*
    |--------------------------------------------------------------------------
    | OPEN FILTER
    |--------------------------------------------------------------------------
    */
    if (
        filterButton &&
        filterSelect
    ) {
        filterButton.addEventListener(
            'click',
            event => {
                event.stopPropagation();
                filterSelect.classList.toggle(
                    'open'
                );
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SELECT CATEGORY
    |--------------------------------------------------------------------------
    */
    filterOptions.forEach(option => {
        option.addEventListener(
            'click',
            () => {
                const value = option.dataset.value || '';
                const text = option.textContent.trim();
                categoryInput.value = value;
                filterButton
                    .querySelector('span')
                    .textContent = text;

                filterOptions.forEach(
                    item => {
                        item.classList.remove(
                            'active'
                        );
                    }
                );

                option.classList.add(
                    'active'
                );

                filterSelect.classList.remove(
                    'open'
                );
                filterItems();
            }
        );
    });

    /*
    |--------------------------------------------------------------------------
    | CLOSE FILTER
    |--------------------------------------------------------------------------
    */
    document.addEventListener(
        'click',
        event => {

            if (
                filterSelect &&
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

    /*
    |--------------------------------------------------------------------------
    | LOGIN REQUIRED MODAL
    |--------------------------------------------------------------------------
    */
    const loginModal =
        document.getElementById(
            'landingKatalogLoginModal'
        );

    const loginModalCancel =
        document.getElementById(
            'landingKatalogLoginCancel'
        );

    const loginRequiredItems =
        document.querySelectorAll(
            '.landing-katalog-login-required'
        );

    if (loginModal) {
        loginRequiredItems.forEach(item => {
            item.addEventListener(
                'click',
                () => {
                    loginModal.classList.add(
                        'show'
                    );
                }
            );
        });

        if (loginModalCancel) {
            loginModalCancel.addEventListener(
                'click',
                () => {
                    loginModal.classList.remove(
                        'show'
                    );
                }
            );
        }

        loginModal.addEventListener(
            'click',
            event => {
                if (
                    event.target === loginModal
                ) {
                    loginModal.classList.remove(
                        'show'
                    );
                }
            }
        );

        document.addEventListener(
            'keydown',
            event => {
                if (event.key === 'Escape') {
                    loginModal.classList.remove(
                        'show'
                    );
                }
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INITIAL
    |--------------------------------------------------------------------------
    */
    if (
        searchInput &&
        categoryInput &&
        pagination
    ) {
        renderItems();
    }
});