document.addEventListener('DOMContentLoaded', () => {

    const photoInput =
        document.getElementById('profilePhotoInput');

    const changePhotoButton =
        document.getElementById('profileChangePhoto');

    const photoPreview =
        document.getElementById('profilePhotoPreview');

    const photoDefault =
        document.getElementById('profilePhotoDefault');


    /* =========================
       FOTO PROFIL
    ========================== */

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


                /* Maksimal 10 MB */
                const maxSize =
                    10 * 1024 * 1024;


                if (file.size > maxSize) {

                    alert(
                        'Ukuran foto maksimal 10 MB.'
                    );

                    photoInput.value = '';

                    return;
                }


                /* Preview foto */
                const reader =
                    new FileReader();


                reader.onload =
                    event => {

                        if (photoPreview) {

                            photoPreview.src =
                                event.target.result;

                            photoPreview.hidden =
                                false;
                        }


                        if (photoDefault) {

                            photoDefault.hidden =
                                true;
                        }
                    };


                reader.readAsDataURL(file);

            }
        );
    }


    /* =========================
       ALERT CLOSE
    ========================== */

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