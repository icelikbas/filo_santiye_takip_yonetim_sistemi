<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user-circle mr-2"></i>Kullanıcı Profili</h4>
            </div>
            <div class="card-body">
                <?php flash('profile_message'); ?>
                
                <!-- Hesap Bilgileri -->
                <h5 class="mb-3">Hesap Bilgileri</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Adı Soyadı:</th>
                        <td><?php echo $data['user']->name . ' ' . $data['user']->surname; ?></td>
                    </tr>
                    <tr>
                        <th>E-posta:</th>
                        <td><?php echo $data['user']->email; ?></td>
                    </tr>
                    <tr>
                        <th>Kullanıcı Rolü:</th>
                        <td>
                            <?php if($data['user']->role == 'admin'): ?>
                                <span class="badge bg-primary">Yönetici</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Kullanıcı</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Kayıt Tarihi:</th>
                        <td><?php echo date('d.m.Y H:i', strtotime($data['user']->created_at)); ?></td>
                    </tr>
                </table>

                <!-- Şifre Değiştir -->
                <h5 class="mt-4 mb-3">Şifre Değiştir</h5>
                <form action="<?php echo URLROOT; ?>/users/changePassword" method="post">
                    <div class="form-group mb-3">
                        <label for="current_password">Mevcut Şifre:</label>
                        <input type="password" name="current_password" class="form-control <?php echo (!empty($data['current_password_err'])) ? 'is-invalid' : ''; ?>" required>
                        <span class="invalid-feedback"><?php echo $data['current_password_err'] ?? ''; ?></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="new_password">Yeni Şifre:</label>
                        <input type="password" name="new_password" class="form-control <?php echo (!empty($data['new_password_err'])) ? 'is-invalid' : ''; ?>" required>
                        <span class="invalid-feedback"><?php echo $data['new_password_err'] ?? ''; ?></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="confirm_password">Yeni Şifre (Tekrar):</label>
                        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" required>
                        <span class="invalid-feedback"><?php echo $data['confirm_password_err'] ?? ''; ?></span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Şifreyi Değiştir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 