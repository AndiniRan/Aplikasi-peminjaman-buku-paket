document.addEventListener('DOMContentLoaded', () => {

    const photoInput =
        document.getElementById('profilePhotoInput');

    const changePhotoButton =
        document.getElementById('profileChangePhoto');

    const photoPreview =
        document.getElementById('profilePhotoPreview');

    const photoDefault =
        document.getElementById('profilePhotoDefault');

    const passwordInput =
        document.getElementById('profilePassword');

    const passwordToggle =
        document.getElementById('profilePasswordToggle');

    /* FOTO PROFIL */
    if (
        photoInput &&
        changePhotoButton
    ) {
        changePhotoButton.addEventListener(
            'click',
            () => {
                photoInput.click();
            }
        );

        photoInput.addEventListener(
            'change',
            event => {
                const file =
                    event.target.files[0];

                if (!file) {
                    return;
                }

                const allowedTypes = [
                    'image/jpeg',
                    'image/png'
                ];

                if (!allowedTypes.includes(file.type)) {
                    alert(
                        'Foto harus berformat JPG, JPEG, atau PNG.'
                    );

                    photoInput.value = '';

                    return;
                }

                const maxSize =
                    2 * 1024 * 1024;

                if (file.size > maxSize) {
                    alert(
                        'Ukuran foto maksimal 2 MB.'
                    );

                    photoInput.value = '';

                    return;
                }

                const reader =
                    new FileReader();

                reader.onload = event => {
                    if (photoPreview) {
                        photoPreview.src =
                            event.target.result;

                        photoPreview.hidden = false;
                    }

                    if (photoDefault) {
                        photoDefault.hidden = true;
                    }
                };

                reader.readAsDataURL(file);
            }
        );
    }

    /* PASSWORD */
    if (
        passwordInput &&
        passwordToggle
    ) {
        passwordToggle.addEventListener(
            'click',
            () => {
                const icon =
                    passwordToggle.querySelector('i');

                const isPassword =
                    passwordInput.type === 'password';

                passwordInput.type =
                    isPassword
                        ? 'text'
                        : 'password';

                if (icon) {
                    icon.className =
                        isPassword
                            ? 'bi bi-eye'
                            : 'bi bi-eye-slash';
                }

                passwordToggle.setAttribute(
                    'aria-label',
                    isPassword
                        ? 'Sembunyikan password'
                        : 'Tampilkan password'
                );
            }
        );
    }

    /* ALERT */
    document
        .querySelectorAll('.profile-alert-close')
        .forEach(button => {
            button.addEventListener(
                'click',
                () => {
                    const alert =
                        button.closest(
                            '.profile-alert'
                        );

                    if (alert) {
                        alert.remove();
                    }
                }
            );
        });
});