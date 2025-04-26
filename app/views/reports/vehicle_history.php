<?php 
// Basit güvenlik kontrolü
if (!defined('APPROOT')) {
    echo "Doğrudan erişim engellendi!"; 
    exit;
}

// Değişkenleri kontrol et ve logla
echo "<!-- DEBUG: vehicle_history.php yükleniyor -->";
error_log("*** VIEW DOSYASI YÜKLENDİ: vehicle_history.php ***");

if (isset($data) && is_array($data)) {
    error_log("View data içeriği: " . implode(", ", array_keys($data)));
} else {
    error_log("View için data tanımlanmamış veya dizi değil");
}

require APPROOT . '/views/inc/header.php'; 
?>

<?php 
// Debug bilgisi
error_log("vehicle_history.php view yükleniyor");
error_log("View data içeriği: " . print_r(array_keys($data), true));
if (isset($data['vehicle'])) {
    error_log("Araç verisi var: " . print_r($data['vehicle'], true));
} else {
    error_log("Araç verisi YOK!");
}
?>

<?php if(isset($data['vehicle']) && $data['vehicle']): ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-car"></i> Araç Geçmiş Detayları: <?php echo $data['vehicle']->brand . ' ' . $data['vehicle']->model . ' (' . $data['vehicle']->plate_number . ')'; ?></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="#" onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-print"></i> Yazdır
                </a>
                <a href="<?php echo URLROOT; ?>/reports/vehicles" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Geri
                </a>
            </div>
        </div>
    </div>
    
    <!-- Araç Bilgileri Kartı -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-info-circle me-2"></i> <strong>Araç Bilgileri</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <h6 class="text-muted">Plaka</h6>
                    <h5><?php echo $data['vehicle']->plate_number; ?></h5>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="text-muted">Marka / Model</h6>
                    <h5><?php echo $data['vehicle']->brand . ' ' . $data['vehicle']->model; ?></h5>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="text-muted">Üretim Yılı</h6>
                    <h5><?php echo $data['vehicle']->year; ?></h5>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="text-muted">Araç Tipi</h6>
                    <h5><?php echo $data['vehicle']->vehicle_type; ?></h5>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="text-muted">Durum</h6>
                    <h5>
                        <?php if ($data['vehicle']->status == 'Aktif') : ?>
                            <span class="badge bg-success">Aktif</span>
                        <?php elseif ($data['vehicle']->status == 'Bakımda') : ?>
                            <span class="badge bg-warning">Bakımda</span>
                        <?php else : ?>
                            <span class="badge bg-danger">Pasif</span>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="text-muted">Kilometre</h6>
                    <h5><?php echo number_format($data['vehicle']->current_km ?? 0, 0, ',', '.'); ?> km</h5>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="text-muted">Çalışma Saati</h6>
                    <h5><?php echo number_format($data['vehicle']->current_hours ?? 0, 1, ',', '.'); ?> saat</h5>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="text-muted">Şirket</h6>
                    <h5><?php echo isset($data['company']) && $data['company'] ? $data['company']->company_name : 'Belirtilmemiş'; ?></h5>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtreler -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light"> 
            <i class="fas fa-filter text-primary me-2"></i> <strong>Geçmiş Türü</strong>
        </div>
        <div class="card-body pb-0">
            <ul class="nav nav-tabs" id="historyTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">
                        <i class="fas fa-th-list me-1"></i> Tümü
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="fuel-tab" data-bs-toggle="tab" href="#fuel" role="tab" aria-controls="fuel" aria-selected="false">
                        <i class="fas fa-gas-pump me-1"></i> Yakıt Geçmişi
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="assignments-tab" data-bs-toggle="tab" href="#assignments" role="tab" aria-controls="assignments" aria-selected="false">
                        <i class="fas fa-tasks me-1"></i> Görevlendirmeler
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- İçerik Sekmeleri -->
    <div class="tab-content" id="historyTabContent">
        <!-- Tüm Geçmiş -->
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <i class="fas fa-history text-primary me-2"></i> <strong>Tüm Geçmiş</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered data-table" id="allHistoryTable">
                            <thead>
                                <tr>
                                    <th>Tarih</th>
                                    <th>İşlem Türü</th>
                                    <th>Detay</th>
                                    <th>Miktar/Süre</th>
                                    <th>Not</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($data['history']) && !empty($data['history'])): ?>
                                    <?php foreach($data['history'] as $history): ?>
                                        <?php if(isset($history->type) && ($history->type == 'fuel' || $history->type == 'assignment')): ?>
                                        <tr>
                                            <td><?php echo isset($history->date) ? date('d.m.Y', strtotime($history->date)) : '-'; ?></td>
                                            <td>
                                                <?php if($history->type == 'fuel'): ?>
                                                    <span class="badge bg-primary">Yakıt</span>
                                                <?php elseif($history->type == 'assignment'): ?>
                                                    <span class="badge bg-info">Görevlendirme</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($history->type == 'fuel' && isset($history->details)): ?>
                                                    <?php echo isset($history->details->fuel_type) ? $history->details->fuel_type : ''; ?> Yakıt Alımı
                                                <?php elseif($history->type == 'assignment' && isset($history->details)): ?>
                                                    <?php echo isset($history->details->assignment_name) ? $history->details->assignment_name : 'Görevlendirme'; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($history->type == 'fuel' && isset($history->details)): ?>
                                                    <?php echo isset($history->details->amount) ? number_format($history->details->amount, 2, ',', '.') . ' lt' : '-'; ?>
                                                <?php elseif($history->type == 'assignment'): ?>
                                                    <?php echo isset($history->duration) ? $history->duration . ' gün' : 'Devam ediyor'; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($history->type == 'fuel' && isset($history->details)): ?>
                                                    <?php echo isset($history->details->notes) ? $history->details->notes : '-'; ?>
                                                <?php elseif($history->type == 'assignment' && isset($history->details)): ?>
                                                    <?php echo isset($history->details->notes) ? $history->details->notes : '-'; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Geçmiş kaydı bulunamadı.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Yakıt Geçmişi -->
        <div class="tab-pane fade" id="fuel" role="tabpanel" aria-labelledby="fuel-tab">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-gas-pump me-2"></i> <strong>Yakıt Geçmişi</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered data-table" id="fuelHistoryTable">
                            <thead>
                                <tr>
                                    <th>Tarih</th>
                                    <th>Yakıt Tipi</th>
                                    <th>Miktar (lt)</th>
                                    <th>Kilometre</th>
                                    <th>Saat</th>
                                    <th>Depo</th>
                                    <th>Sürücü</th>
                                    <th>Notlar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($data['fuelRecords']) && !empty($data['fuelRecords'])): ?>
                                    <?php foreach($data['fuelRecords'] as $fuel): ?>
                                        <tr>
                                            <td><?php echo isset($fuel->date) ? date('d.m.Y', strtotime($fuel->date)) : '-'; ?></td>
                                            <td><?php echo $fuel->fuel_type ?? '-'; ?></td>
                                            <td><?php echo isset($fuel->amount) ? number_format($fuel->amount, 2, ',', '.') . ' lt' : '-'; ?></td>
                                            <td><?php echo isset($fuel->km_reading) ? number_format($fuel->km_reading, 0, ',', '.') . ' km' : '-'; ?></td>
                                            <td><?php echo isset($fuel->hour_reading) ? number_format($fuel->hour_reading, 1, ',', '.') . ' saat' : '-'; ?></td>
                                            <td><?php echo $fuel->tank_name ?? '-'; ?></td>
                                            <td><?php echo $fuel->driver_name ?? 'Belirtilmemiş'; ?></td>
                                            <td><?php echo $fuel->notes ?? '-'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Yakıt kaydı bulunamadı.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Görevlendirmeler -->
        <div class="tab-pane fade" id="assignments" role="tabpanel" aria-labelledby="assignments-tab">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-tasks me-2"></i> <strong>Görevlendirmeler</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered data-table" id="assignmentsTable">
                            <thead>
                                <tr>
                                    <th>Başlangıç</th>
                                    <th>Bitiş</th>
                                    <th>Sürücü</th>
                                    <th>Görev</th>
                                    <th>Lokasyon</th>
                                    <th>Durum</th>
                                    <th>Açıklama</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($data['assignments']) && !empty($data['assignments'])): ?>
                                    <?php foreach($data['assignments'] as $assignment): ?>
                                        <tr>
                                            <td><?php echo isset($assignment->start_date) ? date('d.m.Y', strtotime($assignment->start_date)) : '-'; ?></td>
                                            <td><?php echo isset($assignment->end_date) ? date('d.m.Y', strtotime($assignment->end_date)) : '-'; ?></td>
                                            <td><?php echo $assignment->driver_name ?? '-'; ?></td>
                                            <td><?php echo $assignment->assignment_name ?? '-'; ?></td>
                                            <td><?php echo $assignment->location ?? '-'; ?></td>
                                            <td>
                                                <?php if (isset($assignment->status)): ?>
                                                    <?php if ($assignment->status == 'Aktif') : ?>
                                                        <span class="badge bg-success">Aktif</span>
                                                    <?php elseif ($assignment->status == 'Tamamlandı') : ?>
                                                        <span class="badge bg-primary">Tamamlandı</span>
                                                    <?php elseif ($assignment->status == 'İptal') : ?>
                                                        <span class="badge bg-danger">İptal</span>
                                                    <?php else : ?>
                                                        <span class="badge bg-secondary"><?php echo $assignment->status; ?></span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Belirtilmemiş</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $assignment->notes ?? '-'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Görevlendirme kaydı bulunamadı.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="container-fluid">
    <div class="alert alert-danger mt-4">
        <h4 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Hata!</h4>
        <p>Araç bilgileri yüklenemedi. Lütfen tekrar deneyiniz veya sistem yöneticisiyle iletişime geçiniz.</p>
        <hr>
        <p class="mb-0">
            <a href="<?php echo URLROOT; ?>/reports/vehicles" class="btn btn-outline-danger">
                <i class="fas fa-arrow-left"></i> Araç Listesine Dön
            </a>
        </p>
    </div>
</div>
<?php endif; ?>

<?php require APPROOT . '/views/inc/footer.php'; ?> 