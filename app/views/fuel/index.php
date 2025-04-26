<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1 class="mb-0"><i class="fas fa-gas-pump me-2"></i> Yakıt Kayıtları</h1>
    </div>
</div>

<?php flash('success'); ?>
<?php flash('error'); ?>

<!-- Ana İstatistik Kartları -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100 border-start border-primary border-5">
            <div class="card-header bg-primary text-white text-center">
                <h5 class="mb-0"><i class="fas fa-truck-moving me-2"></i> Toplam Araç</h5>
            </div>
            <div class="card-body text-center d-flex align-items-center justify-content-center">
                <h2 class="mb-0 display-5"><?php echo $data['totalVehicles']; ?> <small class="text-muted">adet</small></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100 border-start border-danger border-5">
            <div class="card-header bg-danger text-white text-center">
                <h5 class="mb-0"><i class="fas fa-gas-pump me-2"></i> Toplam Yakıt</h5>
            </div>
            <div class="card-body text-center d-flex align-items-center justify-content-center">
                <h2 class="mb-0 display-5"><?php echo number_format($data['totalAmount'], 0, ',', '.'); ?> <small class="text-muted">Lt</small></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100 border-start border-success border-5">
            <div class="card-header bg-success text-white text-center">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> Günlük Ort.</h5>
            </div>
            <div class="card-body text-center d-flex align-items-center justify-content-center">
                <h2 class="mb-0 display-5"><?php echo number_format($data['totalAmount'] / 30, 0, ',', '.'); ?> <small class="text-muted">Lt</small></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm h-100 border-start border-info border-5">
            <div class="card-header bg-info text-white text-center">
                <h5 class="mb-0"><i class="fas fa-calendar me-2"></i> Bu Ay</h5>
            </div>
            <div class="card-body text-center d-flex align-items-center justify-content-center">
                <h2 class="mb-0 display-5"><?php echo number_format($data['monthlyAmount'], 0, ',', '.'); ?> <small class="text-muted">Lt</small></h2>
            </div>
        </div>
    </div>
</div>

<!-- Hızlı Erişim Butonları -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="<?php echo URLROOT; ?>/fuel/add" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus-circle me-2"></i> Yeni Yakıt Kaydı
                    </a>
                    <a href="<?php echo URLROOT; ?>/reports/fuel" class="btn btn-success btn-lg">
                        <i class="fas fa-chart-bar me-2"></i> Yakıt Raporu
                    </a>
                    <a href="<?php echo URLROOT; ?>/tanks" class="btn btn-warning btn-lg">
                        <i class="fas fa-database me-2"></i> Tank Durumu
                    </a>
                    <a href="<?php echo URLROOT; ?>/vehicles" class="btn btn-info btn-lg">
                        <i class="fas fa-truck me-2"></i> Araç Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtreleme ve Hızlı Erişim Sekmesi -->
