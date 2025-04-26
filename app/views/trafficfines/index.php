<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-exclamation-triangle mr-2"></i> <?php echo $data['title']; ?>
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/trafficfines/add" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Yeni Ceza Ekle
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('traffic_fine_message'); ?>

    <!-- İstatistik Kartları -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Ceza</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['totalFines']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-danger shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Toplam Ceza Tutarı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($data['totalAmount'], 2, ',', '.'); ?> ₺</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-lira-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Ödenmemiş Tutar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($data['unpaidAmount'], 2, ',', '.'); ?> ₺</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Ödenmiş Tutar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($data['paidAmount'], 2, ',', '.'); ?> ₺</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Arama ve Filtreleme -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-search mr-2"></i>Arama ve Filtreleme</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="searchInput"><strong>Hızlı Arama:</strong></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" id="searchInput" placeholder="Ceza no, plaka, açıklama...">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="statusFilter"><strong>Ödeme Durumu:</strong></label>
                    <select class="form-control" id="statusFilter">
                        <option value="">Tüm Durumlar</option>
                        <option value="Ödenmedi">Ödenmedi</option>
                        <option value="Taksitli Ödeme">Taksitli Ödeme</option>
                        <option value="Ödendi">Ödendi</option>
                        <option value="İtiraz Edildi">İtiraz Edildi</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="typeFilter"><strong>Ceza Tipi:</strong></label>
                    <select class="form-control" id="typeFilter">
                        <option value="">Tüm Ceza Tipleri</option>
                        <?php if (isset($data['fineTypes']) && !empty($data['fineTypes'])) : ?>
                            <?php foreach ($data['fineTypes'] as $type) : ?>
                                <option value="<?php echo $type->name; ?>"><?php echo $type->name; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="d-block">&nbsp;</label>
                    <button id="resetFilters" class="btn btn-secondary btn-block">
                        <i class="fas fa-sync-alt mr-1"></i> Sıfırla
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ceza Listesi -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-list mr-2"></i>Trafik Cezaları Listesi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover data-table-buttons" id="trafficFinesTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Ceza No</th>
                            <th>Araç</th>
                            <th>Ceza Tipi</th>
                            <th>Ceza Tarihi</th>
                            <th>Tutar</th>
                            <th>Ödeme Durumu</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['trafficFines'] as $fine) : ?>
                            <tr>
                                <td><?php echo $fine->id; ?></td>
                                <td><?php echo $fine->fine_number; ?></td>
                                <td>
                                    <?php echo $fine->plate_number ? '<strong>' . $fine->plate_number . '</strong>' : '<span class="text-muted">Belirtilmemiş</span>'; ?>
                                </td>
                                <td><?php echo $fine->fine_type_name; ?></td>
                                <td><?php echo date('d.m.Y', strtotime($fine->fine_date)); ?></td>
                                <td class="text-right"><?php echo number_format($fine->fine_amount, 2, ',', '.'); ?> ₺</td>
                                <td>
                                    <?php
                                    switch($fine->payment_status) {
                                        case 'Ödendi':
                                            echo '<span class="badge bg-success text-white">Ödendi</span>';
                                            break;
                                        case 'İtiraz Edildi':
                                            echo '<span class="badge bg-warning text-white">İtiraz Edildi</span>';
                                            break;
                                        case 'Taksitli Ödeme':
                                            echo '<span class="badge bg-info text-white">Taksitli Ödeme</span>';
                                            break;
                                        default:
                                            echo '<span class="badge bg-danger text-white">Ödenmedi</span>';
                                            break;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo URLROOT; ?>/trafficfines/show/<?php echo $fine->id; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/trafficfines/edit/<?php echo $fine->id; ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-delete" data-id="<?php echo $fine->id; ?>" data-toggle="modal" data-target="#deleteModal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Silme Onay Modalı -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Ceza Kaydını Sil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Bu ceza kaydını silmek istediğinizden emin misiniz? Bu işlem geri alınamaz ve ilgili ödeme kayıtları da silinecektir.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <form id="deleteForm" action="" method="POST">
                    <button type="submit" class="btn btn-danger">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filtreleme işlevleri için bekle
        setTimeout(function() {
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.DataTable !== 'undefined') {
                var $ = jQuery;
                var table = $('#trafficFinesTable').DataTable();
                
                console.log('DataTable başlatıldı. Sütun sayısı:', table.columns().count());
                
                // Tablo sütunlarını kontrol et
                table.columns().every(function(index) {
                    console.log('Sütun ' + index + ' başlığı:', $(table.column(index).header()).text().trim());
                });
                
                // Arama işlevi
                $('#searchInput').on('keyup', function() {
                    table.search(this.value).draw();
                });
                
                // Filtreleme işlevleri
                $('#statusFilter').on('change', function() {
                    console.log('Ödeme durumu filtresi:', this.value);
                    table.column(6).search(this.value).draw();
                });
                
                $('#typeFilter').on('change', function() {
                    var selectedType = this.value;
                    console.log('Ceza tipi filtresi seçildi:', selectedType);
                    
                    // RegExp kullanarak tam eşleşme yerine ceza tipinin adının içinde geçmesini sağla
                    if (selectedType) {
                        var regex = selectedType.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                        table.column(3).search(regex, true, false).draw();
                    } else {
                        table.column(3).search('').draw();
                    }
                });
                
                // Filtreleri sıfırlama
                $('#resetFilters').on('click', function() {
                    $('#searchInput').val('');
                    $('#statusFilter').val('');
                    $('#typeFilter').val('');
                    table.search('').columns().search('').draw();
                    console.log('Filtreler sıfırlandı');
                });
                
                // Silme modalı
                $('.btn-delete').on('click', function() {
                    var id = $(this).data('id');
                    $('#deleteForm').attr('action', '<?php echo URLROOT; ?>/trafficfines/delete/' + id);
                });
            } else {
                console.error('jQuery veya DataTables yüklenemedi!');
            }
        }, 1000);
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 