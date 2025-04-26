<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-gas-pump"></i> Yakıt Raporları</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="#" onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-print"></i> Yazdır
                </a>
                <a href="<?php echo URLROOT; ?>/reports" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Geri
                </a>
            </div>
        </div>
    </div>


    <!-- İstatistikler -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Yakıt Tüketimi (Lt)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($data['fuelStats']->total_fuel) ? number_format($data['fuelStats']->total_fuel, 0, ',', '.') : '0'; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gas-pump fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Toplam Kayıt Sayısı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($data['fuelStats']->record_count) ? $data['fuelStats']->record_count : '0'; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtreler -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter"></i> Filtreler
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo URLROOT; ?>/reports/fuel" class="row">
                <div class="col-md-3 mb-3">
                    <label for="vehicle_id">Araç</label>
                    <select class="form-control" id="vehicle_id" name="vehicle_id">
                        <option value="">Tümü</option>
                        <?php foreach($data['vehicles'] as $vehicle) : ?>
                            <option value="<?php echo $vehicle->id; ?>" <?php echo $data['filters']['vehicle_id'] == $vehicle->id ? 'selected' : ''; ?>>
                                <?php echo $vehicle->plate_number; ?> - <?php echo $vehicle->brand; ?> <?php echo $vehicle->model; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="driver_id">Sürücü</label>
                    <select class="form-control" id="driver_id" name="driver_id">
                        <option value="">Tümü</option>
                        <?php foreach($data['drivers'] as $driver) : ?>
                            <option value="<?php echo $driver->id; ?>" <?php echo $data['filters']['driver_id'] == $driver->id ? 'selected' : ''; ?>>
                                <?php echo $driver->name . ' ' . $driver->surname; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="fuel_type">Yakıt Türü</label>
                    <select class="form-control" id="fuel_type" name="fuel_type">
                        <option value="">Tümü</option>
                        <option value="Benzin" <?php echo $data['filters']['fuel_type'] == 'Benzin' ? 'selected' : ''; ?>>Benzin</option>
                        <option value="Dizel" <?php echo $data['filters']['fuel_type'] == 'Dizel' ? 'selected' : ''; ?>>Dizel</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="start_date">Başlangıç Tarihi</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $data['filters']['start_date']; ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="end_date">Bitiş Tarihi</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $data['filters']['end_date']; ?>">
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Filtrele</button>
                    <a href="<?php echo URLROOT; ?>/reports/fuel" class="btn btn-secondary ms-2">Sıfırla</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Yakıt Türü Dağılımı -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-gas-pump"></i> Yakıt Türü Dağılımı
                </div>
                <div class="card-body">
                    <div class="fuel-distribution pt-2 pb-2">
                        <div class="row justify-content-center">
                            <?php foreach($data['fuelTypeDistribution'] as $type) : 
                                // Yakıt tipine göre renk belirleme
                                $colorClass = 'primary';
                                switch(strtolower($type->fuel_type)) {
                                    case 'benzin': $colorClass = 'danger'; $icon = 'gas-pump'; break;
                                    case 'dizel': $colorClass = 'warning'; $icon = 'oil-can'; break;
                                    default: $colorClass = 'primary'; $icon = 'tint'; break;
                                }
                            ?>
                                <div class="col-md-3 col-sm-6 mb-4 text-center">
                                    <div class="card h-100 fuel-card border-0 shadow-sm">
                                        <div class="card-body p-3">
                                            <div class="fuel-icon mb-2">
                                                <span class="fuel-badge bg-<?php echo $colorClass; ?> rounded-circle p-2">
                                                    <i class="fas fa-<?php echo $icon; ?>"></i>
                                                </span>
                                            </div>
                                            <h5 class="card-title"><?php echo $type->fuel_type; ?></h5>
                                            <h3 class="mb-0 font-weight-bold text-<?php echo $colorClass; ?>"><?php echo number_format($type->total_liters, 0, ',', '.'); ?> Lt</h3>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .fuel-distribution .fuel-card {
        transition: all 0.3s ease;
        border-radius: 10px;
    }
    .fuel-distribution .fuel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .fuel-icon .fuel-badge {
        font-size: 1.25rem;
        width: 50px;
        height: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    </style>

    <!-- Yakıt Kayıtları -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Yakıt Kayıtları
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered data-table" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Araç</th>
                            <th>Sürücü</th>
                            <th>Tarih</th>
                            <th>Yakıt Türü</th>
                            <th>Miktar (Lt)</th>
                            <th>Detay</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['fuelRecords'] as $record) : ?>
                            <tr>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $record->vehicle_id; ?>">
                                        <?php echo $record->plate_number; ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if(!empty($record->driver_id)): ?>
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $record->driver_id; ?>">
                                        <?php echo $record->driver_name; ?>
                                    </a>
                                    <?php else: ?>
                                    <span class="text-muted">Belirtilmemiş</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d.m.Y', strtotime($record->date)); ?></td>
                                <td><?php echo $record->fuel_type; ?></td>
                                <td><?php echo number_format($record->amount, 0, ',', '.'); ?> Lt</td>
                                <td class="text-center">
                                    <a href="<?php echo URLROOT; ?>/fuel/show/<?php echo $record->id; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Görüntüle
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DataTables'ı başlat
        const fuelReportTable = initDataTable('dataTable', {
            "pageLength": 25,
            "dom": '<"top"f>rt<"bottom"lip>',
            "stateSave": true
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
