<?php require APPROOT . '/views/inc/header_login.php'; ?>

<div class="login-container">
    <div class="login-wrapper">
        <div class="login-form-container">
            <div class="login-logo-container">
                <!-- Logo alanı -->
                <div class="company-logo-placeholder">
                    <i class="fas fa-truck-moving"></i>
                    <div class="company-name">Filo Takip</div>
                </div>
            </div>
            
            <div class="login-card">
                <div class="login-header">
                    <h3>Hoş Geldiniz</h3>
                    <p>Sisteme giriş yapmak için bilgilerinizi giriniz</p>
                </div>
                
                <?php flash('register_success'); ?>
                <?php flash('login_fail'); ?>
                
                <form action="<?php echo URLROOT; ?>/users/login" method="post" class="login-form" autocomplete="off">
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                                   placeholder="E-posta adresiniz" value="<?php echo $data['email']; ?>" required>
                        </div>
                        <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                    </div>
                    
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" 
                                   placeholder="Şifreniz" required>
                        </div>
                        <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block w-100 login-btn">
                            <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
                        </button>
                    </div>
                </form>
                
                <div class="login-footer">
                    <div class="footer-info">
                        <p>&copy; <?php echo date('Y'); ?> Duygu İnşaat Filo Takip Sistemi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
