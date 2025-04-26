<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-edit mr-2"></i> Ceza Tipi Düzenle
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/finetypes" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Geri Dön
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('fine_type_message'); ?>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-tag mr-2"></i>Ceza Tipi Bilgileri</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/finetypes/edit/<?php echo $data['id']; ?>" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code"><strong>Ceza Kodu:</strong> <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control <?php echo (!empty($data['code_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['code']; ?>" placeholder="Örn: HizIhlal01">
                                    <span class="invalid-feedback"><?php echo $data['code_err']; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name"><strong>Ceza Adı:</strong> <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>" placeholder="Örn: Hız Sınırı İhlali">
                                    <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="legal_article"><strong>İlgili Kanun Maddesi:</strong></label>
                                    <input type="text" name="legal_article" class="form-control" value="<?php echo $data['legal_article']; ?>" placeholder="Örn: KTK Madde 51">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="points"><strong>Ceza Puanı:</strong></label>
                                    <input type="number" name="points" class="form-control <?php echo (!empty($data['points_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['points']; ?>" placeholder="Örn: 20">
                                    <span class="invalid-feedback"><?php echo $data['points_err']; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description"><strong>Açıklama:</strong></label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Ceza tipi hakkında açıklama..."><?php echo $data['description']; ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="default_amount"><strong>Varsayılan Tutar (₺):</strong> <span class="text-danger">*</span></label>
                                    <input type="text" name="default_amount" class="form-control <?php echo (!empty($data['default_amount_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['default_amount']; ?>" placeholder="0,00">
                                    <span class="invalid-feedback"><?php echo $data['default_amount_err']; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="active" class="d-block"><strong>Durum:</strong></label>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" id="active" name="active" <?php echo ($data['active'] == 1) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="active">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save mr-2"></i> Değişiklikleri Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 