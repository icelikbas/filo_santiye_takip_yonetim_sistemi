<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-certificate mr-2"></i><?php echo $data['title']; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo URLROOT; ?>/certificateTypes/add" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-plus mr-1"></i> Yeni Sertifika Türü Ekle
        </a>
    </div>
</div>

<?php flash('certificateType_message'); ?>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        Sertifika Türleri Listesi
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered data-table" id="certificateTypesTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ad</th>
                        <th>Açıklama</th>
                        <th style="width: 150px;">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['certificateTypes'] as $type) : ?>
                        <tr>
                            <td><?php echo $type->id; ?></td>
                            <td><?php echo $type->name; ?></td>
                            <td><?php echo $type->description; ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group" aria-label="İşlemler">
                                    <a href="<?php echo URLROOT; ?>/certificateTypes/drivers/<?php echo $type->id; ?>" class="btn btn-info" data-toggle="tooltip" title="Sürücüleri Göster">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/certificateTypes/edit/<?php echo $type->id; ?>" class="btn btn-primary" data-toggle="tooltip" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if(isAdmin()) : ?>
                                        <form class="d-inline" action="<?php echo URLROOT; ?>/certificateTypes/delete/<?php echo $type->id; ?>" method="post">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bu sertifika türünü silmek istediğinize emin misiniz?');" data-toggle="tooltip" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Belge Tipleri Hakkında</h5>
            </div>
            <div class="card-body">
                <p>İş makinesi belge tipleri, sürücülerin sahip oldukları operatör belgelerini kategorize etmek için kullanılır.</p>
                <p>Her belge tipi için şu bilgiler tanımlanabilir:</p>
                <ul>
                    <li><strong>Belge Adı:</strong> Belge tipinin adı (örn. "Forklift Operatör Belgesi")</li>
                    <li><strong>Açıklama:</strong> Belge hakkında açıklayıcı bilgi</li>
                    <li><strong>Veren Kurum:</strong> Belgeyi düzenleyen kurum/kuruluş</li>
                    <li><strong>Geçerlilik Süresi:</strong> Belgenin geçerlilik süresi (ay cinsinden)</li>
                    <li><strong>Yenileme Gerekli mi:</strong> Belgenin süre sonunda yenilenmesi gerekip gerekmediği</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Sürücüye Belge Eklerken</h5>
            </div>
            <div class="card-body">
                <p>Sürücülerinize belge eklerken burada tanımladığınız belge tiplerini seçerek:</p>
                <ul>
                    <li>Belge geçerlilik süresi otomatik hesaplanır</li>
                    <li>Belge için gerekli bilgiler standartlaştırılır</li>
                    <li>Benzer belgeleri daha kolay takip edebilirsiniz</li>
                    <li>Belge yenileme süreçlerini daha verimli yönetebilirsiniz</li>
                </ul>
                <p class="mb-0">
                    <a href="<?php echo URLROOT; ?>/certificates/dashboard" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-certificate mr-1"></i> Belge Yönetimine Git
                    </a>
                </p>
            </div>
        </div>
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
            
            console.log('DataTable yükleniyor...');
            
            // DataTable'ı doğrudan başlat
            $('#certificateTypesTable').DataTable({
                "pageLength": 25,
                "order": [[ 0, "asc" ]],
                "columnDefs": [
                    { "orderable": false, "targets": 3 } // İşlemler sütunu sıralanabilir olmasın
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
            
            console.log('DataTable başarıyla yüklendi.');
        } catch (err) {
            console.error('DataTable başlatılırken hata oluştu:', err);
        }
    });
</script>

<style>
    /* İşlemler sütunu için stil düzenlemeleri */
    #certificateTypesTable .btn-group form {
        display: inline-block;
    }
    
    #certificateTypesTable .btn-group .btn {
        border-radius: 0;
    }
    
    #certificateTypesTable .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    
    #certificateTypesTable .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?> 