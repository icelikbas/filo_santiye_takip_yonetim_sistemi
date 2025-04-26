<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-tag mr-2"></i> <?php echo $data['title']; ?>
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/finetypes" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Listeye Dön
                    </a>
                    <a href="<?php echo URLROOT; ?>/finetypes/edit/<?php echo $data['fineType']->id; ?>" class="btn btn-warning">
                        <i class="fas fa-edit mr-1"></i> Düzenle
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('fine_type_message'); ?>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Ceza Tipi Detayları</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <th width="30%">Ceza Kodu</th>
                                <td><strong><?php echo $data['fineType']->code; ?></strong></td>
                            </tr>
                            <tr>
                                <th>Ceza Adı</th>
                                <td><?php echo $data['fineType']->name; ?></td>
                            </tr>
                            <tr>
                                <th>İlgili Kanun Maddesi</th>
                                <td>
                                    <?php echo !empty($data['fineType']->legal_article) ? $data['fineType']->legal_article : '<span class="text-muted">Belirtilmemiş</span>'; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Ceza Puanı</th>
                                <td>
                                    <?php echo !empty($data['fineType']->points) ? $data['fineType']->points : '<span class="text-muted">Belirtilmemiş</span>'; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Varsayılan Tutar</th>
                                <td><strong><?php echo number_format($data['fineType']->default_amount, 2, ',', '.'); ?> ₺</strong></td>
                            </tr>
                            <tr>
                                <th>Durum</th>
                                <td>
                                    <?php if($data['fineType']->active == 1) : ?>
                                        <span class="badge bg-success text-white">Aktif</span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary text-white">Pasif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Açıklama</th>
                                <td>
                                    <?php echo !empty($data['fineType']->description) ? nl2br($data['fineType']->description) : '<span class="text-muted">Açıklama yok</span>'; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Oluşturulma Tarihi</th>
                                <td><?php echo date('d.m.Y H:i', strtotime($data['fineType']->created_at)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-chart-line mr-2"></i>İstatistikler</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-primary shadow-sm py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bu Tipte Toplam Ceza</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($data['fines']); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-success shadow-sm py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Toplam Ceza Tutarı</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                $totalAmount = 0;
                                                foreach($data['fines'] as $fine) {
                                                    $totalAmount += !empty($fine->amount) ? $fine->amount : 0;
                                                }
                                                echo number_format($totalAmount, 2, ',', '.') . ' ₺';
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(!empty($data['fines'])) : ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-receipt mr-2"></i>Bu Ceza Tipinin Uygulandığı Cezalar</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover data-table-buttons" id="finesTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Tarih</th>
                            <th>Plaka</th>
                            <th>Sürücü</th>
                            <th>Tutar</th>
                            <th>Ödeme Durumu</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['fines'] as $fine) : ?>
                            <tr>
                                <td><?php echo !empty($fine->id) ? $fine->id : '<span class="text-muted">ID Yok</span>'; ?></td>
                                <td>
                                    <?php 
                                    if(!empty($fine->fine_date)) {
                                        echo date('d.m.Y', strtotime($fine->fine_date)); 
                                    } else {
                                        echo '<span class="text-muted">Belirtilmemiş</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php echo !empty($fine->plate_number) ? $fine->plate_number : '<span class="text-muted">Belirtilmemiş</span>'; ?>
                                </td>
                                <td>
                                    <?php
                                    if(!empty($fine->driver_name) && !empty($fine->driver_surname)) {
                                        echo $fine->driver_name . ' ' . $fine->driver_surname;
                                    } else {
                                        echo '<span class="text-muted">Belirtilmemiş</span>';
                                    }
                                    ?>
                                </td>
                                <td class="text-right">
                                    <?php echo !empty($fine->amount) ? number_format($fine->amount, 2, ',', '.') . ' ₺' : '<span class="text-muted">Belirtilmemiş</span>'; ?>
                                </td>
                                <td>
                                    <?php
                                    if(!empty($fine->payment_status)) {
                                        switch($fine->payment_status) {
                                            case 'Ödendi':
                                                echo '<span class="badge bg-success text-white">Ödendi</span>';
                                                break;
                                            case 'Taksitli Ödeme':
                                                echo '<span class="badge bg-info text-white">Taksitli Ödeme</span>';
                                                break;
                                            default:
                                                echo '<span class="badge bg-danger text-white">Ödenmedi</span>';
                                                break;
                                        }
                                    } else {
                                        echo '<span class="badge bg-secondary text-white">Belirtilmemiş</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if(!empty($fine->id)): ?>
                                    <a href="<?php echo URLROOT; ?>/trafficfines/show/<?php echo $fine->id; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php else: ?>
                                    <span class="text-muted">İşlem Yok</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php else : ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-1"></i> Bu ceza tipi için henüz ceza kaydı bulunmuyor.
    </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            if (typeof jQuery !== 'undefined' && jQuery('#finesTable').length > 0) {
                var dataTable = jQuery('#finesTable').DataTable();
                
                if (dataTable) {
                    dataTable.order([1, 'desc']).draw();
                }
            }
        }, 1000);
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 