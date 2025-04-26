<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-edit mr-2"></i> Ödeme Düzenle
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/trafficfines/show/<?php echo $data['fine_id']; ?>" class="btn btn-secondary">
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
            <!-- Ödeme Formu -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-file-invoice-dollar mr-2"></i>Ödeme Bilgileri</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/finepayments/edit/<?php echo $data['id']; ?>" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_date"><strong>Ödeme Tarihi:</strong></label>
                                    <input type="date" name="payment_date" class="form-control <?php echo (!empty($data['payment_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['payment_date']; ?>">
                                    <span class="invalid-feedback"><?php echo (!empty($data['payment_date_err'])) ? $data['payment_date_err'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount"><strong>Ödeme Tutarı (₺):</strong></label>
                                    <input type="text" name="amount" class="form-control <?php echo (!empty($data['amount_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['amount']; ?>" placeholder="0,00">
                                    <span class="invalid-feedback"><?php echo (!empty($data['amount_err'])) ? $data['amount_err'] : ''; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method"><strong>Ödeme Yöntemi:</strong></label>
                                    <select name="payment_method" class="form-control <?php echo (!empty($data['payment_method_err'])) ? 'is-invalid' : ''; ?>">
                                        <option value="">Seçiniz</option>
                                        <option value="Nakit" <?php echo ($data['payment_method'] == 'Nakit') ? 'selected' : ''; ?>>Nakit</option>
                                        <option value="Kredi Kartı" <?php echo ($data['payment_method'] == 'Kredi Kartı') ? 'selected' : ''; ?>>Kredi Kartı</option>
                                        <option value="Maaş Kesintisi" <?php echo ($data['payment_method'] == 'Maaş Kesintisi') ? 'selected' : ''; ?>>Maaş Kesintisi</option>
                                        <option value="Diğer" <?php echo ($data['payment_method'] == 'Diğer') ? 'selected' : ''; ?>>Diğer</option>
                                    </select>
                                    <span class="invalid-feedback"><?php echo (!empty($data['payment_method_err'])) ? $data['payment_method_err'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receipt_number"><strong>Makbuz/Dekont No:</strong></label>
                                    <input type="text" name="receipt_number" class="form-control" value="<?php echo $data['receipt_number']; ?>" placeholder="Varsa makbuz numarası">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes"><strong>Notlar:</strong></label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Ödeme ile ilgili notlar..."><?php echo $data['notes']; ?></textarea>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Ödeme bilgilerini değiştirmek, cezanın ödeme durumunu da otomatik olarak güncelleyecektir.
                        </div>

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save mr-2"></i> Değişiklikleri Kaydet
                            </button>
                        </div>
                    </form>
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
                                if (property_exists($data['trafficFine'], 'fine_type_name') && !empty($data['trafficFine']->fine_type_name)) {
                                    echo $data['trafficFine']->fine_type_name;
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

            <!-- Ödeme İpuçları -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-lightbulb mr-2"></i>İpuçları</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success mr-2"></i> Ödeme tarihini doğru girdiğinizden emin olun.
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success mr-2"></i> Tutar için ondalık ayırıcı olarak virgül (,) kullanabilirsiniz.
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success mr-2"></i> Ödeme yöntemini mutlaka seçin.
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check-circle text-success mr-2"></i> Makbuz numarası ileride referans amaçlı faydalı olabilir.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 