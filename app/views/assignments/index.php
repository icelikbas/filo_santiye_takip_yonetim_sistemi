<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <!-- Başlık ve Üst Bilgi Alanı -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-clipboard-list me-2"></i> Görevlendirme Yönetimi
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/assignments/add" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Yeni Görevlendirme
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('assignment_message'); ?>

    <!-- Durum Kartları -->
    <div class="row mb-4">
        <?php
        $activeCount = 0;
        $completedCount = 0;
        $canceledCount = 0;
        
        foreach($data['assignments'] as $assignment) {
            if($assignment->status == 'Aktif') $activeCount++;
            elseif($assignment->status == 'Tamamlandı') $completedCount++;
            elseif($assignment->status == 'İptal') $canceledCount++;
        }
        $totalCount = count($data['assignments']);
        ?>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Görevlendirme</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                                Aktif Görevlendirmeler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $activeCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tamamlanan Görevlendirmeler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $completedCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-flag-checkered fa-2x text-gray-300"></i>
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
                                İptal Edilen Görevlendirmeler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $canceledCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ban fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Arama ve Filtreleme -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-search me-2"></i>Arama ve Filtreleme</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="searchInput"><strong>Arama:</strong></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Plaka, sürücü...">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="statusFilter"><strong>Durum Filtresi:</strong></label>
                    <select class="form-control" id="statusFilter">
                        <option value="">Tüm Durumlar</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tamamlandı">Tamamlandı</option>
                        <option value="İptal">İptal</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="locationFilter"><strong>Konum Filtresi:</strong></label>
                    <select class="form-control" id="locationFilter">
                        <option value="">Tüm Konumlar</option>
                        <?php 
                            // Benzersiz konumları topla
                            $locations = [];
                            foreach($data['assignments'] as $assignment) {
                                if (!empty($assignment->location) && !in_array($assignment->location, $locations)) {
                                    $locations[] = $assignment->location;
                                }
                            }
                            sort($locations); // Alfabetik sırala
                            foreach($locations as $location) {
                                echo '<option value="' . $location . '">' . $location . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="dateFilter"><strong>Tarih Filtresi:</strong></label>
                    <select class="form-control" id="dateFilter">
                        <option value="">Tüm Tarihler</option>
                        <option value="this-week">Bu Hafta</option>
                        <option value="this-month">Bu Ay</option>
                        <option value="last-month">Geçen Ay</option>
                    </select>
                </div>
                <div class="col-md-1 mb-3">
                    <label class="d-block">&nbsp;</label>
                    <button id="resetFilters" class="btn btn-secondary w-100">
                        <i class="fas fa-sync-alt me-1"></i> Sıfırla
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Görevlendirme Listesi -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-list me-2"></i>Görevlendirme Listesi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="assignmentsTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Plaka</th>
                            <th>Sürücü</th>
                            <th>Başlangıç</th>
                            <th>Bitiş</th>
                            <th>Konum</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['assignments'])): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Henüz görevlendirme bulunmuyor
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['assignments'] as $assignment) : ?>
                                <tr>
                                    <td><?php echo $assignment->id; ?></td>
                                    <td><strong><?php echo $assignment->plate_number; ?></strong></td>
                                    <td><?php echo $assignment->driver_name; ?></td>
                                    <td><i class="far fa-calendar-alt me-1"></i> <?php echo date('d.m.Y', strtotime($assignment->start_date)); ?></td>
                                    <td>
                                        <?php if($assignment->end_date): ?>
                                            <i class="far fa-calendar-check me-1"></i> <?php echo date('d.m.Y', strtotime($assignment->end_date)); ?>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="far fa-calendar-minus me-1"></i> Belirtilmemiş</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($assignment->location)): ?>
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i> <?php echo $assignment->location; ?>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="fas fa-map-marker-alt me-1"></i> Belirtilmemiş</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($assignment->status == 'Aktif'): ?>
                                            <span class="badge bg-success text-white">Aktif</span>
                                        <?php elseif($assignment->status == 'Tamamlandı'): ?>
                                            <span class="badge bg-info text-white">Tamamlandı</span>
                                        <?php elseif($assignment->status == 'İptal'): ?>
                                            <span class="badge bg-danger text-white">İptal</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary text-white"><?php echo $assignment->status; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo URLROOT; ?>/assignments/show/<?php echo $assignment->id; ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Görüntüle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo URLROOT; ?>/assignments/edit/<?php echo $assignment->id; ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if(isAdmin()): ?>
                                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-assignment-id="<?php echo $assignment->id; ?>" data-plate="<?php echo $assignment->plate_number; ?>" data-driver="<?php echo $assignment->driver_name; ?>" title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
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

<!-- Silme Onay Modalı - Tüm modalleri tek bir yere taşıdık -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Görevlendirme Silme Onayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <p id="deleteConfirmText">Bu görevlendirmeyi silmek istediğinize emin misiniz?</p>
                <p class="text-danger"><small>Bu işlem geri alınamaz.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <form id="deleteForm" action="" method="post">
                    <button type="submit" class="btn btn-danger">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-info {
    border-left: 4px solid #36b9cc !important;
}
.border-left-danger {
    border-left: 4px solid #e74a3b !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-xs {
    font-size: 0.7rem;
}
.font-weight-bold {
    font-weight: 700 !important;
}
.btn-group .btn {
    margin-right: 3px;
}

