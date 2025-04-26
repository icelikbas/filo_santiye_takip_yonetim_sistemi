<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-exclamation-triangle mr-2"></i> <?php echo $data['title']; ?>
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/trafficfines" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Geri Dön
                    </a>
                    <a href="<?php echo URLROOT; ?>/trafficfines/edit/<?php echo $data['trafficFine']->id; ?>" class="btn btn-warning">
                        <i class="fas fa-edit mr-1"></i> Düzenle
                    </a>
                    <?php if($data['trafficFine']->payment_status != 'Ödendi') : ?>
                        <a href="<?php echo URLROOT; ?>/trafficfines/addPayment/<?php echo $data['trafficFine']->id; ?>" class="btn btn-success">
                            <i class="fas fa-plus mr-1"></i> Ödeme Ekle
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('traffic_fine_message'); ?>
    <?php flash('payment_message'); ?>

    <!-- Ceza Detay Kartı -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Ceza Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-striped">
                                <tr>
                                    <th width="30%">Ceza Numarası</th>
                                    <td><strong><?php echo $data['trafficFine']->fine_number; ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Ceza Tipi</th>
                                    <td>
                                        <?php echo $data['trafficFine']->fine_type_name; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ceza Tarihi</th>
                                    <td>
                                        <?php echo date('d.m.Y', strtotime($data['trafficFine']->fine_date)); ?>
                                        <?php if ($data['trafficFine']->fine_time) : ?>
                                            <span class="ml-2 text-muted"><?php echo date('H:i', strtotime($data['trafficFine']->fine_time)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ceza Tutarı</th>
                                    <td><strong class="text-danger"><?php echo number_format($data['trafficFine']->fine_amount, 2, ',', '.'); ?> ₺</strong></td>
                                </tr>
                                <tr>
                                    <th>Ödeme Durumu</th>
                                    <td>
                                        <?php
                                        switch($data['trafficFine']->payment_status) {
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
                                </tr>
                                <?php if ($data['trafficFine']->payment_status == 'Ödendi' && $data['trafficFine']->payment_date) : ?>
                                <tr>
                                    <th>Ödeme Tarihi</th>
                                    <td><?php echo date('d.m.Y', strtotime($data['trafficFine']->payment_date)); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if ($data['trafficFine']->payment_status == 'Ödendi' && $data['trafficFine']->payment_amount) : ?>
                                <tr>
                                    <th>Ödenen Tutar</th>
                                    <td><?php echo number_format($data['trafficFine']->payment_amount, 2, ',', '.'); ?> ₺</td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th>Ceza Lokasyonu</th>
                                    <td>
                                        <?php echo !empty($data['trafficFine']->fine_location) ? $data['trafficFine']->fine_location : '<span class="text-muted">Belirtilmemiş</span>'; ?>
                                    </td>
                                </tr>
                                <?php if (!empty($data['trafficFine']->document_url)) : ?>
                                <tr>
                                    <th>Ceza Belgesi</th>
                                    <td>
                                        <a href="<?php echo $data['trafficFine']->document_url; ?>" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-file-pdf mr-1"></i> Ceza Belgesini Görüntüle
                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th>Kayıt Tarihi</th>
                                    <td><?php echo date('d.m.Y H:i', strtotime($data['trafficFine']->created_at)); ?></td>
                                </tr>
                                <?php if (!empty($data['trafficFine']->updated_at) && $data['trafficFine']->updated_at != $data['trafficFine']->created_at) : ?>
                                <tr>
                                    <th>Son Güncelleme</th>
                                    <td><?php echo date('d.m.Y H:i', strtotime($data['trafficFine']->updated_at)); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th>Açıklama</th>
                                    <td>
                                        <?php echo !empty($data['trafficFine']->description) ? nl2br($data['trafficFine']->description) : '<span class="text-muted">Açıklama yok</span>'; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-striped">
                                <tr>
                                    <th>Araç:</th>
                                    <td>
                                        <?php 
                                            if (isset($data['vehicle'])) {
                                                echo $data['vehicle']->plate_number . ' (' . $data['vehicle']->brand . ' ' . $data['vehicle']->model . ')';
                                            } else {
                                                echo '<span class="text-muted">Bilgi Yok</span>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Sürücü:</th>
                                    <td>
                                        <?php 
                                            if (isset($data['driver']) && $data['driver']) {
                                                echo $data['driver']->name . ' ' . $data['driver']->surname;
                                            } else {
                                                echo '<span class="text-muted">Bilinmiyor</span>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ceza Lokasyonu</th>
                                    <td>
                                        <?php echo !empty($data['trafficFine']->fine_location) ? $data['trafficFine']->fine_location : '<span class="text-muted">Belirtilmemiş</span>'; ?>
                                    </td>
                                </tr>
                                <?php if (!empty($data['trafficFine']->document_url)) : ?>
                                <tr>
                                    <th>Ceza Belgesi</th>
                                    <td>
                                        <a href="<?php echo $data['trafficFine']->document_url; ?>" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-file-pdf mr-1"></i> Ceza Belgesini Görüntüle
                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th>Açıklama</th>
                                    <td>
                                        <?php echo !empty($data['trafficFine']->description) ? nl2br($data['trafficFine']->description) : '<span class="text-muted">Açıklama yok</span>'; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Ödeme Durumu Özeti -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-chart-pie mr-2"></i>Ödeme Durumu</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <div class="progress" style="height: 25px;">
                                <?php 
                                $paymentPercentage = min(100, round(($data['totalPaid'] / $data['trafficFine']->fine_amount) * 100));
                                $progressClass = ($paymentPercentage == 100) ? 'bg-success' : (($paymentPercentage > 0) ? 'bg-warning' : 'bg-danger');
                                ?>
                                <div class="progress-bar <?php echo $progressClass; ?>" role="progressbar" style="width: <?php echo $paymentPercentage; ?>%" aria-valuenow="<?php echo $paymentPercentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?php echo $paymentPercentage; ?>%
                                </div>
                            </div>
                        </div>
                        <div class="row text-left">
                            <div class="col-6">
                                <h5>Toplam Tutar:</h5>
                                <p class="h4 text-primary"><?php echo number_format($data['trafficFine']->fine_amount, 2, ',', '.'); ?> ₺</p>
                            </div>
                            <div class="col-6">
                                <h5>Ödenmiş Tutar:</h5>
                                <p class="h4 text-success"><?php echo number_format($data['totalPaid'], 2, ',', '.'); ?> ₺</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h5>Kalan Tutar:</h5>
                            <p class="h4 <?php echo ($data['remainingAmount'] > 0) ? 'text-danger' : 'text-success'; ?>">
                                <?php echo number_format($data['remainingAmount'], 2, ',', '.'); ?> ₺
                            </p>
                        </div>
                    </div>

                    <?php if($data['trafficFine']->payment_status != 'Ödendi' && $data['remainingAmount'] > 0): ?>
                        <div class="text-center mt-3">
                            <a href="<?php echo URLROOT; ?>/trafficfines/addPayment/<?php echo $data['trafficFine']->id; ?>" class="btn btn-success btn-block">
                                <i class="fas fa-plus mr-1"></i> Yeni Ödeme Ekle
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Ödeme Geçmişi -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-money-bill-wave mr-2"></i>Ödeme Geçmişi</h5>
        </div>
        <div class="card-body">
            <?php if(count($data['payments']) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Ödeme Tarihi</th>
                                <th>Tutar</th>
                                <th>Ödeme Yöntemi</th>
                                <th>Makbuz No</th>
                                <th>Notlar</th>
                                <th>Oluşturulma Tarihi</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['payments'] as $payment): ?>
                                <tr>
                                    <td><?php echo $payment->id; ?></td>
                                    <td><?php echo date('d.m.Y', strtotime($payment->payment_date)); ?></td>
                                    <td class="text-right font-weight-bold"><?php echo number_format($payment->amount, 2, ',', '.'); ?> ₺</td>
                                    <td><?php echo $payment->payment_method; ?></td>
                                    <td><?php echo $payment->receipt_number ?? '<span class="text-muted">Yok</span>'; ?></td>
                                    <td><?php echo $payment->notes ?? '<span class="text-muted">Yok</span>'; ?></td>
                                    <td><?php echo date('d.m.Y H:i', strtotime($payment->created_at)); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo URLROOT; ?>/finepayments/show/<?php echo $payment->id; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo URLROOT; ?>/finepayments/edit/<?php echo $payment->id; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger btn-delete-payment" data-id="<?php echo $payment->id; ?>" data-toggle="modal" data-target="#deletePaymentModal">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <th colspan="2" class="text-right">Toplam Ödeme:</th>
                                <th class="text-right"><?php echo number_format($data['totalPaid'], 2, ',', '.'); ?> ₺</th>
                                <th colspan="5"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i> Bu ceza için henüz bir ödeme kaydı bulunmamaktadır.
                </div>
                <div class="text-center">
                    <a href="<?php echo URLROOT; ?>/trafficfines/addPayment/<?php echo $data['trafficFine']->id; ?>" class="btn btn-success">
                        <i class="fas fa-plus mr-1"></i> İlk Ödemeyi Ekle
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Ödeme Silme Onay Modalı -->
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
                Bu ödeme kaydını silmek istediğinizden emin misiniz? Bu işlem geri alınamaz ve cezanın ödeme durumu güncellenecektir.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <form id="deletePaymentForm" action="" method="POST">
                    <button type="submit" class="btn btn-danger">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ödeme silme modalı
        $('.btn-delete-payment').on('click', function() {
            let id = $(this).data('id');
            $('#deletePaymentForm').attr('action', '<?php echo URLROOT; ?>/finepayments/delete/' + id);
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 