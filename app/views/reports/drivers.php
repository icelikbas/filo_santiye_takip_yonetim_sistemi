<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-user"></i> Sürücü Raporları</h1>
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
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Sürücü</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['driverStats']['total']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Aktif Sürücüler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['driverStats']['active']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Görevli Sürücüler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['driverStats']['assigned']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Toplam Görevlendirme</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['driverStats']['total_assignments']; ?></div>
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
            <form method="GET" action="<?php echo URLROOT; ?>/reports/drivers" class="row">
                <div class="col-md-3 mb-3">
                    <label for="status">Durum</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Tümü</option>
                        <option value="Aktif" <?php echo $data['filters']['status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="İzinli" <?php echo $data['filters']['status'] == 'İzinli' ? 'selected' : ''; ?>>İzinli</option>
                        <option value="Pasif" <?php echo $data['filters']['status'] == 'Pasif' ? 'selected' : ''; ?>>Pasif</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="license_type">Ehliyet Türü</label>
                    <select class="form-control" id="license_type" name="license_type">
                        <option value="">Tümü</option>
                        <option value="B" <?php echo $data['filters']['license_type'] == 'B' ? 'selected' : ''; ?>>B</option>
                        <option value="C" <?php echo $data['filters']['license_type'] == 'C' ? 'selected' : ''; ?>>C</option>
                        <option value="D" <?php echo $data['filters']['license_type'] == 'D' ? 'selected' : ''; ?>>D</option>
                        <option value="E" <?php echo $data['filters']['license_type'] == 'E' ? 'selected' : ''; ?>>E</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="assignment_status">Görevlendirme Durumu</label>
                    <select class="form-control" id="assignment_status" name="assignment_status">
                        <option value="">Tümü</option>
                        <option value="1" <?php echo $data['filters']['assignment_status'] == '1' ? 'selected' : ''; ?>>Görevde</option>
                        <option value="0" <?php echo $data['filters']['assignment_status'] == '0' ? 'selected' : ''; ?>>Görevde Değil</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrele</button>
                    <a href="<?php echo URLROOT; ?>/reports/drivers" class="btn btn-secondary ms-2">Sıfırla</a>
                </div>
            </form>
        </div>
    </div>


    <!-- Ehliyet Dağılımı -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-id-card"></i> Ehliyet Türü Dağılımı
                </div>
                <div class="card-body">
                    <div class="license-distribution pt-2 pb-2">
                        <div class="row justify-content-center">
                            <?php foreach ($data['licenseDistribution'] as $license) : ?>
                                <div class="col-md-2 col-sm-4 mb-3 text-center">
                                    <div class="card h-100 license-card border-0 shadow-sm">
                                        <div class="card-body p-3">
                                            <div class="license-badge mb-2">
                                                <span class="badge bg-primary rounded-circle p-2">
                                                    <?php echo isset($license->license_type) ? $license->license_type : (isset($license->primary_license_type) ? $license->primary_license_type : '-'); ?>
                                                </span>
                                            </div>
                                            <h3 class="mb-0 font-weight-bold text-primary"><?php echo $license->count; ?></h3>
                                            <div class="text-muted small">Sürücü</div>
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
    .license-distribution .license-card {
        transition: all 0.3s ease;
        border-radius: 10px;
    }
    .license-distribution .license-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .license-badge .badge {
        font-size: 1rem;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #4e73df !important;
    }
    </style>

    <!-- Sürücü Listesi -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Sürücü Listesi
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Adı Soyadı</th>
                            <th>TC No</th>
                            <th>Ehliyet</th>
                            <th>Telefon</th>
                            <th>Durum</th>
                            <th>Görevlendirme Sayısı</th>
                            <th>Mevcut Görev</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['drivers'] as $driver) : ?>
                            <tr>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $driver->id; ?>">
                                        <?php echo $driver->name . ' ' . $driver->surname; ?>
                                    </a>
                                </td>
                                <td><?php echo isset($driver->identity_number) ? $driver->identity_number : '-'; ?></td>
                                <td><?php echo isset($driver->primary_license_type) ? $driver->primary_license_type : '-'; ?></td>
                                <td><?php echo isset($driver->phone) ? $driver->phone : '-'; ?></td>
                                <td>
                                    <?php if ($driver->status == 'Aktif') : ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php elseif ($driver->status == 'İzinli') : ?>
                                        <span class="badge bg-warning">İzinli</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Pasif</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo isset($driver->assignment_count) ? $driver->assignment_count : '0'; ?></td>
                                <td>
                                    <?php if (isset($driver->current_assignment) && $driver->current_assignment) : ?>
                                        <a href="<?php echo URLROOT; ?>/assignments/show/<?php echo $driver->current_assignment->id; ?>" class="badge bg-info text-dark">
                                            <?php echo $driver->current_assignment->plate_number; ?> (<?php echo date('d.m.Y', strtotime($driver->current_assignment->start_date)); ?>)
                                        </a>
                                    <?php else : ?>
                                        <span class="badge bg-secondary">Görevde Değil</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $driver->id; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
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
        try {
            const driversReportTable = $('#dataTable').DataTable({
                "pageLength": 25,
                "dom": '<"row"<"col-sm-6"B><"col-sm-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "buttons": [{
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn-sm btn-success mr-1',
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        },
                        title: 'Sürücü Raporu ' + new Date().toLocaleDateString('tr-TR')
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn-sm btn-danger mr-1',
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        },
                        title: 'Sürücü Raporu ' + new Date().toLocaleDateString('tr-TR'),
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
                        className: 'btn-sm btn-info mr-1',
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Sütunlar',
                        className: 'btn-sm btn-secondary'
                    }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json"
                },
                "responsive": true,
                "stateSave": true,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Tümü"]
                ],
                "columnDefs": [{
                        "orderable": false,
                        "targets": 7
                    } // İşlemler sütunu
                ]
            });

            // Filtre alanlarına event listener ekle
            $('#status, #license_type, #assignment_status').on('change', function() {
                $(this).closest('form').submit();
            });

            console.info("Sürücü rapor tablosu başarıyla yüklendi!");
        } catch (err) {
            console.error("DataTables başlatılamadı: ", err);
            
            // Yedek plan - Basit arama fonksiyonu
            $("#tableSearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#dataTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>