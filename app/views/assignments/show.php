<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <!-- Başlık ve Geri Dön Butonu -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-list text-primary mr-2"></i> Görevlendirme Detayları
        </h1>
        <div>
            <a href="<?php echo URLROOT; ?>/assignments" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Görevlendirme Listesine Dön
            </a>
            <a href="<?php echo URLROOT; ?>/assignments/edit/<?php echo $data['assignment']->id; ?>" class="btn btn-warning">
                <i class="fas fa-edit mr-1"></i> Düzenle
            </a>
            <?php if(isAdmin()): ?>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash mr-1"></i> Sil
                </button>
            <?php endif; ?>
    </div>
</div>

<?php flash('assignment_message'); ?>

    <!-- Durum Göstergesi -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-start border-4 border-<?php 
                echo ($data['assignment']->status == 'Aktif') ? 'success' : 
                    (($data['assignment']->status == 'Tamamlandı') ? 'info' : 'danger'); 
            ?>">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-<?php 
                                echo ($data['assignment']->status == 'Aktif') ? 'success' : 
                                    (($data['assignment']->status == 'Tamamlandı') ? 'info' : 'danger'); 
                            ?> text-white me-3 me-md-4 icon-spacing">
                                <?php if($data['assignment']->status == 'Aktif'): ?>
                                    <i class="fas fa-check-circle"></i>
                                <?php elseif($data['assignment']->status == 'Tamamlandı'): ?>
                                    <i class="fas fa-flag-checkered"></i>
                                <?php else: ?>
                                    <i class="fas fa-ban"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <small class="text-muted d-block text-uppercase">Görevlendirme Durumu</small>
                                <h4 class="mb-0 font-weight-bold text-<?php 
                                    echo ($data['assignment']->status == 'Aktif') ? 'success' : 
                                        (($data['assignment']->status == 'Tamamlandı') ? 'info' : 'danger'); 
                                ?>">
                                    <?php if($data['assignment']->status == 'Aktif'): ?>
                                        AKTİF
                                    <?php elseif($data['assignment']->status == 'Tamamlandı'): ?>
                                        TAMAMLANDI
                                    <?php elseif($data['assignment']->status == 'İptal'): ?>
                                        İPTAL
                                    <?php else: ?>
                                        <?php echo strtoupper($data['assignment']->status); ?>
                                    <?php endif; ?>
                                </h4>
                            </div>
                        </div>
                        
                        <div>
                            <?php if($data['assignment']->status == 'Aktif'): ?>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                    <i class="fas fa-exchange-alt mr-1"></i> Durumu Güncelle
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Araç ve Sürücü Bilgileri -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-info-circle mr-2"></i>Görevlendirme Bilgileri
                    </h6>
                </div>
                <div class="card-body p-0">
                    <!-- Görevlendirme ID -->
                    <div class="p-4 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-secondary text-white mr-3 icon-spacing">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Görevlendirme ID</small>
                                <h5 class="mb-0 font-weight-bold">#<?php echo $data['assignment']->id; ?></h5>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Araç Bilgileri -->
                    <div class="p-4 border-bottom bg-light">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-primary text-white mr-3 icon-spacing">
                                <i class="fas fa-car"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Araç</small>
                                <h5 class="mb-0 font-weight-bold">
                                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $data['assignment']->vehicle_id; ?>" class="text-primary">
                                        <span class="badge badge-dark"><?php echo $data['assignment']->plate_number; ?></span>
                                        <?php echo $data['assignment']->vehicle_brand . ' ' . $data['assignment']->vehicle_model; ?>
                                    </a>
                                </h5>
                                <small class="text-muted">
                                    <i class="fas fa-tag mr-1"></i> <?php echo $data['assignment']->vehicle_type; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sürücü Bilgileri -->
                    <div class="p-4 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-success text-white mr-3 icon-spacing">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Sürücü</small>
                                <h5 class="mb-0 font-weight-bold">
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $data['assignment']->driver_id; ?>" class="text-primary">
                                        <?php echo $data['assignment']->driver_name . ' ' . $data['assignment']->driver_surname; ?>
                                    </a>
                                </h5>
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-phone mr-1"></i> <?php echo $data['assignment']->driver_phone; ?>
                                    </small>
                                </div>
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-id-card mr-1"></i> <?php echo $data['assignment']->driver_license; ?> Ehliyet
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Konum Bilgileri -->
                    <div class="p-4 bg-light">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-danger text-white mr-3 icon-spacing">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Konum</small>
                                <h5 class="mb-0 font-weight-bold">
                                    <?php echo $data['assignment']->location; ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tarih Bilgileri -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-info">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-calendar-alt mr-2"></i>Zaman Bilgileri
                    </h6>
                </div>
                <div class="card-body p-0">
                    <!-- Başlangıç Tarihi -->
                    <div class="p-4 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-primary text-white mr-3 icon-spacing">
                                <i class="fas fa-hourglass-start"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Başlangıç Tarihi</small>
                                <h5 class="mb-0 font-weight-bold"><?php echo date('d.m.Y', strtotime($data['assignment']->start_date)); ?></h5>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bitiş Tarihi -->
                    <div class="p-4 border-bottom bg-light">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle <?php echo $data['assignment']->end_date ? 'bg-success' : 'bg-warning'; ?> text-white mr-3 icon-spacing">
                                <i class="fas fa-hourglass-end"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Bitiş Tarihi</small>
                                <h5 class="mb-0 font-weight-bold">
                                    <?php if($data['assignment']->end_date): ?>
                                        <?php echo date('d.m.Y', strtotime($data['assignment']->end_date)); ?>
                                    <?php else: ?>
                                        <span class="text-warning">Belirtilmemiş</span>
                                    <?php endif; ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kayıt Tarihi -->
                    <div class="p-4 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-secondary text-white mr-3 icon-spacing">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Kayıt Tarihi</small>
                                <h5 class="mb-0 font-weight-bold"><?php echo date('d.m.Y H:i', strtotime($data['assignment']->created_at)); ?></h5>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($data['assignment']->start_date && $data['assignment']->end_date): ?>
                    <!-- Toplam Süre -->
                    <div class="p-4 bg-light">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-info text-white mr-3 icon-spacing">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Toplam Görev Süresi</small>
                                <h5 class="mb-0 font-weight-bold">
                                    <?php 
                                    $start = new DateTime($data['assignment']->start_date);
                                    $end = new DateTime($data['assignment']->end_date);
                                    $interval = $start->diff($end);
                                    echo $interval->days . ' gün';
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notlar Bölümü -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-sticky-note text-primary mr-2"></i>Notlar</h5>
        </div>
        <div class="card-body">
            <?php if (empty(trim($data['assignment']->notes))): ?>
                <div class="text-center text-muted py-4">
                    <i class="fas fa-info-circle mb-3" style="font-size: 2.5rem;"></i>
                    <p class="mb-0">Bu görev için not bulunmamaktadır.</p>
                </div>
            <?php else: ?>
                <div class="p-3 bg-light rounded border">
                    <?php echo nl2br(htmlspecialchars($data['assignment']->notes)); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Durum Güncelleme Modalı -->
