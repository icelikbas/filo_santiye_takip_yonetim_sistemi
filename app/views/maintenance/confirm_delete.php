<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><i class="fas fa-trash-alt mr-2"></i><?php echo $data['title']; ?></h2>
            <a href="<?php echo URLROOT; ?>/maintenance" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left mr-1"></i> Geri Dön
            </a>
        </div>
    </div>

    <?php flash('success'); ?>
    <?php flash('error'); ?>

    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-exclamation-triangle mr-2"></i>Silme İşlemi Onayı</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <strong>Uyarı:</strong> Bu işlem geri alınamaz ve tüm bakım verilerini silecektir.
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Bakım Bilgileri</h6>
                        </div>
                        <div class="card-body">
                            <div class="row py-2 border-bottom">
                                <div class="col-5 font-weight-bold">Bakım ID:</div>
                                <div class="col-7"><?php echo $data['record']->id; ?></div>
                            </div>
                            <div class="row py-2 border-bottom">
                                <div class="col-5 font-weight-bold">Araç:</div>
                                <div class="col-7"><?php echo $data['record']->plate_number; ?></div>
                            </div>
                            <div class="row py-2 border-bottom">
                                <div class="col-5 font-weight-bold">Bakım Türü:</div>
                                <div class="col-7">
                                    <?php if($data['record']->maintenance_type == 'Periyodik Bakım'): ?>
                                        <span class="badge badge-primary"><?php echo $data['record']->maintenance_type; ?></span>
                                    <?php elseif($data['record']->maintenance_type == 'Arıza'): ?>
                                        <span class="badge badge-danger"><?php echo $data['record']->maintenance_type; ?></span>
                                    <?php elseif($data['record']->maintenance_type == 'Lastik Değişimi'): ?>
                                        <span class="badge badge-warning"><?php echo $data['record']->maintenance_type; ?></span>
                                    <?php elseif($data['record']->maintenance_type == 'Yağ Değişimi'): ?>
                                        <span class="badge badge-info"><?php echo $data['record']->maintenance_type; ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary"><?php echo $data['record']->maintenance_type; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row py-2 border-bottom">
                                <div class="col-5 font-weight-bold">Açıklama:</div>
                                <div class="col-7"><?php echo $data['record']->description; ?></div>
                            </div>
                            <div class="row py-2 border-bottom">
                                <div class="col-5 font-weight-bold">Durum:</div>
                                <div class="col-7">
                                    <?php if($data['record']->status == 'Planlandı'): ?>
                                        <span class="badge badge-info">Planlandı</span>
                                    <?php elseif($data['record']->status == 'Devam Ediyor'): ?>
                                        <span class="badge badge-warning">Devam Ediyor</span>
                                    <?php elseif($data['record']->status == 'Tamamlandı'): ?>
                                        <span class="badge badge-success">Tamamlandı</span>
                                    <?php elseif($data['record']->status == 'İptal'): ?>
                                        <span class="badge badge-danger">İptal</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row py-2 border-bottom">
                                <div class="col-5 font-weight-bold">Başlangıç:</div>
                                <div class="col-7">
                                    <?php if($data['record']->start_date): ?>
                                        <?php echo date('d.m.Y', strtotime($data['record']->start_date)); ?>
                                    <?php else: ?>
                                        <span class="text-muted">Belirtilmemiş</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-5 font-weight-bold">Maliyet:</div>
                                <div class="col-7"><?php echo number_format($data['record']->cost, 2, ',', '.'); ?> ₺</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <h5 class="text-danger mb-4">Bu bakım kaydını silmek istediğinize emin misiniz?</h5>
                
                <form action="<?php echo URLROOT; ?>/maintenance/delete/<?php echo $data['record']->id; ?>" method="post" class="d-inline">
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="fas fa-trash mr-1"></i> Evet, Sil
                    </button>
                </form>
                
                <a href="<?php echo URLROOT; ?>/maintenance" class="btn btn-secondary btn-lg ml-2">
                    <i class="fas fa-times mr-1"></i> İptal
                </a>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 