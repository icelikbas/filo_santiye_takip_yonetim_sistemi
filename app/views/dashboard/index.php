<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo $data['title']; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-calendar-alt"></i> Bu Hafta
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#"><i class="fas fa-calendar-day me-2"></i>Bugün</a>
                <a class="dropdown-item" href="#"><i class="fas fa-calendar-week me-2"></i>Bu Hafta</a>
                <a class="dropdown-item" href="#"><i class="fas fa-calendar-alt me-2"></i>Bu Ay</a>
                <a class="dropdown-item" href="#"><i class="fas fa-calendar me-2"></i>Bu Yıl</a>
            </div>
        </div>
    </div>
</div>

<!-- İstatistik Kartları -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card shadow-sm h-100 border-start border-primary border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-truck fa-lg text-primary"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-0 text-muted small">Toplam Araç</h6>
                        <h4 class="mb-0 mt-1 fw-bold"><?php echo $data['total_vehicles']; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card shadow-sm h-100 border-start border-success border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-user fa-lg text-success"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-0 text-muted small">Aktif Sürücüler</h6>
                        <h4 class="mb-0 mt-1 fw-bold"><?php echo $data['active_drivers']; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card shadow-sm h-100 border-start border-info border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-building fa-lg text-info"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-0 text-muted small">Toplam Şirket</h6>
                        <h4 class="mb-0 mt-1 fw-bold"><?php echo $data['total_companies']; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card shadow-sm h-100 border-start border-warning border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-tools fa-lg text-warning"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-0 text-muted small">Yaklaşan Bakım</h6>
                        <h4 class="mb-0 mt-1 fw-bold"><?php echo $data['upcoming_maintenance_count']; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Bölümü -->
<div class="row">
    <div class="col-lg-8 col-md-12 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-chart-line me-2 text-primary"></i>Yakıt Tüketimi</span>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary active" id="monthlyViewBtn">Aylık</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="weeklyViewBtn">Haftalık</button>
                </div>
            </div>
            <div class="card-body">
                <div style="height: 300px; position: relative;">
                    <canvas id="fuelChart" class="chart-view active"></canvas>
                    <canvas id="weeklyFuelChart" class="chart-view" style="display: none;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-truck-moving me-2 text-primary"></i>Araç Dağılımı</span>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="vehicleChartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-filter me-1"></i>Filtre
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="vehicleChartDropdown">
                        <li><a class="dropdown-item" href="#">Tüm Araçlar</a></li>
                        <li><a class="dropdown-item" href="#">Aktif Araçlar</a></li>
                        <li><a class="dropdown-item" href="#">Bakımda Olanlar</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div style="height: 230px; width: 100%; position: relative;">
                    <canvas id="vehicleChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Son Eklenen Şirketler ve Yaklaşan Muayeneler -->
