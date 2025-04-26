<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-money-bill-wave mr-2"></i> Ödeme Detayı
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/trafficfines/show/<?php echo $data['trafficFine']->id; ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Ceza Detayına Dön
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('payment_message'); ?>

    <div class="row">
        <div class="col-md-8">
            <!-- Ödeme Detayları -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-file-invoice-dollar mr-2"></i>Ödeme Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th class="bg-light">Ödeme Tarihi</th>
                                    <td><?php echo date('d.m.Y', strtotime($data['payment']->payment_date)); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Ödeme Tutarı</th>
                                    <td><span class="font-weight-bold"><?php echo number_format($data['payment']->amount, 2, ',', '.'); ?> ₺</span></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Ödeme Yöntemi</th>
                                    <td><?php echo $data['payment']->payment_method; ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Makbuz/Dekont No</th>
                                    <td>
                                        <?php 
                                        if (!empty($data['payment']->receipt_number)) {
                                            echo $data['payment']->receipt_number;
                                        } else {
                                            echo '<span class="text-muted">Belirtilmemiş</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th class="bg-light">Kayıt Tarihi</th>
                                    <td><?php echo date('d.m.Y H:i', strtotime($data['payment']->created_at)); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Kaydeden</th>
                                    <td>
                                        <?php 
                                        if (isset($data['user'])) {
                                            echo $data['user']->name . ' ' . $data['user']->surname;
                                        } else {
                                            echo '<span class="text-muted">Bilinmiyor</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Son Güncelleme</th>
                                    <td>
                                        <?php 
                                        if (property_exists($data['payment'], 'updated_at') && $data['payment']->updated_at) {
                                            echo date('d.m.Y H:i', strtotime($data['payment']->updated_at));
                                        } else {
                                            echo '<span class="text-muted">Güncellenmemiş</span>';
                                        } 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">İşlemler</th>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo URLROOT; ?>/finepayments/edit/<?php echo $data['payment']->id; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit mr-1"></i> Düzenle
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deletePaymentModal">
                                                <i class="fas fa-trash mr-1"></i> Sil
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if (!empty($data['payment']->notes)): ?>
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <strong><i class="fas fa-sticky-note mr-1"></i> Notlar</strong>
                        </div>
                        <div class="card-body">
                            <?php echo nl2br(htmlspecialchars($data['payment']->notes)); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Ceza Özeti -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Ceza Bilgileri</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-striped">
                        <tr>
                            <th>Ceza No:</th>
                            <td><?php echo $data['trafficFine']->fine_number; ?></td>
                        </tr>
                        <tr>
                            <th>Araç Plakası:</th>
                            <td><?php echo $data['trafficFine']->plate_number; ?></td>
                        </tr>
                        <tr>
                            <th>Ceza Tarihi:</th>
                            <td><?php echo date('d.m.Y', strtotime($data['trafficFine']->fine_date)); ?></td>
                        </tr>
                        <tr>
                            <th>Ceza Tipi:</th>
                            <td>
                                <?php 
                                if (property_exists($data['payment'], 'fine_type_name') && !empty($data['payment']->fine_type_name)) {
                                    echo $data['payment']->fine_type_name;
                                } else {
                                    echo '<span class="text-muted">Belirtilmemiş</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Toplam Tutar:</th>
                            <td class="font-weight-bold"><?php echo number_format($data['trafficFine']->fine_amount, 2, ',', '.'); ?> ₺</td>
                        </tr>
                        <tr>
                            <th>Ödeme Durumu:</th>
                            <td>
                                <?php if($data['trafficFine']->payment_status == 'Ödenmedi') : ?>
                                    <span class="badge bg-danger text-white">Ödenmedi</span>
                                <?php elseif($data['trafficFine']->payment_status == 'Taksitli Ödeme') : ?>
                                    <span class="badge bg-warning text-white">Taksitli Ödeme</span>
                                <?php elseif($data['trafficFine']->payment_status == 'Ödendi') : ?>
                                    <span class="badge bg-success text-white">Ödendi</span>
                                <?php elseif($data['trafficFine']->payment_status == 'İtiraz Edildi') : ?>
                                    <span class="badge bg-info text-white">İtiraz Edildi</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>

                    <div class="mt-3 text-center">
                        <a href="<?php echo URLROOT; ?>/trafficfines/show/<?php echo $data['trafficFine']->id; ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye mr-1"></i> Ceza Detayını Görüntüle
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ödeme Makbuzu -->
            <?php if (!empty($data['payment']->receipt_number)): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-receipt mr-2"></i>Makbuz Bilgileri</h5>
                </div>
                <div class="card-body text-center">
                    <h5><?php echo $data['payment']->receipt_number; ?></h5>
                    <p class="text-muted mb-2">Makbuz/Dekont Numarası</p>
                    <div class="border rounded p-3 bg-light mt-3">
                        <i class="fas fa-file-invoice fa-3x text-secondary mb-3"></i>
                        <p class="mb-0">Makbuz detayları için muhasebe departmanıyla iletişime geçebilirsiniz.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Silme Onay Modalı -->
<div class="modal fade" id="deletePaymentModal" tabindex="-1" role="dialog" aria-labelledby="deletePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletePaymentModalLabel">Ödeme Kaydını Sil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bu ödeme kaydını silmek istediğinizden emin misiniz?</p>
                <p class="font-weight-bold">Ödeme Tutarı: <?php echo number_format($data['payment']->amount, 2, ',', '.'); ?> ₺</p>
                <p class="font-weight-bold">Ödeme Tarihi: <?php echo date('d.m.Y', strtotime($data['payment']->payment_date)); ?></p>
                <p class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-1"></i> Bu işlem geri alınamaz ve cezanın ödeme durumu güncellenecektir.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <form action="<?php echo URLROOT; ?>/finepayments/delete/<?php echo $data['payment']->id; ?>" method="post">
                    <button type="submit" class="btn btn-danger">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 