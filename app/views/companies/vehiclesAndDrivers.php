<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-list text-primary mr-2"></i> <?php echo $data['title']; ?>
            </h1>
            <p class="mb-4">
                <?php if ($data['is_filtered'] && $data['company']): ?>
                    <span class="badge badge-pill badge-primary"><?php echo htmlspecialchars($data['company']->company_name); ?></span> şirketine ait araçları ve sürücüleri listeleyin ve çıktı alın.
                <?php else: ?>
                    Sistemdeki tüm araçları ve sürücüleri listeleyin ve çıktı alın.
                <?php endif; ?>
            </p>
        </div>
    </div>

    <?php flash('company_message'); ?>

    <!-- Sekmeler -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs" id="listTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="vehicles-tab" data-bs-toggle="tab" href="#vehicles" role="tab" aria-controls="vehicles" aria-selected="true">
                        <i class="fas fa-car mr-1"></i> Araçlar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="drivers-tab" data-bs-toggle="tab" href="#drivers" role="tab" aria-controls="drivers" aria-selected="false">
                        <i class="fas fa-users mr-1"></i> Sürücüler
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="listTabContent">
                <!-- Araçlar Tablosu -->
                <div class="tab-pane fade show active" id="vehicles" role="tabpanel" aria-labelledby="vehicles-tab">
                    <?php if (empty($data['vehicles'])): ?>
                        <div class="alert alert-light border text-center">
                            <i class="fas fa-info-circle"></i> <?php echo ($data['is_filtered']) ? 'Bu şirkete ait araç kaydı bulunmamaktadır.' : 'Sistemde kayıtlı araç bulunmamaktadır.'; ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="vehiclesTable" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Plaka</th>
                                        <th>Marka</th>
                                        <th>Model</th>
                                        <th>Yıl</th>
                                        <th>Araç Tipi</th>
                                        <th>Durum</th>
                                        <?php if (!$data['is_filtered']): ?>
                                        <th>Şirket</th>
                                        <?php endif; ?>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['vehicles'] as $vehicle): ?>
                                        <tr>
                                            <td><?php echo $vehicle->id; ?></td>
                                            <td><?php echo htmlspecialchars($vehicle->plate_number); ?></td>
                                            <td><?php echo htmlspecialchars($vehicle->brand); ?></td>
                                            <td><?php echo htmlspecialchars($vehicle->model); ?></td>
                                            <td><?php echo htmlspecialchars($vehicle->year); ?></td>
                                            <td><?php echo htmlspecialchars($vehicle->vehicle_type); ?></td>
                                            <td>
                                                <?php if($vehicle->status == 'Aktif'): ?>
                                                    <span class="badge badge-success">Aktif</span>
                                                <?php elseif($vehicle->status == 'Pasif'): ?>
                                                    <span class="badge badge-secondary">Pasif</span>
                                                <?php elseif($vehicle->status == 'Bakımda'): ?>
                                                    <span class="badge badge-warning">Bakımda</span>
                                                <?php endif; ?>
                                            </td>
                                            <?php if (!$data['is_filtered']): ?>
                                            <td>
                                                <?php 
                                                if(isset($vehicle->company_name)) {
                                                    echo htmlspecialchars($vehicle->company_name);
                                                } else {
                                                    echo '<span class="text-muted">-</span>';
                                                }
                                                ?>
                                            </td>
                                            <?php endif; ?>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/vehicles/show/<?php echo $vehicle->id; ?>" class="btn btn-sm btn-info">
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

                <!-- Sürücüler Tablosu -->
                <div class="tab-pane fade" id="drivers" role="tabpanel" aria-labelledby="drivers-tab">
                    <?php if (empty($data['drivers'])): ?>
                        <div class="alert alert-light border text-center">
                            <i class="fas fa-info-circle"></i> <?php echo ($data['is_filtered']) ? 'Bu şirkete ait sürücü kaydı bulunmamaktadır.' : 'Sistemde kayıtlı sürücü bulunmamaktadır.'; ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="driversTable" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>TC Kimlik</th>
                                        <th>Ad</th>
                                        <th>Soyad</th>
                                        <th>Telefon</th>
                                        <th>Ehliyet Sınıfı</th>
                                        <th>Durum</th>
                                        <?php if (!$data['is_filtered']): ?>
                                        <th>Şirket</th>
                                        <?php endif; ?>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['drivers'] as $driver): ?>
                                        <tr>
                                            <td><?php echo $driver->id; ?></td>
                                            <td><?php echo htmlspecialchars($driver->identity_number); ?></td>
                                            <td><?php echo htmlspecialchars($driver->name); ?></td>
                                            <td><?php echo htmlspecialchars($driver->surname); ?></td>
                                            <td><?php echo htmlspecialchars($driver->phone); ?></td>
                                            <td><span class="badge badge-primary"><?php echo htmlspecialchars($driver->primary_license_type); ?></span></td>
                                            <td>
                                                <?php if($driver->status == 'Aktif'): ?>
                                                    <span class="badge badge-success">Aktif</span>
                                                <?php elseif($driver->status == 'Pasif'): ?>
                                                    <span class="badge badge-secondary">Pasif</span>
                                                <?php elseif($driver->status == 'İzinli'): ?>
                                                    <span class="badge badge-info">İzinli</span>
                                                <?php endif; ?>
                                            </td>
                                            <?php if (!$data['is_filtered']): ?>
                                            <td>
                                                <?php 
                                                if(isset($driver->company_name)) {
                                                    echo htmlspecialchars($driver->company_name);
                                                } else {
                                                    echo '<span class="text-muted">-</span>';
                                                }
                                                ?>
                                            </td>
                                            <?php endif; ?>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $driver->id; ?>" class="btn btn-sm btn-info">
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Araç listesi başlığı için değişkenler
        var vehiclesTitle = <?php echo json_encode(($data['is_filtered'] && $data['company']) ? 
            htmlspecialchars($data['company']->company_name) . ' - Araçlar' : 
            'Tüm Araçlar'); ?> + ' - ' + new Date().toLocaleDateString('tr-TR');
            
        // Sürücü listesi başlığı için değişkenler
        var driversTitle = <?php echo json_encode(($data['is_filtered'] && $data['company']) ? 
            htmlspecialchars($data['company']->company_name) . ' - Sürücüler' : 
            'Tüm Sürücüler'); ?> + ' - ' + new Date().toLocaleDateString('tr-TR');

        // DataTables için ortak konfigürasyon
        var commonConfig = {
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json"
            },
            responsive: true,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Tümü"]
            ],
            pageLength: 25,
            dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        };

        // Araçlar tablosunu başlat
        if (document.getElementById('vehiclesTable')) {
            try {
                var vehiclesTable = $('#vehiclesTable').DataTable({
                    ...commonConfig,
                    buttons: [
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn-sm btn-success',
                            exportOptions: {
                                columns: ':not(:last-child)'
                            },
                            title: vehiclesTitle
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            className: 'btn-sm btn-danger',
                            exportOptions: {
                                columns: ':not(:last-child)'
                            },
                            title: vehiclesTitle,
                            customize: function(doc) {
                                doc.defaultStyle.fontSize = 10;
                                doc.styles.tableHeader.fontSize = 11;
                                doc.styles.tableHeader.alignment = 'left';
                                doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> Yazdır',
                            className: 'btn-sm btn-secondary',
                            exportOptions: {
                                columns: ':not(:last-child)'
                            }
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="fas fa-columns"></i> Sütunlar',
                            className: 'btn-sm btn-info'
                        }
                    ],
                    columnDefs: [
                        {orderable: false, targets: -1}
                    ]
                });
            } catch (e) {
                console.error('Araçlar tablosu yüklenirken hata oluştu:', e);
            }
        }

        // Sürücüler tablosunu başlat
        if (document.getElementById('driversTable')) {
            try {
                var driversTable = $('#driversTable').DataTable({
                    ...commonConfig,
                    buttons: [
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn-sm btn-success',
                            exportOptions: {
                                columns: ':not(:last-child)'
                            },
                            title: driversTitle
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            className: 'btn-sm btn-danger',
                            exportOptions: {
                                columns: ':not(:last-child)'
                            },
                            title: driversTitle,
                            customize: function(doc) {
                                doc.defaultStyle.fontSize = 10;
                                doc.styles.tableHeader.fontSize = 11;
                                doc.styles.tableHeader.alignment = 'left';
                                doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> Yazdır',
                            className: 'btn-sm btn-secondary',
                            exportOptions: {
                                columns: ':not(:last-child)'
                            }
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="fas fa-columns"></i> Sütunlar',
                            className: 'btn-sm btn-info'
                        }
                    ],
                    columnDefs: [
                        {orderable: false, targets: -1}
                    ]
                });
            } catch (e) {
                console.error('Sürücüler tablosu yüklenirken hata oluştu:', e);
            }
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 