<div class="row">
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-gas-pump me-2 text-primary"></i>Yakıt Tankı Durumları</span>
                <a href="<?php echo URLROOT; ?>/tanks" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-external-link-alt me-1"></i>Tümünü Gör
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover" id="tanksTable">
                        <thead>
                            <tr>
                                <th>Tank Adı</th>
                                <th>Yakıt Tipi</th>
                                <th>Doluluk Oranı</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['fuel_tanks'])): ?>
                            <tr>
                                <td colspan="4" class="text-center">Kayıtlı yakıt tankı bulunmamaktadır</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($data['fuel_tanks'] as $tank) : ?>
                            <?php 
                                // Doluluk oranını hesapla
                                $fillRate = ($tank->current_amount / $tank->capacity) * 100;
                                $fillRate = min(100, max(0, $fillRate)); // 0-100 arasında olmasını sağla
                                
                                // Doluluk durumuna göre renk sınıfı
                                $colorClass = 'bg-danger';
                                if ($fillRate > 75) {
                                    $colorClass = 'bg-success';
                                } elseif ($fillRate > 40) {
                                    $colorClass = 'bg-warning';
                                } elseif ($fillRate > 20) {
                                    $colorClass = 'bg-danger';
                                }
                            ?>
                            <tr>
                                <td class="fw-bold"><?php echo $tank->name; ?></td>
                                <td>
                                    <?php if($tank->fuel_type == 'Benzin'): ?>
                                        <span class="badge bg-danger">Benzin</span>
                                    <?php elseif($tank->fuel_type == 'Dizel'): ?>
                                        <span class="badge bg-warning text-dark">Dizel</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo $tank->fuel_type; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="progress" style="height: 15px;">
                                        <div class="progress-bar <?php echo $colorClass; ?>" role="progressbar" 
                                             style="width: <?php echo $fillRate; ?>%;" 
                                             aria-valuenow="<?php echo $fillRate; ?>" aria-valuemin="0" aria-valuemax="100">
                                            <?php echo round($fillRate, 1); ?>%
                                        </div>
                                    </div>
                                    <small class="text-muted"><?php echo number_format($tank->current_amount, 1, ',', '.'); ?> / <?php echo number_format($tank->capacity, 0, ',', '.'); ?> lt</small>
                                </td>
                                <td>
                                    <?php if($fillRate < 20): ?>
                                        <span class="badge bg-danger">Kritik</span>
                                    <?php elseif($fillRate < 40): ?>
                                        <span class="badge bg-warning text-dark">Az</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Normal</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-car-alt me-2 text-warning"></i>Yaklaşan Muayeneler</span>
                <a href="<?php echo URLROOT; ?>/vehicles" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-external-link-alt me-1"></i>Tümünü Gör
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover" id="inspectionsTable">
                        <thead>
                            <tr>
                                <th>Araç Plakası</th>
                                <th>Şirket</th>
                                <th>Muayene Tarihi</th>
                                <th class="text-center">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['upcoming_inspections'])): ?>
                            <tr>
                                <td colspan="4" class="text-center">Yaklaşan muayene bulunmamaktadır</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($data['upcoming_inspections'] as $vehicle) : ?>
                            <tr>
                                <td class="fw-bold"><?php echo $vehicle->plate_number; ?></td>
                                <td><?php echo isset($vehicle->company_name) ? $vehicle->company_name : '-'; ?></td>
                                <td><?php echo isset($vehicle->inspection_date) ? '<span class="badge bg-warning text-dark">' . date('d.m.Y', strtotime($vehicle->inspection_date)) . '</span>' : '-'; ?></td>
                                <td class="text-center">
                                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo isset($vehicle->id) ? $vehicle->id : 0; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Son Eklenen Araçlar ve Sürücüler -->