/* Modal görünüm düzeltmeleri */
.modal {
    z-index: 1050;
}
.modal-backdrop {
    z-index: 1040;
}
.modal-dialog {
    margin: 1.75rem auto;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        // jQuery ve DataTables yüklü mü kontrol et
        if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
            console.error('jQuery veya DataTables yüklenmemiş!');
            return;
        }
        
        // Eğer zaten DataTable örneği varsa, onu yok et
        if ($.fn.dataTable.isDataTable('#assignmentsTable')) {
            $('#assignmentsTable').DataTable().destroy();
        }
        
        // DataTables'ı doğrudan başlat
        const assignmentsTable = $('#assignmentsTable').DataTable({
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
            },
            autoWidth: false,
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: 7 }, // İşlemler sütunu sıralanamaz
                { width: "5%", targets: 0 },
                { width: "15%", targets: 1 },
                { width: "20%", targets: 2 },
                { width: "15%", targets: 3 },
                { width: "15%", targets: 4 },
                { width: "10%", targets: 5 },
                { width: "10%", targets: 6 },
                { width: "15%", targets: 7, className: "text-center" }
            ],
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            order: [[0, "desc"]],
            // Tablonun arama ve filtreleme yeteneklerini geliştir
            search: {
                return: true,
                regex: true
            }
        });
        
        if (assignmentsTable) {
            // Harici arama kutusu ile DataTables entegrasyonu
            $('#searchInput').on('keyup', function() {
                assignmentsTable.search(this.value).draw();
            });
            
            // Durum filtresi
            $('#statusFilter').on('change', function() {
                var val = $(this).val();
                assignmentsTable.column(6).search(val ? val : '', true, false).draw();
            });
            
            // Konum filtresi
            $('#locationFilter').on('change', function() {
                var val = $(this).val();
                assignmentsTable.column(5).search(val ? val : '', true, false).draw();
            });
            
            // Tarih filtresi
            $('#dateFilter').on('change', function() {
                var val = $(this).val();
                
                // DataTable'ın arama fonksiyonu için kullanılacak fonksiyon
                $.fn.dataTable.ext.search.pop(); // Önceki tarih filtresini temizle
                
                if (val) {
                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                        var dateStr = data[3]; // "Başlangıç" tarih sütunu (4. sütun, 0-indexed)
                        if (!dateStr) return true; // Tarih yoksa göster
                        
                        // Tarih formatını DD.MM.YYYY'den YYYY-MM-DD'ye çevir
                        var dateParts = dateStr.replace(/[^\d.]/g, '').split('.');
                        if (dateParts.length !== 3) return true; // Geçersiz format ise göster
                        
                        var date = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                        var now = new Date();
                        
                        if (val === 'this-week') {
                            // Bu haftanın başlangıcı (Pazartesi)
                            var startOfWeek = new Date(now);
                            startOfWeek.setDate(now.getDate() - now.getDay() + (now.getDay() === 0 ? -6 : 1));
                            startOfWeek.setHours(0, 0, 0, 0);
                            
                            return date >= startOfWeek;
                        } 
                        else if (val === 'this-month') {
                            // Bu ayın başlangıcı
                            var startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
                            return date >= startOfMonth;
                        } 
                        else if (val === 'last-month') {
                            // Geçen ayın başlangıcı ve bitişi
                            var startOfLastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                            var endOfLastMonth = new Date(now.getFullYear(), now.getMonth(), 0);
                            
                            return date >= startOfLastMonth && date <= endOfLastMonth;
                        }
                        
                        return true; // Filtre yok, tüm satırları göster
                    });
                }
                
                assignmentsTable.draw(); // Tabloyu yeniden çiz
            });
            
            // Filtreleri sıfırla
            $('#resetFilters').on('click', function() {
                $('#searchInput').val('');
                $('#statusFilter').val('');
                $('#locationFilter').val('');
                $('#dateFilter').val('');
                
                // Tüm tarihe dayalı filtreleri kaldır
                $.fn.dataTable.ext.search.pop();
                
                assignmentsTable.search('').columns().search('').draw();
            });
        }
        
        // Silme butonları için olay dinleyicisi
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const assignmentId = this.getAttribute('data-assignment-id');
                const plate = this.getAttribute('data-plate');
                const driver = this.getAttribute('data-driver');
                
                // Modal içeriğini güncelle
                document.getElementById('deleteConfirmText').innerHTML = 
                    `<strong>${plate}</strong> plakalı araca ait <strong>${driver}</strong> isimli sürücü görevlendirmesini silmek istediğinize emin misiniz?`;
                
                // Form action'ını güncelle
                document.getElementById('deleteForm').action = `<?php echo URLROOT; ?>/assignments/delete/${assignmentId}`;
                
                // Modalı aç
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                deleteModal.show();
            });
        });
        
    } catch (error) {
        console.error("DataTables veya Bootstrap hata:", error);
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 