<div class="card shadow mb-4">
    <div class="card-header bg-light">
        <ul class="nav nav-tabs card-header-tabs" id="fuelTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="filters-tab" data-bs-toggle="tab" data-bs-target="#filters" type="button" role="tab" aria-controls="filters" aria-selected="true">
                    <i class="fas fa-filter me-1"></i> Filtreleme
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab" aria-controls="reports" aria-selected="false">
                    <i class="fas fa-chart-bar me-1"></i> Hızlı Raporlar
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="quick-filters-tab" data-bs-toggle="tab" data-bs-target="#quick-filters" type="button" role="tab" aria-controls="quick-filters" aria-selected="false">
                    <i class="fas fa-bolt me-1"></i> Hızlı Filtreler
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="fuelTabsContent">
            <!-- Filtreleme Sekmesi -->
            <div class="tab-pane fade show active" id="filters" role="tabpanel" aria-labelledby="filters-tab">
                <form action="<?php echo URLROOT; ?>/fuel/filter" method="post">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="vehicle_id" class="form-label">Araç</label>
                            <select name="vehicle_id" id="vehicle_id" class="form-select">
                                <option value="">Tüm Araçlar</option>
                                <?php foreach($data['vehicles'] as $vehicle) : ?>
                                    <option value="<?php echo $vehicle->id; ?>" <?php echo ($data['filters']['vehicle_id'] == $vehicle->id) ? 'selected' : ''; ?>>
                                        <?php echo $vehicle->vehicle_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="driver_id" class="form-label">Sürücü</label>
                            <select name="driver_id" id="driver_id" class="form-select">
                                <option value="">Tüm Sürücüler</option>
                                <?php foreach($data['drivers'] as $driver) : ?>
                                    <option value="<?php echo $driver->id; ?>" <?php echo ($data['filters']['driver_id'] == $driver->id) ? 'selected' : ''; ?>>
                                        <?php echo $driver->full_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="dispenser_id" class="form-label">Yakıt Dağıtan Personel</label>
                            <select name="dispenser_id" id="dispenser_id" class="form-select">
                                <option value="">Tüm Personeller</option>
                                <?php if(isset($data['users'])): ?>
                                    <?php foreach($data['users'] as $user) : ?>
                                        <option value="<?php echo $user->id; ?>" <?php echo (isset($data['filters']['dispenser_id']) && $data['filters']['dispenser_id'] == $user->id) ? 'selected' : ''; ?>>
                                            <?php echo $user->name . (isset($user->surname) ? ' ' . $user->surname : ''); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tank_id" class="form-label">Tank</label>
                            <select name="tank_id" id="tank_id" class="form-select">
                                <option value="">Tüm Tanklar</option>
                                <?php foreach($data['tanks'] as $tank) : ?>
                                    <option value="<?php echo $tank->id; ?>" <?php echo ($data['filters']['tank_id'] == $tank->id) ? 'selected' : ''; ?>>
                                        <?php echo $tank->name; ?> (<?php echo $tank->fuel_type; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fuel_type" class="form-label">Yakıt Türü</label>
                            <select name="fuel_type" id="fuel_type" class="form-select">
                                <option value="">Tüm Yakıt Türleri</option>
                                <?php foreach($data['fuel_types'] as $type) : ?>
                                    <option value="<?php echo $type; ?>" <?php echo ($data['filters']['fuel_type'] == $type) ? 'selected' : ''; ?>>
                                        <?php echo $type; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo $data['filters']['start_date']; ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="end_date" class="form-label">Bitiş Tarihi</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo $data['filters']['end_date']; ?>">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 mb-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i> Filtrele
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?php echo URLROOT; ?>/fuel" class="btn btn-secondary w-100">
                                <i class="fas fa-undo me-1"></i> Filtreleri Temizle
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Hızlı Raporlar Sekmesi -->
            <div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-left-primary p-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <i class="fas fa-gas-pump fa-2x text-primary"></i>
                                </div>
                                <div class="col">
                                    <h5 class="mb-0">Yakıt Tüketim Raporu</h5>
                                    <p class="text-muted small mb-0">Genel yakıt tüketim analizi</p>
                                </div>
                                <div class="col-auto">
                                    <a href="<?php echo URLROOT; ?>/reports/fuel" class="btn btn-sm btn-primary">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-left-success p-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <i class="fas fa-car fa-2x text-success"></i>
                                </div>
                                <div class="col">
                                    <h5 class="mb-0">Araç Performans Raporu</h5>
                                    <p class="text-muted small mb-0">Araç başına yakıt tüketimi</p>
                                </div>
                                <div class="col-auto">
                                    <a href="<?php echo URLROOT; ?>/reports/vehicles" class="btn btn-sm btn-success">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-left-warning p-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <i class="fas fa-chart-line fa-2x text-warning"></i>
                                </div>
                                <div class="col">
                                    <h5 class="mb-0">Yakıt Trend Analizi</h5>
                                    <p class="text-muted small mb-0">Aylık ve yıllık yakıt tüketim eğilimleri</p>
                                </div>
                                <div class="col-auto">
                                    <a href="<?php echo URLROOT; ?>/reports/fuel?show=trends" class="btn btn-sm btn-warning">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-left-danger p-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <i class="fas fa-file-export fa-2x text-danger"></i>
                                </div>
                                <div class="col">
                                    <h5 class="mb-0">Raporları Dışa Aktar</h5>
                                    <p class="text-muted small mb-0">Excel, PDF veya yazdırılabilir format</p>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="exportAllData()">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Hızlı Filtreler Sekmesi -->
            <div class="tab-pane fade" id="quick-filters" role="tabpanel" aria-labelledby="quick-filters-tab">
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?php echo URLROOT; ?>/fuel" class="btn btn-outline-secondary <?php echo empty($data['filters']['fuel_type']) ? 'active' : ''; ?>">
                        <i class="fas fa-list me-1"></i> Tüm Kayıtlar
                    </a>
                    <a href="<?php echo URLROOT; ?>/fuel/filter" onclick="event.preventDefault(); document.getElementById('quick-filter-dizel').submit();" class="btn btn-outline-warning <?php echo ($data['filters']['fuel_type'] == 'Dizel') ? 'active' : ''; ?>">
                        <i class="fas fa-gas-pump me-1"></i> Dizel
                    </a>
                    <a href="<?php echo URLROOT; ?>/fuel/filter" onclick="event.preventDefault(); document.getElementById('quick-filter-benzin').submit();" class="btn btn-outline-danger <?php echo ($data['filters']['fuel_type'] == 'Benzin') ? 'active' : ''; ?>">
                        <i class="fas fa-gas-pump me-1"></i> Benzin
                    </a>
                    <a href="#" class="btn btn-outline-primary" onclick="event.preventDefault(); setDateFilter('thisMonth')">
                        <i class="fas fa-calendar-alt me-1"></i> Bu Ay
                    </a>
                    <a href="#" class="btn btn-outline-primary" onclick="event.preventDefault(); setDateFilter('lastMonth')">
                        <i class="fas fa-calendar-alt me-1"></i> Geçen Ay
                    </a>
                    <a href="#" class="btn btn-outline-primary" onclick="event.preventDefault(); setDateFilter('last30Days')">
                        <i class="fas fa-calendar-day me-1"></i> Son 30 Gün
                    </a>
                    <a href="#" class="btn btn-outline-primary" onclick="event.preventDefault(); setDateFilter('thisYear')">
                        <i class="fas fa-calendar-alt me-1"></i> Bu Yıl
                    </a>
                </div>

                <!-- Hızlı Filtreleme Formları -->
                <form id="quick-filter-dizel" action="<?php echo URLROOT; ?>/fuel/filter" method="post" style="display: none;">
                    <input type="hidden" name="fuel_type" value="Dizel">
                </form>
                <form id="quick-filter-benzin" action="<?php echo URLROOT; ?>/fuel/filter" method="post" style="display: none;">
                    <input type="hidden" name="fuel_type" value="Benzin">
                </form>
                <form id="date-filter-form" action="<?php echo URLROOT; ?>/fuel/filter" method="post" style="display: none;">
                    <input type="hidden" name="start_date" id="hidden-start-date" value="">
                    <input type="hidden" name="end_date" id="hidden-end-date" value="">
                </form>
            </div>
            
            <!-- Servis Sağlayıcılar -->
            <div class="tab-pane fade" id="service-providers" role="tabpanel" aria-labelledby="service-providers-tab">
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
                                $counter = 0;
                                foreach($data['serviceProviders'] as $provider => $providerCount): 
                                    if($counter < 5): // En çok kullanılan 5 servis sağlayıcı
                                ?>
                                    <tr>
                                        <td><?php echo $provider; ?></td>
                                        <td><?php echo $providerCount; ?></td>
                                    </tr>
                                <?php 
                                    endif;
                                    $counter++;
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

<!-- Yakıt Kayıtları Tablosu -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i> Yakıt Kayıtları</h5>
        <span class="badge bg-light text-primary rounded-pill px-3 py-2"><?php echo $data['total_records']; ?> Kayıt</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 10%">Araç</th>
                        <th style="width: 10%">Sürücü</th>
                        <th style="width: 10%">Tank</th>
                        <th style="width: 8%">Tarih</th>
                        <th style="width: 8%">Yakıt Tipi</th>
                        <th style="width: 8%">Miktar (Lt)</th>
                        <th style="width: 6%">Km</th>
                        <th style="width: 6%">Saat</th>
                        <th style="width: 12%">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['records'] as $fuel) : ?>
                        <tr>
                            <td class="align-middle"><?php echo $fuel->id; ?></td>
                            <td class="align-middle">
                                <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $fuel->vehicle_id; ?>" class="text-primary text-decoration-none">
                                    <?php echo $fuel->plate_number; ?>
                                </a>
                            </td>
                            <td class="align-middle">
                                <?php if($fuel->driver_id): ?>
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $fuel->driver_id; ?>" class="text-primary text-decoration-none">
                                        <?php echo isset($fuel->driver_name) ? $fuel->driver_name : 'Bilinmiyor'; ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle">
                                <a href="<?php echo URLROOT; ?>/tanks/show/<?php echo $fuel->tank_id; ?>" class="text-primary text-decoration-none">
                                    <?php echo isset($fuel->tank_name) ? $fuel->tank_name : 'Bilinmiyor'; ?>
                                </a>
                            </td>
                            <td class="align-middle"><?php echo date('d.m.Y', strtotime($fuel->date)); ?></td>
                            <td class="align-middle text-center">
                                <?php if($fuel->fuel_type == 'Benzin'): ?>
                                    <span class="badge bg-danger">Benzin</span>
                                <?php elseif($fuel->fuel_type == 'Dizel'): ?>
                                    <span class="badge bg-warning text-dark">Dizel</span>
                                <?php elseif($fuel->fuel_type == 'LPG'): ?>
                                    <span class="badge bg-info">LPG</span>
                                <?php elseif($fuel->fuel_type == 'Elektrik'): ?>
                                    <span class="badge bg-success">Elektrik</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle text-end"><?php echo number_format($fuel->amount, 0, ',', '.'); ?></td>
                            <td class="align-middle text-end"><?php echo $fuel->km_reading ? number_format($fuel->km_reading, 0, ',', '.') : '-'; ?></td>
                            <td class="align-middle text-end"><?php echo $fuel->hour_reading ? number_format($fuel->hour_reading, 0, ',', '.') : '-'; ?></td>
                            <td class="align-middle text-center">
                                <div class="btn-group">
                                    <a href="<?php echo URLROOT; ?>/fuel/show/<?php echo $fuel->id; ?>" class="btn btn-sm btn-outline-primary" title="Detay">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/fuel/edit/<?php echo $fuel->id; ?>" class="btn btn-sm btn-outline-warning" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if(isAdmin()): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Sil" 
                                                onclick="confirmDelete(<?php echo $fuel->id; ?>, '<?php echo date('d.m.Y', strtotime($fuel->date)) . ' - ' . $fuel->plate_number . ' - ' . number_format($fuel->amount, 0, ',', '.') . ' Lt'; ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php if(empty($data['records'])): ?>
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-circle me-2"></i> Henüz yakıt kaydı bulunmuyor
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <!-- Sayfalama -->
        <nav>
            <ul class="pagination justify-content-center mb-0">
                <?php if($data['current_page'] > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo URLROOT; ?>/fuel?page=1&limit=<?php echo $data['limit']; ?>" aria-label="İlk">
                            <span aria-hidden="true">&laquo;&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo URLROOT; ?>/fuel?page=<?php echo $data['current_page']-1; ?>&limit=<?php echo $data['limit']; ?>" aria-label="Önceki">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="İlk">
                            <span aria-hidden="true">&laquo;&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Önceki">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php
                // Sayfa numaralarını göster
                $startPage = max(1, $data['current_page'] - 2);
                $endPage = min($data['total_pages'], $data['current_page'] + 2);
                
                // İlk sayfayı göster
                if ($startPage > 1) {
                    echo '<li class="page-item"><a class="page-link" href="' . URLROOT . '/fuel?page=1&limit=' . $data['limit'] . '">1</a></li>';
                    if ($startPage > 2) {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                    }
                }
                
                // Sayfa numaralarını göster
                for ($i = $startPage; $i <= $endPage; $i++) {
                    echo '<li class="page-item ' . ($i == $data['current_page'] ? 'active' : '') . '">';
                    echo '<a class="page-link" href="' . URLROOT . '/fuel?page=' . $i . '&limit=' . $data['limit'] . '">' . $i . '</a>';
                    echo '</li>';
                }
                
                // Son sayfayı göster
                if ($endPage < $data['total_pages']) {
                    if ($endPage < $data['total_pages'] - 1) {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="' . URLROOT . '/fuel?page=' . $data['total_pages'] . '&limit=' . $data['limit'] . '">' . $data['total_pages'] . '</a></li>';
                }
                ?>

                <?php if($data['current_page'] < $data['total_pages']): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo URLROOT; ?>/fuel?page=<?php echo $data['current_page']+1; ?>&limit=<?php echo $data['limit']; ?>" aria-label="Sonraki">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo URLROOT; ?>/fuel?page=<?php echo $data['total_pages']; ?>&limit=<?php echo $data['limit']; ?>" aria-label="Son">
                            <span aria-hidden="true">&raquo;&raquo;</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Sonraki">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Son">
                            <span aria-hidden="true">&raquo;&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        
        <!-- Sayfa başına kayıt sayısı seçimi -->
        <div class="text-center mt-3">
            <div class="btn-group" role="group">
                <a href="<?php echo URLROOT; ?>/fuel?page=1&limit=10" class="btn btn-sm <?php echo $data['limit'] == 10 ? 'btn-primary' : 'btn-outline-primary'; ?>">10</a>
                <a href="<?php echo URLROOT; ?>/fuel?page=1&limit=20" class="btn btn-sm <?php echo $data['limit'] == 20 ? 'btn-primary' : 'btn-outline-primary'; ?>">20</a>
                <a href="<?php echo URLROOT; ?>/fuel?page=1&limit=50" class="btn btn-sm <?php echo $data['limit'] == 50 ? 'btn-primary' : 'btn-outline-primary'; ?>">50</a>
                <a href="<?php echo URLROOT; ?>/fuel?page=1&limit=100" class="btn btn-sm <?php echo $data['limit'] == 100 ? 'btn-primary' : 'btn-outline-primary'; ?>">100</a>
            </div>
            <span class="ms-2 text-muted small">Sayfa başına kayıt sayısı</span>
        </div>
    </div>
</div>

<!-- Silme Onay Modalı -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-trash me-2"></i> Yakıt Kaydı Silme Onayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <p id="deleteModalBody" class="mb-2">Bu yakıt kaydını silmek istediğinizden emin misiniz?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> Bu işlem geri alınamaz ve tanktaki yakıt miktarı güncellenecektir.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> İptal
                </button>
                <form id="deleteForm" action="" method="post">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Evet, Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, details) {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.getElementById('deleteModalBody').textContent = details + " yakıt kaydını silmek istediğinizden emin misiniz?";
        document.getElementById('deleteForm').action = "<?php echo URLROOT; ?>/fuel/delete/" + id;
        modal.show();
    }
    
    function setDateFilter(period) {
        const today = new Date();
        let startDate, endDate;
        
        switch(period) {
            case 'thisMonth':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                endDate = today;
                break;
            case 'lastMonth':
                startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                break;
            case 'last30Days':
                startDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 30);
                endDate = today;
                break;
            case 'thisYear':
                startDate = new Date(today.getFullYear(), 0, 1);
                endDate = today;
                break;
        }
        
        document.getElementById('hidden-start-date').value = startDate.toISOString().split('T')[0];
        document.getElementById('hidden-end-date').value = endDate.toISOString().split('T')[0];
        document.getElementById('date-filter-form').submit();
    }
    
    function exportAllData() {
        // Burası daha sonra dışa aktarma fonksiyonları için kullanılabilir
        alert('Dışa aktarma özellikleri yakında eklenecektir!');
    }

    // Sayfa yüklendiğinde çalışacak
    document.addEventListener('DOMContentLoaded', function() {
        // Tablo satırlarına hover efekti
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseover', function() {
                this.style.backgroundColor = '#f0f8ff';
            });
            row.addEventListener('mouseout', function() {
                this.style.backgroundColor = '';
            });
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 