<?php if($data['assignment']->status == 'Aktif'): ?>
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateStatusModalLabel">Görevlendirme Durumunu Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo URLROOT; ?>/assignments/updateStatus/<?php echo $data['assignment']->id; ?>" method="post" id="updateStatusForm">
                    <div class="form-group mb-3">
                        <label for="status" class="form-label">Yeni Durum:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="Aktif" selected>Aktif</option>
                            <option value="Tamamlandı">Tamamlandı</option>
                            <option value="İptal">İptal</option>
                        </select>
                    </div>
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle mr-1"></i> Durumu "Tamamlandı" veya "İptal" olarak değiştirdiğinizde, bitiş tarihi otomatik olarak bugünün tarihi olarak ayarlanacaktır.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="submit" form="updateStatusForm" class="btn btn-primary">Güncelle</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Silme Onay Modalı -->
<?php if(isAdmin()): ?>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Görevlendirme Silme Onayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    <strong><?php echo $data['assignment']->plate_number; ?></strong> plakalı araca ait 
                    <strong><?php echo $data['assignment']->driver_name . ' ' . $data['assignment']->driver_surname; ?></strong> 
                    isimli sürücü görevlendirmesini silmek istediğinize emin misiniz?
                </p>
                <div class="alert alert-danger small">
                    <i class="fas fa-exclamation-triangle mr-1"></i> 
                    <strong>Dikkat:</strong> Bu işlem geri alınamaz!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form action="<?php echo URLROOT; ?>/assignments/delete/<?php echo $data['assignment']->id; ?>" method="post">
                    <button type="submit" class="btn btn-danger">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
.icon-circle {
    height: 3rem;
    width: 3rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.border-start {
    border-left-width: 0.25rem !important;
    border-left-style: solid !important;
}

.border-4 {
    border-width: 4px !important;
}

.border-success {
    border-color: #1cc88a !important;
}

.border-info {
    border-color: #36b9cc !important;
}

.border-danger {
    border-color: #e74a3b !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-xs {
    font-size: 0.7rem;
}

.font-weight-bold {
    font-weight: 700 !important;
}

.btn-group .btn {
    margin-right: 3px;
}

/* Transition effects */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

/* Hover effect for icons */
.icon-circle {
    transition: all 0.3s ease;
}

.card:hover .icon-circle {
    transform: scale(1.1);
}

/* Custom colors for badges */
.badge-dark {
    background-color: #343a40;
    color: #fff;
}

.badge-secondary {
    background-color: #6c757d;
    color: #fff;
}

/* Modal görünüm düzeltmeleri */
.modal {
    z-index: 1050;
}
.modal-backdrop {
    z-index: 1040;
}
.modal-dialog {
    margin: 1.75rem auto;
}
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?> 