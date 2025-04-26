<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-tags mr-2"></i> <?php echo $data['title']; ?>
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/finetypes/add" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Yeni Ceza Tipi Ekle
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('fine_type_message'); ?>

    <!-- İstatistik Kartları -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-4">
            <div class="card border-left-primary shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Ceza Tipi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['totalCount']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4">
            <div class="card border-left-success shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Aktif Ceza Tipleri</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['activeCount']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4">
            <div class="card border-left-secondary shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Pasif Ceza Tipleri</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $data['inactiveCount']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
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
                <div class="col-md-6 mb-3">
                    <label for="searchInput"><strong>Hızlı Arama:</strong></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" id="searchInput" placeholder="Kod, ad, açıklama...">
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="statusFilter"><strong>Durum Filtresi:</strong></label>
                    <select class="form-control" id="statusFilter">
                        <option value="">Tüm Durumlar</option>
                        <option value="1">Aktif</option>
                        <option value="0">Pasif</option>
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

    <!-- Ceza Tipleri Listesi -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-list mr-2"></i>Ceza Tipleri Listesi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover data-table-buttons" id="fineTypesTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Kod</th>
                            <th>Ad</th>
                            <th>Kanun Maddesi</th>
                            <th>Açıklama</th>
                            <th>Varsayılan Tutar</th>
                            <th>Ceza Puanı</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['fineTypes'] as $type) : ?>
                            <tr>
                                <td><?php echo $type->id; ?></td>
                                <td><strong><?php echo $type->code; ?></strong></td>
                                <td><?php echo $type->name; ?></td>
                                <td>
                                    <?php
                                    if(!empty($type->legal_article)) {
                                        echo $type->legal_article;
                                    } else {
                                        echo '<span class="text-muted">-</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if(!empty($type->description)) {
                                        echo substr($type->description, 0, 50) . (strlen($type->description) > 50 ? '...' : '');
                                    } else {
                                        echo '<span class="text-muted">Açıklama yok</span>';
                                    }
                                    ?>
                                </td>
                                <td class="text-right"><?php echo number_format($type->default_amount, 2, ',', '.'); ?> ₺</td>
                                <td class="text-center">
                                    <?php
                                    if(!empty($type->points)) {
                                        echo $type->points;
                                    } else {
                                        echo '<span class="text-muted">-</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if($type->active == 1) : ?>
                                        <span class="badge bg-success text-white">Aktif</span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary text-white">Pasif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo URLROOT; ?>/finetypes/show/<?php echo $type->id; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/finetypes/edit/<?php echo $type->id; ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm <?php echo ($type->active == 1) ? 'btn-secondary' : 'btn-success'; ?> btn-toggle-status" 
                                                data-id="<?php echo $type->id; ?>"
                                                data-active="<?php echo $type->active; ?>"
                                                title="<?php echo ($type->active == 1) ? 'Pasif Yap' : 'Aktif Yap'; ?>">
                                            <i class="fas <?php echo ($type->active == 1) ? 'fa-toggle-off' : 'fa-toggle-on'; ?>"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                data-id="<?php echo $type->id; ?>"
                                                data-name="<?php echo $type->name; ?>">
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
                <h5 class="modal-title" id="deleteModalLabel">Ceza Tipi Sil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Bu ceza tipini silmek istediğinizden emin misiniz? Bu işlem geri alınamaz. Eğer ceza tipi cezalarda kullanılıyorsa silinemez.
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

<!-- Durum Değiştirme Modalı -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" role="dialog" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="toggleStatusModalLabel">Durum Değiştir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="toggleStatusModalBody">
                Ceza tipinin durumunu değiştirmek istediğinizden emin misiniz?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <form id="toggleStatusForm" action="" method="POST">
                    <button type="submit" class="btn btn-primary">Evet, Değiştir</button>
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
                var table = $('#fineTypesTable').DataTable();
                
                console.log('DataTable başlatıldı. Sütun sayısı:', table.columns().count());
                
                // Tablodan sonra oluşturulan satırlara olay dinleyicileri atama
                function setupTableEventListeners() {
                    // Silme modalı
                    $('#fineTypesTable').on('click', '.btn-delete', function() {
                        var id = $(this).data('id');
                        var name = $(this).data('name');
                        console.log('Silme butonuna tıklandı: ID=' + id + ', İsim=' + name);
                        
                        $('#deleteModalLabel').text('Ceza Tipi Sil: ' + name);
                        $('#deleteForm').attr('action', '<?php echo URLROOT; ?>/finetypes/delete/' + id);
                        
                        // Modal'ı elle aç
                        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                        deleteModal.show();
                    });
                    
                    // Durum değiştirme modalı
                    $('#fineTypesTable').on('click', '.btn-toggle-status', function() {
                        var id = $(this).data('id');
                        var active = $(this).data('active');
                        var isActive = active == 1;
                        var statusText = isActive ? 'pasif' : 'aktif';
                        
                        console.log('Durum değiştirme butonuna tıklandı: ID=' + id + ', Aktif=' + active);
                        
                        $('#toggleStatusModalLabel').text('Durum Değiştir');
                        $('#toggleStatusModalBody').text(`Bu ceza tipini ${statusText} yapmak istediğinizden emin misiniz?`);
                        $('#toggleStatusForm').attr('action', '<?php echo URLROOT; ?>/finetypes/toggleStatus/' + id);
                        
                        // Modal'ı elle aç
                        var toggleModal = new bootstrap.Modal(document.getElementById('toggleStatusModal'));
                        toggleModal.show();
                    });
                }
                
                // İlk yükleme için event listener'ları ayarla
                setupTableEventListeners();
                
                // DataTables draw olayında event listener'ları tekrar ayarla
                table.on('draw', function() {
                    setupTableEventListeners();
                });
                
                // Arama işlevi
                $('#searchInput').on('keyup', function() {
                    table.search(this.value).draw();
                });
                
                // Filtreleme işlevleri - Durum (index 7)
                $('#statusFilter').on('change', function() {
                    var value = this.value;
                    console.log('Durum filtresi:', value);
                    
                    if (value === '1') {
                        table.column(7).search('Aktif').draw();
                    } else if (value === '0') {
                        table.column(7).search('Pasif').draw();
                    } else {
                        table.column(7).search('').draw();
                    }
                });
                
                // Filtreleri sıfırlama
                $('#resetFilters').on('click', function() {
                    $('#searchInput').val('');
                    $('#statusFilter').val('');
                    table.search('').columns().search('').draw();
                    console.log('Filtreler sıfırlandı');
                });
            } else {
                console.error('jQuery veya DataTables yüklenemedi!');
            }
        }, 1000);
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 