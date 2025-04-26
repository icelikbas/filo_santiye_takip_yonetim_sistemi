<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-certificate mr-2"></i> 
        <?php echo $data['certificateType']->name; ?> Sertifikasına Sahip Sürücüler
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo URLROOT; ?>/certificateTypes" class="btn btn-sm btn-outline-secondary ml-2">
            <i class="fas fa-arrow-left mr-1"></i> Sertifika Türlerine Dön
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-info-circle mr-1"></i>
        Sertifika Türü Bilgileri
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Sertifika Adı:</strong> <?php echo $data['certificateType']->name; ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Açıklama:</strong> <?php echo $data['certificateType']->description; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-users mr-1"></i>
        Bu Sertifikaya Sahip Sürücüler
    </div>
    <div class="card-body">
        <?php if(empty($data['drivers'])) : ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i> Bu sertifika türüne sahip sürücü bulunmamaktadır.
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-bordered data-table" id="driversTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ad Soyad</th>
                            <th>Telefon</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['drivers'] as $driver) : ?>
                            <tr>
                                <td><?php echo $driver->id; ?></td>
                                <td><?php echo $driver->name . ' ' . $driver->surname; ?></td>
                                <td><?php echo $driver->phone; ?></td>
                                <td>
                                    <span class="badge <?php 
                                        if($driver->status == 'Aktif') echo 'bg-success';
                                        elseif($driver->status == 'Pasif') echo 'bg-secondary';
                                        elseif($driver->status == 'İzinli') echo 'bg-info';
                                    ?> text-white"><?php echo $driver->status; ?></span>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/drivers/show/<?php echo $driver->id; ?>" class="btn btn-sm btn-info" data-toggle="tooltip" title="Sürücü Detayı">
                                        <i class="fas fa-user"></i>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/certificates/index/<?php echo $driver->id; ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Sertifikaları Görüntüle">
                                        <i class="fas fa-certificate"></i>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // jQuery ve DataTables kontrolü
            if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
                console.error('jQuery veya DataTables bulunamadı!');
                return;
            }
            
            console.log('Sürücüler tablosu yükleniyor...');
            
            // DataTable'ı doğrudan başlat
            $('#driversTable').DataTable({
                "pageLength": 25,
                "order": [[ 0, "asc" ]],
                "columnDefs": [
                    { "orderable": false, "targets": 4 } // İşlemler sütunu sıralanabilir olmasın
                ],
                "responsive": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json"
                }
            });
            
            // Tooltips'i aktifleştir
            if (typeof $().tooltip === 'function') {
                $('[data-toggle="tooltip"]').tooltip();
            } else {
                console.warn('Bootstrap Tooltip fonksiyonu bulunamadı.');
            }
            
            console.log('Sürücüler tablosu başarıyla yüklendi.');
        } catch (err) {
            console.error('DataTable başlatılırken hata oluştu:', err);
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 