<div class="row">
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-truck me-2 text-primary"></i>Son Eklenen Araçlar</span>
                <a href="<?php echo URLROOT; ?>/vehicles" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-external-link-alt me-1"></i>Tümünü Gör
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover" id="vehiclesTable">
                        <thead>
                            <tr>
                                <th>Plaka</th>
                                <th>Marka/Model</th>
                                <th>Şirket</th>
                                <th class="text-center">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['recent_vehicles'])): ?>
                            <tr>
                                <td colspan="4" class="text-center">Henüz araç eklenmemiştir</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($data['recent_vehicles'] as $vehicle) : ?>
                            <tr>
                                <td class="fw-bold"><?php echo isset($vehicle->plate_number) ? $vehicle->plate_number : '-'; ?></td>
                                <td><?php echo isset($vehicle->brand) && isset($vehicle->model) ? $vehicle->brand . ' ' . $vehicle->model : '-'; ?></td>
                                <td><?php echo isset($vehicle->company_name) ? $vehicle->company_name : '-'; ?></td>
                                <td class="text-center">
                                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo isset($vehicle->id) ? $vehicle->id : 0; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-user-tie me-2 text-success"></i>Son Eklenen Sürücüler</span>
                <a href="<?php echo URLROOT; ?>/drivers" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-external-link-alt me-1"></i>Tümünü Gör
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover" id="driversTable">
                        <thead>
                            <tr>
                                <th>Adı Soyadı</th>
                                <th>Ehliyet</th>
                                <th>Şirket</th>
                                <th class="text-center">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['recent_drivers'])): ?>
                            <tr>
                                <td colspan="4" class="text-center">Henüz sürücü eklenmemiştir</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($data['recent_drivers'] as $driver) : ?>
                            <tr>
                                <td class="fw-bold"><?php echo isset($driver->name) && isset($driver->surname) ? $driver->name . ' ' . $driver->surname : '-'; ?></td>
                                <td><?php echo isset($driver->primary_license_type) ? '<span class="badge bg-success">' . $driver->primary_license_type . '</span>' : '-'; ?></td>
                                <td><?php echo isset($driver->company_name) ? $driver->company_name : '-'; ?></td>
                                <td class="text-center">
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo isset($driver->id) ? $driver->id : 0; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Son Bakım Kayıtları -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span><i class="fas fa-tools me-2 text-danger"></i>Son Bakım Kayıtları</span>
        <a href="<?php echo URLROOT; ?>/maintenance" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-external-link-alt me-1"></i>Tümünü Gör
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover" id="maintenanceTable">
                <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Araç</th>
                        <th>Bakım Türü</th>
                        <th>Şirket</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['recent_maintenance'])): ?>
                    <tr>
                        <td colspan="7" class="text-center">Bakım kaydı bulunmamaktadır</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach($data['recent_maintenance'] as $maintenance) : ?>
                    <tr>
                        <td><?php echo isset($maintenance->start_date) ? date('d.m.Y', strtotime($maintenance->start_date)) : '-'; ?></td>
                        <td class="fw-bold"><?php echo isset($maintenance->plate_number) ? $maintenance->plate_number : '-'; ?></td>
                        <td><?php echo isset($maintenance->maintenance_type) ? $maintenance->maintenance_type : '-'; ?></td>
                        <td><?php echo isset($maintenance->company_name) ? $maintenance->company_name : '-'; ?></td>
                        <td><?php echo isset($maintenance->cost) ? number_format($maintenance->cost, 2, ',', '.') . ' ₺' : '-'; ?></td>
                        <td><?php 
                            $statusClass = 'bg-secondary';
                            if(isset($maintenance->status)) {
                                if($maintenance->status == 'Tamamlandı') $statusClass = 'bg-success';
                                elseif($maintenance->status == 'Beklemede') $statusClass = 'bg-warning text-dark';
                                elseif($maintenance->status == 'İşlemde') $statusClass = 'bg-info';
                            }
                            echo '<span class="badge ' . $statusClass . '">' . (isset($maintenance->status) ? $maintenance->status : 'Belirsiz') . '</span>'; 
                        ?></td>
                        <td class="text-center">
                            <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo isset($maintenance->id) ? $maintenance->id : 0; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Dashboard tabloları için DataTables yapılandırması
    document.addEventListener('DOMContentLoaded', function() {
        // Basit DataTables konfigürasyonu
        const simpleTableOptions = {
            "paging": false,
            "searching": false,
            "info": false,
            "ordering": true,
            "responsive": true,
            "autoWidth": false,
            "language": {
                "emptyTable": "Veri bulunamadı",
                "zeroRecords": "Eşleşen kayıt bulunamadı"
            }
        };
        
        // Her tabloya basit konfigürasyon uygula
        try {
            // Şirketler tablosu
            if (document.getElementById('companiesTable')) {
                initDataTable('companiesTable', simpleTableOptions);
            }
            
            // Muayeneler tablosu
            if (document.getElementById('inspectionsTable')) {
                initDataTable('inspectionsTable', simpleTableOptions);
            }
            
            // Araçlar tablosu
            if (document.getElementById('vehiclesTable')) {
                initDataTable('vehiclesTable', simpleTableOptions);
            }
            
            // Sürücüler tablosu
            if (document.getElementById('driversTable')) {
                initDataTable('driversTable', simpleTableOptions);
            }
            
            // Bakım tablosu
            if (document.getElementById('maintenanceTable')) {
                initDataTable('maintenanceTable', {
                    ...simpleTableOptions,
                    "paging": true,
                    "pageLength": 5,
                    "lengthMenu": [[5, 10], [5, 10]]
                });
            }
        } catch (e) {
            console.error("DataTables başlatma hatası:", e);
        }
        
        // Chart.js grafiklerini başlat
        initCharts();
        
        // Aylık/Haftalık butonlarının tıklama olayları
        document.getElementById('monthlyViewBtn').addEventListener('click', function() {
            document.getElementById('monthlyViewBtn').classList.add('active');
            document.getElementById('weeklyViewBtn').classList.remove('active');
            document.getElementById('fuelChart').style.display = 'block';
            document.getElementById('weeklyFuelChart').style.display = 'none';
        });
        
        document.getElementById('weeklyViewBtn').addEventListener('click', function() {
            document.getElementById('weeklyViewBtn').classList.add('active');
            document.getElementById('monthlyViewBtn').classList.remove('active');
            document.getElementById('fuelChart').style.display = 'none';
            document.getElementById('weeklyFuelChart').style.display = 'block';
        });
    });
    
    // Grafikleri başlatma fonksiyonu
    function initCharts() {
        try {
            // Yakıt grafiği - Aylık
            const ctx1 = document.getElementById('fuelChart');
            if (ctx1) {
                let fuelData = <?php echo json_encode($data['fuel_consumption_months'] ?? []); ?>;
                let fuelLabels = [];
                let fuelValues = [];
                
                if (fuelData && fuelData.length > 0) {
                    fuelData.forEach(item => {
                        fuelLabels.push(item.month_name);
                        fuelValues.push(parseFloat(item.total_amount));
                    });
                } else {
                    // Veri yoksa varsayılan değerler
                    fuelLabels = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran'];
                    fuelValues = [0, 0, 0, 0, 0, 0];
                }
                
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: fuelLabels,
                        datasets: [{
                            label: 'Aylık Yakıt Tüketimi (Lt)',
                            data: fuelValues,
                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                            borderColor: 'rgba(78, 115, 223, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.raw + ' Lt';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value + ' Lt';
                                    }
                                }
                            }
                        }
                    }
                });
                console.log("Aylık yakıt grafiği oluşturuldu");
            }
            
            // Yakıt grafiği - Haftalık
            const ctx3 = document.getElementById('weeklyFuelChart');
            if (ctx3) {
                let weeklyData = <?php echo json_encode($data['fuel_consumption_weeks'] ?? []); ?>;
                let weeklyLabels = [];
                let weeklyValues = [];
                
                if (weeklyData && weeklyData.length > 0) {
                    weeklyData.forEach(item => {
                        weeklyLabels.push(item.week_name);
                        weeklyValues.push(parseFloat(item.total_amount));
                    });
                } else {
                    // Veri yoksa varsayılan değerler
                    weeklyLabels = ['Hafta 1', 'Hafta 2', 'Hafta 3', 'Hafta 4', 'Hafta 5', 'Hafta 6'];
                    weeklyValues = [0, 0, 0, 0, 0, 0];
                }
                
                new Chart(ctx3, {
                    type: 'bar',
                    data: {
                        labels: weeklyLabels,
                        datasets: [{
                            label: 'Haftalık Yakıt Tüketimi (Lt)',
                            data: weeklyValues,
                            backgroundColor: 'rgba(28, 200, 138, 0.5)',
                            borderColor: 'rgba(28, 200, 138, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                            barThickness: 20,
                            maxBarThickness: 30
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.raw + ' Lt';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value + ' Lt';
                                    }
                                }
                            }
                        }
                    }
                });
                console.log("Haftalık yakıt grafiği oluşturuldu");
            }

            // Araç dağılımı grafiği
            const ctx2 = document.getElementById('vehicleChart');
            if (ctx2) {
                let vehicleData = <?php echo json_encode($data['vehicle_type_distribution'] ?? []); ?>;
                let vehicleLabels = [];
                let vehicleValues = [];
                let backgroundColors = [
                    'rgba(78, 115, 223, 0.8)',
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(54, 185, 204, 0.8)',
                    'rgba(246, 194, 62, 0.8)',
                    'rgba(231, 74, 59, 0.8)',
                    'rgba(133, 135, 150, 0.8)'
                ];
                
                if (vehicleData && vehicleData.length > 0) {
                    vehicleData.forEach(item => {
                        vehicleLabels.push(item.vehicle_type);
                        vehicleValues.push(parseInt(item.count));
                    });
                } else {
                    // Veri yoksa varsayılan değerler
                    vehicleLabels = ['Binek', 'Kamyon', 'Otobüs', 'Diğer'];
                    vehicleValues = [0, 0, 0, 0];
                }
                
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: vehicleLabels,
                        datasets: [{
                            data: vehicleValues,
                            backgroundColor: backgroundColors.slice(0, vehicleLabels.length),
                            borderWidth: 1,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 15,
                                    padding: 15
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.raw || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        return label + ': ' + value + ' araç (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
                console.log("Araç dağılım grafiği başarıyla oluşturuldu.");
            }
        } catch (err) {
            console.error("Grafikler oluşturulurken hata oluştu:", err);
        }
        
        // Sayfa yüklendiğinde en üste kaydır
        window.scrollTo(0, 0);
    }
    
    // Yardımcı fonksiyon - DataTables için
    function initDataTable(tableId, options) {
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.DataTable !== 'undefined') {
            try {
                jQuery('#' + tableId).DataTable(options);
                console.log(tableId + " için DataTable başlatıldı");
            } catch (e) {
                console.error(tableId + " için DataTable başlatılırken hata:", e);
            }
        } else {
            console.warn("jQuery veya DataTables kütüphanesi yüklü değil");
        }
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 