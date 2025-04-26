<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-car"></i> Araç Raporları</h1>
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
        <div class="col-md-3">
            <div class="card border-start-primary border-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Toplam Araç</div>
                            <div class="h5 mb-0 fw-bold"><?php echo isset($data['vehicleStats']['total']) ? $data['vehicleStats']['total'] : 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-start-success border-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Aktif Araçlar</div>
                            <div class="h5 mb-0 fw-bold"><?php echo isset($data['vehicleStats']['active']) ? $data['vehicleStats']['active'] : 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-start-warning border-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Bakımda</div>
                            <div class="h5 mb-0 fw-bold"><?php echo isset($data['vehicleStats']['maintenance']) ? $data['vehicleStats']['maintenance'] : 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-start-danger border-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                                Pasif Araçlar</div>
                            <div class="h5 mb-0 fw-bold"><?php echo isset($data['vehicleStats']['inactive']) ? $data['vehicleStats']['inactive'] : 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
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
            <form method="GET" action="<?php echo URLROOT; ?>/reports/vehicles" class="row g-3">
                <div class="col-md-4 mb-3">
                    <label for="search" class="form-label">Araç Ara</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Plaka, Marka veya Model" value="<?php echo isset($data['filters']['search']) ? $data['filters']['search'] : ''; ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Durum</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tümü</option>
                        <option value="Aktif" <?php echo isset($data['filters']['status']) && $data['filters']['status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Pasif" <?php echo isset($data['filters']['status']) && $data['filters']['status'] == 'Pasif' ? 'selected' : ''; ?>>Pasif</option>
                        <option value="Bakımda" <?php echo isset($data['filters']['status']) && $data['filters']['status'] == 'Bakımda' ? 'selected' : ''; ?>>Bakımda</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="type" class="form-label">Araç Tipi</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Tümü</option>
                        <option value="Otomobil" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Otomobil' ? 'selected' : ''; ?>>Otomobil</option>
                        <option value="Kamyonet" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Kamyonet' ? 'selected' : ''; ?>>Kamyonet</option>
                        <option value="Kamyon" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Kamyon' ? 'selected' : ''; ?>>Kamyon</option>
                        <option value="Otobüs" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Otobüs' ? 'selected' : ''; ?>>Otobüs</option>
                        <option value="Damperli Kamyon" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Damperli Kamyon' ? 'selected' : ''; ?>>Damperli Kamyon</option>
                        <option value="Beton Santrali" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Beton Santrali' ? 'selected' : ''; ?>>Beton Santrali</option>
                        <option value="Silindir" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Silindir' ? 'selected' : ''; ?>>Silindir</option>
                        <option value="Loder" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Loder' ? 'selected' : ''; ?>>Loder</option>
                        <option value="Bekoloder" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Bekoloder' ? 'selected' : ''; ?>>Bekoloder</option>
                        <option value="Ekskavatör" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Ekskavatör' ? 'selected' : ''; ?>>Ekskavatör</option>
                        <option value="Akaryakıt Tankı" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Akaryakıt Tankı' ? 'selected' : ''; ?>>Akaryakıt Tankı</option>
                        <option value="Mikser" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Mikser' ? 'selected' : ''; ?>>Mikser</option>
                        <option value="Çekici" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Çekici' ? 'selected' : ''; ?>>Çekici</option>
                        <option value="Arazöz" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Arazöz' ? 'selected' : ''; ?>>Arazöz</option>
                        <option value="Mobil Beton Pompası" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Mobil Beton Pompası' ? 'selected' : ''; ?>>Mobil Beton Pompası</option>
                        <option value="Jeneratör" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'Jeneratör' ? 'selected' : ''; ?>>Jeneratör</option>
                        <option value="İş Makinesi" <?php echo isset($data['filters']['type']) && $data['filters']['type'] == 'İş Makinesi' ? 'selected' : ''; ?>>İş Makinesi</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrele</button>
                    <a href="<?php echo URLROOT; ?>/reports/vehicles" class="btn btn-secondary ms-2">Sıfırla</a>
                </div>
            </form>
        </div>
    </div>

    
    <!-- Araç Dağılımı -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <i class="fas fa-chart-pie text-primary me-2"></i> <strong>Araç Tipi Dağılımı</strong>
                </div>
                <div class="card-body">
                    <?php if(isset($data['vehicleTypeDistribution']) && count($data['vehicleTypeDistribution']) > 0): ?>
                        <div class="row g-2">
                            <?php 
                            // Renk sınıfları
                            $colorClasses = [
                                'primary', 'success', 'info', 'warning', 'danger', 
                                'secondary', 'dark', 'primary', 'success', 'info'
                            ];
                            
                            $i = 0;
                            foreach($data['vehicleTypeDistribution'] as $type) : 
                                $colorClass = $colorClasses[$i % count($colorClasses)];
                                $i++;
                            ?>
                                <div class="col-md-2 col-sm-4 col-6 mb-2">
                                    <div class="card border border-<?php echo $colorClass; ?> h-100">
                                        <div class="card-body p-2 text-center">
                                            <h6 class="card-title text-<?php echo $colorClass; ?> mb-1 fs-6 text-truncate" title="<?php echo $type->vehicle_type; ?>">
                                                <?php echo $type->vehicle_type; ?>
                                            </h6>
                                            <p class="mb-0">
                                                <span class="badge bg-<?php echo $colorClass; ?> fs-5 mb-1"><?php echo $type->count; ?></span>
                                                <small class="d-block text-muted">Araç</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <p class="text-muted mb-0">Araç tipi dağılımı verisi bulunamadı.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Araç Listesi -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Araç Listesi
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered data-table-buttons" id="vehiclesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Plaka</th>
                            <th>Marka</th>
                            <th>Model</th>
                            <th>Yıl</th>
                            <th>Araç Tipi</th>
                            <th>Durum</th>
                            <th>Yakıt Tüketimi</th>
                            <th>Bakım Maliyeti</th>
                            <th>Görevlendirme</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($data['vehicles']) && count($data['vehicles']) > 0): ?>
                            <?php foreach($data['vehicles'] as $vehicle) : ?>
                                <tr>
                                    <td><a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $vehicle->id; ?>"><?php echo $vehicle->plate_number; ?></a></td>
                                    <td><?php echo $vehicle->brand; ?></td>
                                    <td><?php echo $vehicle->model; ?></td>
                                    <td><?php echo $vehicle->year; ?></td>
                                    <td><?php echo $vehicle->vehicle_type; ?></td>
                                    <td>
                                        <?php if ($vehicle->status == 'Aktif') : ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php elseif ($vehicle->status == 'Bakımda') : ?>
                                            <span class="badge bg-warning">Bakımda</span>
                                        <?php else : ?>
                                            <span class="badge bg-danger">Pasif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (isset($vehicle->fuelStats) && isset($vehicle->fuelStats->total_fuel)) {
                                            echo number_format($vehicle->fuelStats->total_fuel, 2, ',', '.') . ' Lt';
                                        } else {
                                            echo '0 Lt';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo isset($vehicle->maintenanceCost) ? number_format($vehicle->maintenanceCost, 2, ',', '.') . ' ₺' : '0 ₺'; ?>
                                    </td>
                                    <td>
                                        <?php echo isset($vehicle->assignment_count) ? $vehicle->assignment_count : 0; ?> görev
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo URLROOT; ?>/reports/vehicle_history/<?php echo $vehicle->id; ?>" class="btn btn-info btn-sm" title="Detaylı Geçmiş">
                                            <i class="fas fa-history"></i> Detay
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Araç bulunamadı</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // URL'den arama parametresini alalım ve arama kutusuna ekleyelim
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const searchParam = urlParams.get('search');
        
        if (searchParam) {
            // Form search alanına değeri yerleştirelim
            const filterSearchBox = document.getElementById('search');
            if (filterSearchBox) {
                filterSearchBox.value = searchParam;
            }
            
            // DataTables yüklendikten sonra arama ekleyelim
            setTimeout(function() {
                if (typeof jQuery !== 'undefined') {
                    jQuery('#vehiclesTable_filter input').val(searchParam).trigger('keyup');
                }
            }, 800);
        }
        
        // Arama kutusunu senkronize etmek için
        setTimeout(function() {
            if (typeof jQuery !== 'undefined') {
                const $dataTableSearchBox = jQuery('#vehiclesTable_filter input');
                const $filterSearchBox = jQuery('#search');
                
                if ($dataTableSearchBox.length && $filterSearchBox.length) {
                    // DataTable araması değiştiğinde
                    $dataTableSearchBox.on('input', function() {
                        $filterSearchBox.val(jQuery(this).val());
                    });
                    
                    // Form gönderilmeden önce
                    jQuery('form').on('submit', function() {
                        $filterSearchBox.val($dataTableSearchBox.val());
                    });
                }
            }
        }, 1000);
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 