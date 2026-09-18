document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SEARCH KATALOG LANDING
    |--------------------------------------------------------------------------
    */
    const searchInput = document.getElementById('landingBookSearch');
    const bookItems = document.querySelectorAll('.landing-book-item');
    const emptyMessage = document.getElementById('landingBookEmpty');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const keyword = this.value
                .toLowerCase()
                .trim();
            let visibleBooks = 0;

            bookItems.forEach(function (item) {
                const title = item.dataset.title || '';
                const author = item.dataset.author || '';
                const category = item.dataset.category || '';
                const match =
                    title.includes(keyword) ||
                    author.includes(keyword) ||
                    category.includes(keyword);

                if (match) {
                    item.style.display = '';
                    visibleBooks++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (emptyMessage) {
                emptyMessage.style.display =
                    visibleBooks === 0 && bookItems.length > 0
                        ? 'block'
                        : 'none';
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN REQUIRED MODAL
    |--------------------------------------------------------------------------
    */
    const loginModal = document.getElementById('publicLoginModal');
    const loginModalCancel = document.getElementById('publicLoginModalCancel');
    const loginRequiredItems = document.querySelectorAll('.landing-login-required');

    if (loginModal) {
        loginRequiredItems.forEach(function (item) {
            item.addEventListener('click', function () {
                loginModal.classList.add('show');
            });
        });

        if (loginModalCancel) {
            loginModalCancel.addEventListener('click', function () {
                loginModal.classList.remove('show');
            });
        }

        loginModal.addEventListener('click', function (event) {
            if (event.target === loginModal) {
                loginModal.classList.remove('show');
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                loginModal.classList.remove('show');
            }
        });
    }
});