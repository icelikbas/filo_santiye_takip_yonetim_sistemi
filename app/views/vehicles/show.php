<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-truck mr-2"></i> <?php echo $data['vehicle']->plate_number; ?> - <?php echo $data['vehicle']->brand; ?> <?php echo $data['vehicle']->model; ?>
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/vehicles" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Araç Listesine Dön
                    </a>
                    <a href="<?php echo URLROOT; ?>/vehicles/edit/<?php echo $data['vehicle']->id; ?>" class="btn btn-primary ml-2">
                        <i class="fas fa-edit mr-1"></i> Düzenle
                    </a>
                    <?php if(isAdmin()) : ?>
                        <form class="d-inline" action="<?php echo URLROOT; ?>/vehicles/delete/<?php echo $data['vehicle']->id; ?>" method="post">
                            <button type="submit" class="btn btn-danger ml-2" onclick="return confirm('Bu aracı silmek istediğinize emin misiniz?');">
                                <i class="fas fa-trash mr-1"></i> Sil
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <!-- Ana Bilgiler ve Görevlendirmeler -->
    <div class="row">
        <!-- Araç Detayları -->
        <div class="col-md-6">
            <div class="card border-left-primary shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Araç Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Plaka</h6>
                            <h5><?php echo $data['vehicle']->plate_number; ?></h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Marka / Model</h6>
                            <h5><?php echo $data['vehicle']->brand; ?> <?php echo $data['vehicle']->model; ?></h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Model Yılı</h6>
                            <h5><?php echo $data['vehicle']->year; ?></h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Araç Tipi</h6>
                            <h5><?php echo $data['vehicle']->vehicle_type; ?></h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Durum</h6>
                            <h5>
                                <?php if($data['vehicle']->status == 'Aktif') : ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php elseif($data['vehicle']->status == 'Pasif') : ?>
                                    <span class="badge badge-secondary">Pasif</span>
                                <?php elseif($data['vehicle']->status == 'Bakımda') : ?>
                                    <span class="badge badge-warning">Bakımda</span>
                                <?php endif; ?>
                            </h5>
                        </div>
                        <?php if(!empty($data['vehicle']->company_id)): ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Şirket</h6>
                            <h5>
                                <?php 
                                    // Şirket bilgisini göster
                                    if(isset($data['company']) && $data['company']) {
                                        echo '<a href="' . URLROOT . '/companies/show/' . $data['company']->id . '">' . $data['company']->company_name . '</a>';
                                    } else {
                                        echo 'Belirtilmemiş';
                                    }
                                ?>
                            </h5>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Kayıt Tarihi</h6>
                            <h5><?php echo date('d.m.Y', strtotime($data['vehicle']->created_at)); ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ek Detay Bilgileri -->
        <div class="col-md-6">
            <div class="card border-left-info shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-clipboard-list mr-2"></i>Ek Bilgiler</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if(!empty($data['vehicle']->order_number)): ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Sipariş Numarası</h6>
                            <h5><?php echo $data['vehicle']->order_number; ?></h5>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['vehicle']->equipment_number)): ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Ekipman Numarası</h6>
                            <h5><?php echo $data['vehicle']->equipment_number; ?></h5>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['vehicle']->fixed_asset_number)): ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Sabit Varlık Numarası</h6>
                            <h5><?php echo $data['vehicle']->fixed_asset_number; ?></h5>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['vehicle']->cost_center)): ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Maliyet Merkezi</h6>
                            <h5><?php echo $data['vehicle']->cost_center; ?></h5>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['vehicle']->production_site)): ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Üretim Sahası</h6>
                            <h5><?php echo $data['vehicle']->production_site; ?></h5>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['vehicle']->work_site)): ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Çalışma Sahası</h6>
                            <h5><?php echo $data['vehicle']->work_site; ?></h5>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sigorta ve Muayene Bilgileri -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-calendar-check mr-2"></i>Sigorta ve Muayene Bilgileri</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php if(!empty($data['vehicle']->inspection_date)): ?>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-warning shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Muayene Tarihi</h6>
                            <h5><?php echo date('d.m.Y', strtotime($data['vehicle']->inspection_date)); ?></h5>
                            <?php 
                            // Muayene tarihinin geçip geçmediğini kontrol et
                            $inspectionDate = new DateTime($data['vehicle']->inspection_date);
                            $today = new DateTime();
                            $daysDiff = $today->diff($inspectionDate)->days;
                            $isExpired = $today > $inspectionDate;
                            
                            if($isExpired): ?>
                                <div class="mt-2 text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Muayene süresi dolmuş</div>
                            <?php elseif($daysDiff <= 30): ?>
                                <div class="mt-2 text-warning"><i class="fas fa-exclamation-circle mr-1"></i> Son <?php echo $daysDiff; ?> gün</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($data['vehicle']->traffic_insurance_date)): ?>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-primary shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Trafik Sigortası</h6>
                            <h5><?php echo date('d.m.Y', strtotime($data['vehicle']->traffic_insurance_date)); ?></h5>
                            <?php if(!empty($data['vehicle']->traffic_insurance_agency)): ?>
                                <div class="small text-muted"><?php echo $data['vehicle']->traffic_insurance_agency; ?></div>
                            <?php endif; ?>
                            
                            <?php 
                            // Trafik sigortası tarihinin geçip geçmediğini kontrol et
                            $insuranceDate = new DateTime($data['vehicle']->traffic_insurance_date);
                            $today = new DateTime();
                            $daysDiff = $today->diff($insuranceDate)->days;
                            $isExpired = $today > $insuranceDate;
                            
                            if($isExpired): ?>
                                <div class="mt-2 text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Sigorta süresi dolmuş</div>
                            <?php elseif($daysDiff <= 30): ?>
                                <div class="mt-2 text-warning"><i class="fas fa-exclamation-circle mr-1"></i> Son <?php echo $daysDiff; ?> gün</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($data['vehicle']->casco_insurance_date)): ?>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-success shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Kasko Sigortası</h6>
                            <h5><?php echo date('d.m.Y', strtotime($data['vehicle']->casco_insurance_date)); ?></h5>
                            <?php if(!empty($data['vehicle']->casco_insurance_agency)): ?>
                                <div class="small text-muted"><?php echo $data['vehicle']->casco_insurance_agency; ?></div>
                            <?php endif; ?>
                            
                            <?php 
                            // Kasko sigortası tarihinin geçip geçmediğini kontrol et
                            $cascoDate = new DateTime($data['vehicle']->casco_insurance_date);
                            $today = new DateTime();
                            $daysDiff = $today->diff($cascoDate)->days;
                            $isExpired = $today > $cascoDate;
                            
                            if($isExpired): ?>
                                <div class="mt-2 text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Kasko süresi dolmuş</div>
                            <?php elseif($daysDiff <= 30): ?>
                                <div class="mt-2 text-warning"><i class="fas fa-exclamation-circle mr-1"></i> Son <?php echo $daysDiff; ?> gün</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Kilometre ve Çalışma Saati Bilgileri -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-tachometer-alt mr-2"></i>Kilometre ve Çalışma Saati Bilgileri</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php if(!empty($data['vehicle']->initial_km) || !empty($data['vehicle']->current_km)): ?>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-primary shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Kilometre Bilgisi</h6>
                            <?php if(!empty($data['vehicle']->initial_km)): ?>
                                <div class="mb-2">
                                    <span class="text-muted">Başlangıç:</span>
                                    <h5><?php echo number_format($data['vehicle']->initial_km, 0, ',', '.'); ?> km</h5>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($data['vehicle']->current_km)): ?>
                                <div>
                                    <span class="text-muted">Güncel:</span>
                                    <h5><?php echo number_format($data['vehicle']->current_km, 0, ',', '.'); ?> km</h5>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($data['vehicle']->initial_hours) || !empty($data['vehicle']->current_hours)): ?>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-success shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Çalışma Saati</h6>
                            <?php if(!empty($data['vehicle']->initial_hours)): ?>
                                <div class="mb-2">
                                    <span class="text-muted">Başlangıç:</span>
                                    <h5><?php echo number_format($data['vehicle']->initial_hours, 2, ',', '.'); ?> saat</h5>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($data['vehicle']->current_hours)): ?>
                                <div>
                                    <span class="text-muted">Güncel:</span>
                                    <h5><?php echo number_format($data['vehicle']->current_hours, 2, ',', '.'); ?> saat</h5>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Aktif Görevlendirme Bilgileri -->
    <div class="row">
        <div class="col-md-6">
            <?php
            // Aktif görevlendirmeyi kontrol et
            $activeAssignment = null;
            if(isset($data['assignments']) && !empty($data['assignments'])) {
                foreach($data['assignments'] as $assignment) {
                    if($assignment->status == 'Aktif') {
                        $activeAssignment = $assignment;
                        break;
                    }
                }
            }
            ?>

            <div class="card border-left-success shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-user-check mr-2"></i>Aktif Görevlendirme</h5>
                </div>
                <div class="card-body">
                    <?php if($activeAssignment): ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Şoför</h6>
                                <h5>
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $activeAssignment->driver_id; ?>">
                                        <?php echo $activeAssignment->driver_name; ?>
                                    </a>
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Görevlendirme Tarihi</h6>
                                <h5><?php echo date('d.m.Y', strtotime($activeAssignment->start_date)); ?></h5>
                            </div>
                            <?php if($activeAssignment->end_date): ?>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Planlanan Bitiş</h6>
                                <h5><?php echo date('d.m.Y', strtotime($activeAssignment->end_date)); ?></h5>
                            </div>
                            <?php endif; ?>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Durum</h6>
                                <h5><span class="badge badge-success">Aktif</span></h5>
                            </div>
                            <?php if(!empty($activeAssignment->notes)): ?>
                            <div class="col-md-12">
                                <h6 class="text-muted">Notlar</h6>
                                <p><?php echo $activeAssignment->notes; ?></p>
                            </div>
                            <?php endif; ?>
                            <div class="col-md-12 mt-2">
                                <a href="<?php echo URLROOT; ?>/assignments/show/<?php echo $activeAssignment->id; ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye mr-1"></i> Görevlendirme Detayı
                                </a>
                                <a href="<?php echo URLROOT; ?>/assignments/edit/<?php echo $activeAssignment->id; ?>" class="btn btn-sm btn-primary m-1" title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Bu araç için aktif görevlendirme bulunmuyor</h5>
                            <a href="<?php echo URLROOT; ?>/assignments/add" class="btn btn-success mt-3">
                                <i class="fas fa-plus mr-1"></i> Yeni Görevlendirme Ekle
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Görevlendirme Geçmişi -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold">
                <i class="fas fa-history mr-2"></i> Görevlendirme Geçmişi
                <a href="<?php echo URLROOT; ?>/assignments/add" class="btn btn-success btn-sm float-right">
                    <i class="fas fa-plus mr-1"></i> Yeni Görevlendirme
                </a>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- DATATABLE KULLANMAYAN STATIK TABLO -->
                <table class="table table-hover" id="assignmentsTableStatic">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Şoför</th>
                            <th>Başlangıç</th>
                            <th>Bitiş</th>
                            <th>Durum</th>
                            <th class="text-center" style="min-width: 140px;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($data['assignments']) && !empty($data['assignments'])): ?>
                            <?php foreach($data['assignments'] as $assignment): ?>
                                <tr>
                                    <td><?php echo $assignment->id; ?></td>
                                    <td>
                                        <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $assignment->driver_id; ?>">
                                            <?php echo $assignment->driver_name; ?>
                                        </a>
                                    </td>
                                    <td><?php echo date('d.m.Y', strtotime($assignment->start_date)); ?></td>
                                    <td>
                                        <?php if($assignment->end_date): ?>
                                            <?php echo date('d.m.Y', strtotime($assignment->end_date)); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($assignment->status == 'Aktif'): ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php elseif($assignment->status == 'Tamamlandı'): ?>
                                            <span class="badge badge-info">Tamamlandı</span>
                                        <?php elseif($assignment->status == 'İptal'): ?>
                                            <span class="badge badge-danger">İptal</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary"><?php echo $assignment->status; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center" style="min-width: 140px; text-align: center !important; white-space: nowrap;">
                                        <a href="<?php echo URLROOT; ?>/assignments/show/<?php echo $assignment->id; ?>" class="btn btn-sm btn-info m-1" title="Görüntüle" style="display:inline-block !important; visibility:visible !important; opacity:1 !important;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/assignments/edit/<?php echo $assignment->id; ?>" class="btn btn-sm btn-primary m-1" title="Düzenle" style="display:inline-block !important; visibility:visible !important; opacity:1 !important;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger m-1 delete-btn" data-id="<?php echo $assignment->id; ?>" title="Sil" style="display:inline-block !important; visibility:visible !important; opacity:1 !important;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Bu araç için görevlendirme kaydı bulunmuyor</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bakım Kayıtları -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="m-0 font-weight-bold">
                <i class="fas fa-tools mr-2"></i> Bakım Kayıtları
                <a href="<?php echo URLROOT; ?>/maintenance/vehicleReport/<?php echo $data['vehicle']->id; ?>" class="btn btn-info btn-sm float-right ml-2">
                    <i class="fas fa-chart-bar mr-1"></i> Bakım Raporu
                </a>
                <a href="<?php echo URLROOT; ?>/maintenance/add" class="btn btn-success btn-sm float-right">
                    <i class="fas fa-plus mr-1"></i> Yeni Bakım Kaydı
                </a>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- DATATABLE KULLANMAYAN STATIK TABLO -->
                <table class="table table-hover" id="maintenanceTableStatic">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Bakım Türü</th>
                            <th>Başlangıç</th>
                            <th>Bitiş</th>
                            <th>Maliyet</th>
                            <th>Durum</th>
                            <th class="text-center" style="min-width: 140px;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(isset($data['maintenanceRecords']) && !empty($data['maintenanceRecords'])): 
                            foreach($data['maintenanceRecords'] as $record): 
                        ?>
                            <tr>
                                <td><?php echo $record->id; ?></td>
                                <td>
                                    <?php if($record->maintenance_type == 'Periyodik Bakım'): ?>
                                        <span class="badge badge-primary"><?php echo $record->maintenance_type; ?></span>
                                    <?php elseif($record->maintenance_type == 'Arıza'): ?>
                                        <span class="badge badge-danger"><?php echo $record->maintenance_type; ?></span>
                                    <?php elseif($record->maintenance_type == 'Lastik Değişimi'): ?>
                                        <span class="badge badge-warning"><?php echo $record->maintenance_type; ?></span>
                                    <?php elseif($record->maintenance_type == 'Yağ Değişimi'): ?>
                                        <span class="badge badge-info"><?php echo $record->maintenance_type; ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary"><?php echo $record->maintenance_type; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d.m.Y', strtotime($record->start_date)); ?></td>
                                <td>
                                    <?php if($record->end_date): ?>
                                        <?php echo date('d.m.Y', strtotime($record->end_date)); ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo number_format($record->cost, 2, ',', '.'); ?> ₺</td>
                                <td>
                                    <?php if($record->status == 'Planlandı'): ?>
                                        <span class="badge badge-info">Planlandı</span>
                                    <?php elseif($record->status == 'Devam Ediyor'): ?>
                                        <span class="badge badge-warning">Devam Ediyor</span>
                                    <?php elseif($record->status == 'Tamamlandı'): ?>
                                        <span class="badge badge-success">Tamamlandı</span>
                                    <?php elseif($record->status == 'İptal'): ?>
                                        <span class="badge badge-danger">İptal</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center" style="min-width: 140px; text-align: center !important; white-space: nowrap;">
                                    <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $record->id; ?>" class="btn btn-sm btn-info m-1" title="Görüntüle" style="display:inline-block !important; visibility:visible !important; opacity:1 !important;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/maintenance/edit/<?php echo $record->id; ?>" class="btn btn-sm btn-primary m-1" title="Düzenle" style="display:inline-block !important; visibility:visible !important; opacity:1 !important;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger m-1 delete-maintenance-btn" data-id="<?php echo $record->id; ?>" title="Sil" style="display:inline-block !important; visibility:visible !important; opacity:1 !important;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php 
                            endforeach; 
                        else: 
                        ?>
                            <tr>
                                <td colspan="7" class="text-center">Bu araç için henüz bakım kaydı bulunmuyor</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <!-- GIZLI DATATABLE - SADECE ARAMA VE FILTRELEME ICIN -->
                <div style="display:none">
                    <table class="data-table" id="assignmentsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Şoför</th>
                                <th>Başlangıç</th>
                                <th>Bitiş</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    
                    <table class="data-table" id="maintenanceTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bakım Türü</th>
                                <th>Başlangıç</th>
                                <th>Bitiş</th>
                                <th>Maliyet</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bakım Silme Modal -->
<div class="modal fade" id="deleteMaintenanceModal" tabindex="-1" aria-labelledby="deleteMaintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteMaintenanceModalLabel">Bakım Kaydını Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bu bakım kaydını silmek istediğinize emin misiniz? Bu işlem geri alınamaz.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form id="deleteMaintenance" action="" method="POST">
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Görevlendirme Silme Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Görevlendirmeyi Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bu görevlendirmeyi silmek istediğinize emin misiniz? Bu işlem geri alınamaz.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form id="deleteAssignment" action="" method="POST">
                    <button type="submit" class="btn btn-danger">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Görevlendirme silme modalı için işlemler
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            document.getElementById('deleteAssignment').action = '<?php echo URLROOT; ?>/assignments/delete/' + id;
            
            // Modal'ı açalım
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
    });
    
    // Bakım silme işlemleri
    document.querySelectorAll('.delete-maintenance-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            document.getElementById('deleteMaintenance').action = '<?php echo URLROOT; ?>/maintenance/delete/' + id;
            
            // Modal'ı açalım
            const maintenanceModal = new bootstrap.Modal(document.getElementById('deleteMaintenanceModal'));
            maintenanceModal.show();
        });
    });
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 