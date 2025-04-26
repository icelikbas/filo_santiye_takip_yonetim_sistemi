<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-money-bill-wave mr-2"></i> Ödeme Ekle
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/trafficfines/show/<?php echo $data['fine_id']; ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Geri Dön
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
                    <form action="<?php echo URLROOT; ?>/trafficfines/addPayment/<?php echo $data['fine_id']; ?>" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_date"><strong>Ödeme Tarihi:</strong></label>
                                    <input type="date" name="payment_date" class="form-control <?php echo (!empty($data['payment_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['payment_date']; ?>">
                                    <span class="invalid-feedback"><?php echo $data['payment_date_err']; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount"><strong>Ödeme Tutarı (₺):</strong></label>
                                    <input type="text" name="amount" class="form-control <?php echo (!empty($data['amount_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['amount']; ?>" placeholder="0,00">
                                    <span class="invalid-feedback"><?php echo $data['amount_err']; ?></span>
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
                                    <span class="invalid-feedback"><?php echo $data['payment_method_err']; ?></span>
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

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save mr-2"></i> Ödemeyi Kaydet
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
                    <div class="mb-4">
                        <table class="table table-sm table-bordered">
                            <tr class="bg-light">
                                <th>Ceza No</th>
                                <th>Ceza Tipi</th>
                                <th>Ceza Tutarı</th>
                                <th>Ödenen Tutar</th>
                                <th>Kalan Tutar</th>
                            </tr>
                            <tr>
                                <td><?php echo $data['trafficFine']->fine_number; ?></td>
                                <td><?php echo $data['trafficFine']->fine_type_name; ?></td>
                                <td><?php echo number_format($data['trafficFine']->fine_amount, 2, ',', '.'); ?> ₺</td>
                                <td><?php echo number_format($data['totalPaid'], 2, ',', '.'); ?> ₺</td>
                                <td><strong class="text-<?php echo ($data['remainingAmount'] > 0) ? 'danger' : 'success'; ?>"><?php echo number_format($data['remainingAmount'], 2, ',', '.'); ?> ₺</strong></td>
                            </tr>
                        </table>
                    </div>

                    <div class="progress mt-3" style="height: 25px;">
                        <?php 
                        $paymentPercentage = min(100, round(($data['totalPaid'] / $data['trafficFine']->fine_amount) * 100));
                        $progressClass = ($paymentPercentage == 100) ? 'bg-success' : (($paymentPercentage > 0) ? 'bg-warning' : 'bg-danger');
                        ?>
                        <div class="progress-bar <?php echo $progressClass; ?>" role="progressbar" style="width: <?php echo $paymentPercentage; ?>%" aria-valuenow="<?php echo $paymentPercentage; ?>" aria-valuemin="0" aria-valuemax="100">
                            <?php echo $paymentPercentage; ?>%
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mevcut Ödemeler -->
            <?php if(count($data['payments']) > 0): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="m-0 font-weight-bold"><i class="fas fa-history mr-2"></i>Önceki Ödemeler</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach($data['payments'] as $payment): ?>
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?php echo date('d.m.Y', strtotime($payment->payment_date)); ?></h6>
                                        <small><?php echo $payment->payment_method; ?></small>
                                    </div>
                                    <p class="mb-1 font-weight-bold"><?php echo number_format($payment->amount, 2, ',', '.'); ?> ₺</p>
                                    <?php if(!empty($payment->receipt_number)): ?>
                                        <small>Makbuz No: <?php echo $payment->receipt_number; ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 