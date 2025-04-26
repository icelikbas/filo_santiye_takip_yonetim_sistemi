<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
// Yardımcı fonksiyonlar
function formatDate($date) {
    if (empty($date)) return '-';
    return date('d.m.Y', strtotime($date));
}

// Bugünün tarihini tanımla
$today = date('Y-m-d');
?>

<div class="container-fluid px-4">
    <!-- Başlık ve Üst Bilgi Alanı -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-tools me-2"></i> Bakım Takip Yönetimi
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/maintenance/add" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Yeni Bakım Kaydı
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('success'); ?>
    <?php flash('error'); ?>

    <?php if (isset($data['filteredStatus'])): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <strong><i class="fas fa-filter me-2"></i>Filtre aktif:</strong> 
        "<?php echo $data['filteredStatus']; ?>" durumundaki bakım kayıtları gösteriliyor.
        <a href="<?php echo URLROOT; ?>/maintenance" class="alert-link ms-2">Tüm kayıtları göster</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Bakım Durumu Dashboard Kartları -->
    <div class="row mb-4">
        <!-- Planlanmış Bakımlar -->
        <div class="col-lg-4 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-header bg-info text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-calendar-alt me-2"></i> Planlanmış Bakımlar</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php 
                        $plannedCount = 0;
                        foreach ($data['records'] as $record): 
                            if ($record->status == 'Planlandı' && $plannedCount < 5):
                                $plannedCount++;
                        ?>
                            <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $record->id; ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo $record->plate_number; ?></strong>
                                        <div class="small text-muted">
                                            <?php echo $record->maintenance_type; ?>
                                            <?php if (!empty($record->planning_date)): ?>
                                                <span class="ms-2">
                                                    <i class="fas fa-calendar"></i> <?php echo date('d.m.Y', strtotime($record->planning_date)); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <span class="badge bg-info rounded-pill">Planlandı</span>
                                </div>
                            </a>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if ($plannedCount == 0):
                        ?>
                            <div class="list-group-item text-center text-muted py-4">
                                <i class="fas fa-info-circle mb-2 fa-2x"></i>
                                <p class="mb-0">Planlanmış bakım bulunmamaktadır.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($plannedCount > 0): ?>
                        <div class="card-footer text-center">
                            <a href="javascript:void(0)" onclick="filterByStatus('Planlandı')" class="btn btn-sm btn-info">
                                Tümünü Gör (<?php echo $data['statusCounts']['Planlandı'] ?? $plannedCount; ?>)
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Servisteki Araçlar -->
        <div class="col-lg-4 mb-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-header bg-warning text-dark py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-wrench me-2"></i> Servisteki Araçlar</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php 
                        $inProgressCount = 0;
                        foreach ($data['records'] as $record): 
                            if ($record->status == 'Devam Ediyor' && $inProgressCount < 5):
                                $inProgressCount++;
                        ?>
                            <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $record->id; ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo $record->plate_number; ?></strong>
                                        <div class="small text-muted">
                                            <?php echo $record->maintenance_type; ?>
                                            <?php if (!empty($record->start_date)): ?>
                                                <span class="ms-2">
                                                    <i class="fas fa-clock"></i> <?php echo date('d.m.Y', strtotime($record->start_date)); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning text-dark rounded-pill">Serviste</span>
                                </div>
                            </a>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if ($inProgressCount == 0):
                        ?>
                            <div class="list-group-item text-center text-muted py-4">
                                <i class="fas fa-tools mb-2 fa-2x"></i>
                                <p class="mb-0">Bakımda olan araç bulunmamaktadır.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($inProgressCount > 0): ?>
                        <div class="card-footer text-center">
                            <a href="javascript:void(0)" onclick="filterByStatus('Devam Ediyor')" class="btn btn-sm btn-warning text-dark">
                                Tümünü Gör (<?php echo $data['statusCounts']['Devam Ediyor'] ?? $inProgressCount; ?>)
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Tamamlanan Bakımlar -->
        <div class="col-lg-4 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-check-circle me-2"></i> Tamamlanan Bakımlar</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php 
                        $completedCount = 0;
                        foreach ($data['records'] as $record): 
                            if ($record->status == 'Tamamlandı' && $completedCount < 5):
                                $completedCount++;
                        ?>
                            <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $record->id; ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo $record->plate_number; ?></strong>
                                        <div class="small text-muted">
                                            <?php echo $record->maintenance_type; ?>
                                            <?php if (!empty($record->end_date)): ?>
                                                <span class="ms-2">
                                                    <i class="fas fa-calendar-check"></i> <?php echo date('d.m.Y', strtotime($record->end_date)); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <span class="badge bg-success rounded-pill">Tamamlandı</span>
                                </div>
                            </a>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if ($completedCount == 0):
                        ?>
                            <div class="list-group-item text-center text-muted py-4">
                                <i class="fas fa-clipboard-check mb-2 fa-2x"></i>
                                <p class="mb-0">Tamamlanmış bakım bulunmamaktadır.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($completedCount > 0): ?>
                        <div class="card-footer text-center">
                            <a href="javascript:void(0)" onclick="filterByStatus('Tamamlandı')" class="btn btn-sm btn-success">
                                Tümünü Gör (<?php echo $data['statusCounts']['Tamamlandı'] ?? $completedCount; ?>)
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bakım Kayıtları Özeti -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Toplam Bakım</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count($data['records']); ?> <small>Adet</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tamamlanan Bakımlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo isset($data['status_counts']) && isset($data['status_counts']['Tamamlandı']) ? $data['status_counts']['Tamamlandı'] : 0; ?> <small>Adet</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Devam Eden Bakımlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo isset($data['status_counts']) && isset($data['status_counts']['Devam Ediyor']) ? $data['status_counts']['Devam Ediyor'] : 0; ?> <small>Adet</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Toplam Maliyet</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($data['totalCost'], 2, ',', '.'); ?> <small>₺</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bakım Türü Dağılımı ve Servis Sağlayıcılar -->
    <div class="row mb-4">
        <!-- Bakım Türü Dağılımı -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Bakım Türü Dağılımı</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="maintenanceTypeChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <?php foreach($data['typeDistribution'] as $type): ?>
                            <span class="mr-2">
                                <?php 
                                    $color = '';
                                    switch($type->maintenance_type) {
                                        case 'Periyodik Bakım':
                                            $color = 'primary';
                                            break;
                                        case 'Arıza':
                                            $color = 'danger';
                                            break;
                                        case 'Lastik Değişimi':
                                            $color = 'warning';
                                            break;
                                        case 'Yağ Değişimi':
                                            $color = 'info';
                                            break;
                                        default:
                                            $color = 'secondary';
                                    }
                                ?>
                                <i class="fas fa-circle text-<?php echo $color; ?>"></i> <?php echo $type->maintenance_type; ?> (<?php echo $type->count; ?>)
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Servis Sağlayıcılar -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">En Çok Kullanılan Servis Sağlayıcılar</h6>
                </div>
                <div class="card-body">
                    <?php if(empty($data['serviceProviders'])): ?>
                        <p class="text-center">Henüz servis sağlayıcı kaydedilmemiş.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Servis Sağlayıcı</th>
                                        <th>Bakım Sayısı</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 0;
                                    foreach($data['serviceProviders'] as $provider => $count): 
                                        if($i < 5): // En çok kullanılan 5 servis sağlayıcı
                                    ?>
                                        <tr>
                                            <td><?php echo $provider; ?></td>
                                            <td><?php echo $count; ?></td>
                                        </tr>
                                    <?php 
                                        endif;
                                        $i++;
                                    endforeach; 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Yaklaşan Bakımlar -->
    <div class="row mb-4">
        <!-- Tarihe Göre Yaklaşan Bakımlar -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Planlanan Bakımlar</h6>
                </div>
                <div class="card-body">
                    <?php if(empty($data['upcomingMaintenances'])): ?>
                        <p class="text-center">Planlanmış bakım bulunmuyor.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Araç</th>
                                        <th>Bakım Türü</th>
                                        <th>Planlama Tarihi</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['upcomingMaintenances'] as $maintenance): ?>
                                        <tr>
                                            <td><?php echo $maintenance->plate_number; ?></td>
                                            <td><?php echo $maintenance->maintenance_type; ?></td>
                                            <td><?php echo !empty($maintenance->planning_date) ? date('d.m.Y', strtotime($maintenance->planning_date)) : '-'; ?></td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $maintenance->id; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Kilometreye Göre Yaklaşan Bakımlar -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Yaklaşan Kilometre Bakımları (1000 KM Kalan)</h6>
                </div>
                <div class="card-body">
                    <?php if(empty($data['upcomingKmMaintenances'])): ?>
                        <p class="text-center">Yaklaşan kilometre bakımı bulunmuyor.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Araç</th>
                                        <th>Şu Anki KM</th>
                                        <th>Bakım KM</th>
                                        <th>Kalan KM</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['upcomingKmMaintenances'] as $maintenance): ?>
                                        <tr>
                                            <td><?php echo $maintenance->plate_number; ?></td>
                                            <td><?php echo number_format($maintenance->km_reading, 0, ',', '.'); ?></td>
                                            <td><?php echo number_format($maintenance->next_maintenance_km, 0, ',', '.'); ?></td>
                                            <td>
                                                <?php 
                                                    $kmLeft = $maintenance->next_maintenance_km - $maintenance->km_reading;
                                                    echo number_format($kmLeft, 0, ',', '.');
                                                ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $maintenance->id; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Saate Göre Yaklaşan Bakımlar -->
    <div class="row mb-4">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Yaklaşan Çalışma Saati Bakımları (50 Saat Kalan)</h6>
                </div>
                <div class="card-body">
                    <?php if(empty($data['upcomingHourMaintenances'])): ?>
                        <p class="text-center">Yaklaşan çalışma saati bakımı bulunmuyor.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Araç</th>
                                        <th>Şu Anki Saat</th>
                                        <th>Bakım Saati</th>
                                        <th>Kalan Saat</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['upcomingHourMaintenances'] as $maintenance): ?>
                                        <tr>
                                            <td><?php echo $maintenance->plate_number; ?></td>
                                            <td><?php echo number_format($maintenance->hour_reading, 0, ',', '.'); ?> saat</td>
                                            <td><?php echo number_format($maintenance->next_maintenance_hours, 0, ',', '.'); ?> saat</td>
                                            <td>
                                                <?php 
                                                    $hoursLeft = $maintenance->next_maintenance_hours - $maintenance->hour_reading;
                                                    echo number_format($hoursLeft, 0, ',', '.') . ' saat';
                                                ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $maintenance->id; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Arama ve Filtreleme Bölümü -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-search me-2"></i>Arama ve Filtreleme</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="searchInput"><strong>Hızlı Arama:</strong></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Plaka, bakım türü...">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="typeFilter"><strong>Bakım Türü:</strong></label>
                    <select class="form-control" id="typeFilter">
                        <option value="">Tüm Türler</option>
                        <option value="Periyodik Bakım">Periyodik Bakım</option>
                        <option value="Arıza">Arıza</option>
                        <option value="Lastik Değişimi">Lastik Değişimi</option>
                        <option value="Yağ Değişimi">Yağ Değişimi</option>
                        <option value="Diğer">Diğer</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="statusFilter"><strong>Durum:</strong></label>
                    <select class="form-control" id="statusFilter">
                        <option value="">Tüm Durumlar</option>
                        <option value="Planlandı">Planlandı</option>
                        <option value="Devam Ediyor">Devam Ediyor</option>
                        <option value="Tamamlandı">Tamamlandı</option>
                        <option value="İptal">İptal</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="dateFilter"><strong>Tarih Aralığı:</strong></label>
                    <select class="form-control" id="dateFilter">
                        <option value="">Tüm Tarihler</option>
                        <option value="today">Bugün</option>
                        <option value="week">Bu Hafta</option>
                        <option value="month">Bu Ay</option>
                        <option value="year">Bu Yıl</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-end">
                    <button id="resetFilters" class="btn btn-secondary">
                        <i class="fas fa-sync-alt me-1"></i> Filtreleri Sıfırla
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bakım Kayıtları Tablosu -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-list me-2"></i>Bakım Kayıtları</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="maintenanceTable" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Plaka</th>
                            <th>Bakım Türü</th>
                            <th>Planlama Tarihi</th>
                            <th>Başlangıç Tarihi</th>
                            <th>Bitiş Tarihi</th>
                            <th>KM</th>
                            <th>Maliyet</th>
                            <th>Durum</th>
                            <th>Servis Sağlayıcı</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['records'] as $record) : ?>
                            <tr>
                                <td><?php echo $record->id; ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $record->vehicle_id; ?>" class="fw-bold text-decoration-none">
                                        <?php echo $record->plate_number; ?>
                                    </a>
                                </td>
                                <td>
                                    <?php
                                    $typeClass = 'secondary';
                                    switch ($record->maintenance_type) {
                                        case 'Periyodik Bakım':
                                            $typeClass = 'info';
                                            break;
                                        case 'Arıza':
                                            $typeClass = 'danger';
                                            break;
                                        case 'Lastik Değişimi':
                                            $typeClass = 'warning';
                                            break;
                                        case 'Yağ Değişimi':
                                            $typeClass = 'primary';
                                            break;
                                        default:
                                            $typeClass = 'secondary';
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $typeClass; ?>"><?php echo $record->maintenance_type; ?></span>
                                </td>
                                <td><?php echo !empty($record->planning_date) ? formatDate($record->planning_date) : '-'; ?></td>
                                <td><?php echo !empty($record->start_date) ? formatDate($record->start_date) : '-'; ?></td>
                                <td><?php echo !empty($record->end_date) ? formatDate($record->end_date) : '-'; ?></td>
                                <td><?php echo number_format($record->km_reading, 0, ',', '.'); ?> km</td>
                                <td><?php echo number_format($record->cost, 2, ',', '.'); ?> ₺</td>
                                <td>
                                    <?php
                                    $statusClass = 'secondary';
                                    switch ($record->status) {
                                        case 'Planlandı':
                                            $statusClass = 'primary';
                                            break;
                                        case 'Devam Ediyor':
                                            $statusClass = 'warning';
                                            break;
                                        case 'Tamamlandı':
                                            $statusClass = 'success';
                                            break;
                                        case 'İptal':
                                            $statusClass = 'danger';
                                            break;
                                        default:
                                            $statusClass = 'secondary';
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $record->status; ?></span>
                                </td>
                                <td><?php echo $record->service_provider; ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $record->id; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/maintenance/edit/<?php echo $record->id; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-record" data-id="<?php echo $record->id; ?>">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Silme İşlemi Onay Modalı -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Bakım Kaydı Silme Onayı</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Uyarı:</strong> Bu bakım kaydını silmek istediğinize emin misiniz? Bu işlem geri alınamaz.
                    </div>
                    <p>Bakım kaydı silindiğinde, bu kayıtla ilgili tüm bilgiler kalıcı olarak silinecektir.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <a href="#" id="confirmDelete" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Evet, Sil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js ve DataTables için JavaScript -->
    <script>
        // Durum filtresi için yardımcı fonksiyon
        function filterByStatus(status) {
            // Durum filtresini ayarla
            $('#statusFilter').val(status);
            
            // DataTable'ı güncelle
            $('#maintenanceTable').DataTable().column(8).search(status).draw();
            
            // Sayfayı filtreleme bölümüne kaydır
            $('html, body').animate({
                scrollTop: $("#maintenanceTable").offset().top - 100
            }, 500);
        }
    
        document.addEventListener('DOMContentLoaded', function() {
            // jQuery ve DataTables yüklü mü kontrol et
            if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
                console.error('jQuery veya DataTables yüklenmemiş!');
                return;
            }
            
            // Eğer zaten DataTable örneği varsa, onu yok et
            if ($.fn.dataTable.isDataTable('#maintenanceTable')) {
                $('#maintenanceTable').DataTable().destroy();
            }
            
            // DataTables'ı doğrudan başlat
            const maintenanceTable = $('#maintenanceTable').DataTable({
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
                },
                dom: 'Bfrtip',
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10', '25', '50', 'Tümü']
                ],
                buttons: [
                    'copy', 'excel', 'pdf', 'print', 'colvis'
                ],
                order: [[0, 'desc']], // ID'ye göre azalan sıralama
                pageLength: 10,
                columnDefs: [
                    { targets: [9], orderable: false }, // İşlemler sütunu (10. sütun) sıralanabilir olmasın
                    { 
                        targets: [8], // Durum sütununa özel render fonksiyonu
                        render: function(data, type, row) {
                            // Veri türü arama veya sıralama ise sadece metin içeriğini dön
                            if (type === 'filter' || type === 'sort') {
                                // HTML içinden durum metnini çıkar
                                var div = document.createElement('div');
                                div.innerHTML = data;
                                return div.textContent.trim();
                            }
                            // Diğer türlerde normal HTML'i döndür (görüntüleme için)
                            return data;
                        }
                    }
                ]
            });
            
            // Harici arama kutusu ile DataTables entegrasyonu
            $('#searchInput').on('keyup', function() {
                maintenanceTable.search(this.value).draw();
            });
            
            // Bakım türü filtresi
            $('#typeFilter').on('change', function() {
                maintenanceTable.column(2).search(this.value).draw();
            });
            
            // Durum filtresi
            $('#statusFilter').on('change', function() {
                var selectedStatus = this.value;
                maintenanceTable.column(8).search(selectedStatus).draw();
            });
            
            // Tarih filtresi
            $('#dateFilter').on('change', function() {
                var value = $(this).val();
                var dateSearch = '';
                
                if (value === 'today') {
                    var today = new Date().toLocaleDateString('tr-TR');
                    dateSearch = today;
                } else if (value === 'week') {
                    // Bu hafta içindeki tarihler
                    maintenanceTable.column(3).search('').draw();
                    
                    var today = new Date();
                    var firstDay = new Date(today.setDate(today.getDate() - today.getDay())); // Haftanın başlangıcı (Pazar)
                    var lastDay = new Date(today.setDate(today.getDate() - today.getDay() + 6)); // Haftanın sonu (Cumartesi)
                    
                    // Tarihleri uygun formata çevir (tarih sütununda hangi format kullanılıyorsa)
                    var firstDayStr = firstDay.toLocaleDateString('tr-TR');
                    var lastDayStr = lastDay.toLocaleDateString('tr-TR');
                    
                    // Özel filtreleme fonksiyonu
                    $.fn.dataTable.ext.search.push(
                        function(settings, data, dataIndex) {
                            var date = new Date(data[3].split('.').reverse().join('-')); // DD.MM.YYYY -> YYYY-MM-DD
                            return (date >= firstDay && date <= lastDay);
                        }
                    );
                    
                    maintenanceTable.draw();
                    // Sonraki filtreleme için array'i temizle
                    $.fn.dataTable.ext.search.pop();
                    return;
                } else if (value === 'month') {
                    // Bu ay içindeki tarihler
                    maintenanceTable.column(3).search('').draw();
                    
                    var date = new Date();
                    var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
                    var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                    
                    // Özel filtreleme fonksiyonu
                    $.fn.dataTable.ext.search.push(
                        function(settings, data, dataIndex) {
                            var date = new Date(data[3].split('.').reverse().join('-')); // DD.MM.YYYY -> YYYY-MM-DD
                            return (date >= firstDay && date <= lastDay);
                        }
                    );
                    
                    maintenanceTable.draw();
                    // Sonraki filtreleme için array'i temizle
                    $.fn.dataTable.ext.search.pop();
                    return;
                } else if (value === 'year') {
                    // Bu yıl içindeki tarihler
                    maintenanceTable.column(3).search('').draw();
                    
                    var date = new Date();
                    var firstDay = new Date(date.getFullYear(), 0, 1);
                    var lastDay = new Date(date.getFullYear(), 11, 31);
                    
                    // Özel filtreleme fonksiyonu
                    $.fn.dataTable.ext.search.push(
                        function(settings, data, dataIndex) {
                            var date = new Date(data[3].split('.').reverse().join('-')); // DD.MM.YYYY -> YYYY-MM-DD
                            return (date >= firstDay && date <= lastDay);
                        }
                    );
                    
                    maintenanceTable.draw();
                    // Sonraki filtreleme için array'i temizle
                    $.fn.dataTable.ext.search.pop();
                    return;
                }
                
                maintenanceTable.column(3).search(dateSearch).draw();
            });
            
            // Filtreleri sıfırla
            $('#resetFilters').on('click', function() {
                $('#searchInput').val('');
                $('#typeFilter').val('');
                $('#statusFilter').val('');
                $('#dateFilter').val('');
                maintenanceTable.search('').columns().search('').draw();
            });
            
            // Tooltip'leri etkinleştir
            if (typeof bootstrap !== 'undefined' && typeof bootstrap.Tooltip !== 'undefined') {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
            
            // Chart.js yüklü mü kontrol et
            if (typeof Chart === 'undefined') {
                console.error('Chart.js yüklenmemiş!');
                return;
            }
            
            // Bakım Türü Chart
            var ctx = document.getElementById("maintenanceTypeChart");
            if (ctx) {
                var typeLabels = [];
                var typeData = [];
                var typeColors = [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(201, 203, 207, 0.7)'
                ];
                
                <?php if (!empty($data['maintenance_types'])): ?>
                    <?php foreach ($data['maintenance_types'] as $type): ?>
                        typeLabels.push("<?php echo $type->maintenance_type; ?>");
                        typeData.push(<?php echo $type->count; ?>);
                    <?php endforeach; ?>
                <?php endif; ?>
                
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: typeLabels,
                        datasets: [{
                            data: typeData,
                            backgroundColor: typeColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12
                                }
                            },
                            title: {
                                display: true,
                                text: 'Bakım Tipi Dağılımı'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.raw || 0;
                                        var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        var percentage = Math.round((value / total) * 100);
                                        return label + ': ' + value + ' (%' + percentage + ')';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Durum Dağılımı Chart
            var statusCtx = document.getElementById("statusChart");
            if (statusCtx) {
                var statusLabels = [];
                var statusData = [];
                var statusColors = {
                    'Planlandı': 'rgba(54, 162, 235, 0.7)',
                    'Devam Ediyor': 'rgba(255, 206, 86, 0.7)',
                    'Tamamlandı': 'rgba(75, 192, 192, 0.7)',
                    'İptal': 'rgba(255, 99, 132, 0.7)'
                };
                
                var chartColors = [];
                
                <?php if (!empty($data['status_counts'])): ?>
                    <?php foreach ($data['status_counts'] as $status => $count): ?>
                        statusLabels.push("<?php echo $status; ?>");
                        statusData.push(<?php echo $count; ?>);
                        chartColors.push(statusColors['<?php echo $status; ?>'] || 'rgba(201, 203, 207, 0.7)');
                    <?php endforeach; ?>
                <?php endif; ?>
                
                new Chart(statusCtx, {
                    type: 'pie',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            data: statusData,
                            backgroundColor: chartColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12
                                }
                            },
                            title: {
                                display: true,
                                text: 'Bakım Durumu Dağılımı'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.raw || 0;
                                        var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        var percentage = Math.round((value / total) * 100);
                                        return label + ': ' + value + ' (%' + percentage + ')';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });

        // Silme işlemi için modal - delegasyon yöntemi kullanarak
        $(document).on('click', '.delete-record', function() {
            var id = $(this).data('id');
            $('#confirmDelete').attr('href', '<?php echo URLROOT; ?>/maintenance/confirmDelete/' + id);
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
    </script>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 