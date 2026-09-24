

<?php $__env->startSection('title', 'Profil'); ?>
<?php $__env->startSection('page-title', 'Profil'); ?>

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/css/adminProfile.css',
    'resources/js/adminProfile.js'
]); ?>

<?php $__env->startSection('content'); ?>

<div class="profile-page">

    <?php if(session('success')): ?>
        <div class="profile-alert success">
            <i class="bi bi-check-circle-fill"></i>

            <span><?php echo e(session('success')); ?></span>

            <button
                type="button"
                class="profile-alert-close"
                aria-label="Tutup"
            >
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="profile-alert error">
            <i class="bi bi-exclamation-circle-fill"></i>

            <div>
                <strong>Profil belum berhasil diperbarui.</strong>
                <span><?php echo e($errors->first()); ?></span>
            </div>

            <button
                type="button"
                class="profile-alert-close"
                aria-label="Tutup"
            >
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- INFORMASI PROFIL -->
    <div class="profile-card profile-information-card">
        <div class="profile-information">

            <div class="profile-avatar-wrapper">
                <?php if($user->foto): ?>
                    <img
                        src="<?php echo e(asset('storage/' . $user->foto)); ?>"
                        alt="<?php echo e($user->name); ?>"
                        class="profile-avatar"
                    >
                <?php else: ?>
                    <div class="profile-avatar-default">
                        <i class="bi bi-person-fill"></i>
                    </div>
                <?php endif; ?>
            </div>

            <div class="profile-detail">

                <div class="profile-name-row">
                    <h2>Admin Perpustakaan</h2>

                    <span class="profile-role-badge">
                        <?php echo e(ucfirst($user->role)); ?>

                    </span>
                </div>

                <div class="profile-detail-list">

                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-person"></i>
                            <span>Username</span>
                        </div>

                        <span class="profile-detail-separator">:</span>

                        <span class="profile-detail-value">
                            <?php echo e($user->name); ?>

                        </span>
                    </div>

                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-person-lock"></i>
                            <span>Email</span>
                        </div>

                        <span class="profile-detail-separator">:</span>

                        <span class="profile-detail-value">
                            <?php echo e($user->email); ?>

                        </span>
                    </div>

                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-globe2"></i>
                            <span>Role</span>
                        </div>

                        <span class="profile-detail-separator">:</span>

                        <span class="profile-detail-value role">
                            <?php echo e(ucfirst($user->role)); ?>

                        </span>
                    </div>

                    <div class="profile-detail-item">
                        <div class="profile-detail-label">
                            <i class="bi bi-stopwatch"></i>
                            <span>Status Akun</span>
                        </div>

                        <span class="profile-detail-separator">:</span>

                        <span class="profile-account-status <?php echo e($user->status); ?>">
                            <span class="profile-status-dot"></span>
                            <?php echo e(ucfirst($user->status)); ?>

                        </span>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- EDIT PROFIL -->
    <div class="profile-card profile-edit-card">

        <div class="profile-edit-title">
            <h2>Edit Profil</h2>
        </div>

        <div class="profile-info-message">
            <i class="bi bi-info-circle"></i>

            <span>
                Anda dapat mengubah informasi akun pada akun form di bawah ini
            </span>
        </div>

        <form
            action="<?php echo e(route('admin.profile.update')); ?>"
            method="POST"
            enctype="multipart/form-data"
            class="profile-form"
        >
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- FOTO -->
            <div class="profile-photo-section">

                <h3>Foto Profil</h3>

                <div class="profile-photo-content">

                    <div class="profile-photo-preview-wrapper">

                        <?php if($user->foto): ?>
                            <img
                                src="<?php echo e(asset('storage/' . $user->foto)); ?>"
                                alt="<?php echo e($user->name); ?>"
                                class="profile-photo-preview"
                                id="profilePhotoPreview"
                            >

                            <div
                                class="profile-photo-default"
                                id="profilePhotoDefault"
                                hidden
                            >
                                <i class="bi bi-person-fill"></i>
                            </div>
                        <?php else: ?>
                            <img
                                src=""
                                alt="Preview Foto"
                                class="profile-photo-preview"
                                id="profilePhotoPreview"
                                hidden
                            >

                            <div
                                class="profile-photo-default"
                                id="profilePhotoDefault"
                            >
                                <i class="bi bi-person-fill"></i>
                            </div>
                        <?php endif; ?>

                        <div class="profile-photo-camera">
                            <i class="bi bi-camera-fill"></i>
                        </div>

                    </div>

                    <p class="profile-photo-help">
                        Format JPG, PNG. Maks. 10MB
                    </p>

                    <input
                        type="file"
                        name="foto"
                        id="profilePhotoInput"
                        accept=".jpg,.jpeg,.png"
                        hidden
                    >

                    <button
                        type="button"
                        class="profile-change-photo"
                        id="profileChangePhoto"
                    >
                        <i class="bi bi-camera"></i>
                        Ubah Foto
                    </button>

                    <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="profile-field-error profile-photo-error">
                            <?php echo e($message); ?>

                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                </div>
            </div>

            <div class="profile-form-divider"></div>

            <!-- FORM -->
            <div class="profile-fields">

                <div class="profile-field">
                    <label for="profileName">
                        Username
                    </label>

                    <div class="profile-input-wrapper">
                        <input
                            type="text"
                            name="name"
                            id="profileName"
                            value="<?php echo e(old('name', $user->name)); ?>"
                            placeholder="Masukan Username"
                            required
                        >
                    </div>

                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="profile-field-error">
                            <?php echo e($message); ?>

                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="profile-field">
                    <label for="profileEmail">
                        Email
                    </label>

                    <div class="profile-input-wrapper">
                        <input
                            type="email"
                            name="email"
                            id="profileEmail"
                            value="<?php echo e(old('email', $user->email)); ?>"
                            placeholder="Masukan Email"
                            required
                        >
                    </div>

                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="profile-field-error">
                            <?php echo e($message); ?>

                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="profile-field">
                    <label for="profilePassword">
                        Password Baru
                    </label>

                    <div class="profile-input-wrapper">
                        <input
                            type="password"
                            name="password"
                            id="profilePassword"
                            placeholder="Masukan Password Baru"
                            autocomplete="new-password"
                        >

                        <button
                            type="button"
                            class="profile-password-toggle"
                            id="profilePasswordToggle"
                            aria-label="Tampilkan password"
                        >
                            <i class="bi bi-eye-slash-fill"></i>
                        </button>
                    </div>

                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="profile-field-error">
                            <?php echo e($message); ?>

                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="profile-submit-wrapper">
                    <button
                        type="submit"
                        class="profile-submit-button"
                    >
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </form>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Aplikasi_peminjaman_buku_paket(PKL)\resources\views/admin/profile/profile.blade.php ENDPATH**/ ?>