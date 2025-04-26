<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-edit mr-2"></i> Trafik Cezası Düzenle
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/trafficfines/show/<?php echo $data['id']; ?>" class="btn btn-info">
                        <i class="fas fa-eye mr-1"></i> Ceza Detayı
                    </a>
                    <a href="<?php echo URLROOT; ?>/trafficfines" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Geri Dön
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('traffic_fine_message'); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-file-alt mr-2"></i>Ceza Bilgileri</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/trafficfines/edit/<?php echo $data['id']; ?>" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fine_number"><strong>Ceza Numarası:</strong> <span class="text-danger">*</span></label>
                                    <input type="text" name="fine_number" class="form-control <?php echo (!empty($data['fine_number_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['fine_number']; ?>" placeholder="Ceza numarasını giriniz">
                                    <span class="invalid-feedback"><?php echo (!empty($data['fine_number_err'])) ? $data['fine_number_err'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_id"><strong>Araç:</strong> <span class="text-danger">*</span></label>
                                    <select name="vehicle_id" id="vehicle_id" class="form-control <?php echo (!empty($data['vehicle_id_err'])) ? 'is-invalid' : ''; ?>">
                                        <option value="">Araç Seçiniz</option>
                                        <?php foreach ($data['vehicles'] as $vehicle) : ?>
                                            <option value="<?php echo $vehicle->id; ?>" <?php echo ($data['vehicle_id'] == $vehicle->id) ? 'selected' : ''; ?>>
                                                <?php echo $vehicle->plate_number . ' - ' . $vehicle->brand . ' ' . $vehicle->model; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo (!empty($data['vehicle_id_err'])) ? $data['vehicle_id_err'] : ''; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driver_id"><strong>Sürücü:</strong></label>
                                    <select name="driver_id" id="driver_id" class="form-control">
                                        <option value="">Sürücü Seçiniz (Opsiyonel)</option>
                                        <?php foreach ($data['drivers'] as $driver) : ?>
                                            <option value="<?php echo $driver->id; ?>" <?php echo ($data['driver_id'] == $driver->id) ? 'selected' : ''; ?>>
                                                <?php echo $driver->name . ' ' . $driver->surname; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fine_type_id"><strong>Ceza Tipi:</strong> <span class="text-danger">*</span></label>
                                    <select name="fine_type_id" id="fine_type_id" class="form-control <?php echo (!empty($data['fine_type_id_err'])) ? 'is-invalid' : ''; ?>" onchange="updateDefaultAmount(this.value)">
                                        <option value="">Ceza Tipi Seçiniz</option>
                                        <?php foreach ($data['fineTypes'] as $type) : ?>
                                            <option value="<?php echo $type->id; ?>" data-amount="<?php echo $type->default_amount; ?>" <?php echo ($data['fine_type_id'] == $type->id) ? 'selected' : ''; ?>>
                                                <?php echo $type->name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo (!empty($data['fine_type_id_err'])) ? $data['fine_type_id_err'] : ''; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fine_date"><strong>Ceza Tarihi:</strong> <span class="text-danger">*</span></label>
                                    <input type="date" name="fine_date" class="form-control <?php echo (!empty($data['fine_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['fine_date']; ?>">
                                    <span class="invalid-feedback"><?php echo (!empty($data['fine_date_err'])) ? $data['fine_date_err'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fine_time"><strong>Ceza Saati:</strong></label>
                                    <input type="time" name="fine_time" class="form-control" value="<?php echo $data['fine_time']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fine_amount"><strong>Ceza Tutarı (₺):</strong> <span class="text-danger">*</span></label>
                                    <input type="text" name="fine_amount" id="fine_amount" class="form-control <?php echo (!empty($data['fine_amount_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['fine_amount']; ?>" placeholder="0,00">
                                    <span class="invalid-feedback"><?php echo (!empty($data['fine_amount_err'])) ? $data['fine_amount_err'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fine_location"><strong>Ceza Lokasyonu:</strong></label>
                                    <input type="text" name="fine_location" class="form-control" value="<?php echo $data['fine_location']; ?>" placeholder="Cezanın kesildiği yer">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_status"><strong>Ödeme Durumu:</strong> <span class="text-danger">*</span></label>
                                    <select name="payment_status" id="payment_status" class="form-control <?php echo (!empty($data['payment_status_err'])) ? 'is-invalid' : ''; ?>" onchange="togglePaymentFields(this.value)">
                                        <option value="">Seçiniz</option>
                                        <option value="Ödenmedi" <?php echo ($data['payment_status'] == 'Ödenmedi') ? 'selected' : ''; ?>>Ödenmedi</option>
                                        <option value="Ödendi" <?php echo ($data['payment_status'] == 'Ödendi') ? 'selected' : ''; ?>>Ödendi</option>
                                        <option value="Taksitli Ödeme" <?php echo ($data['payment_status'] == 'Taksitli Ödeme') ? 'selected' : ''; ?>>Taksitli Ödeme</option>
                                        <option value="İtiraz Edildi" <?php echo ($data['payment_status'] == 'İtiraz Edildi') ? 'selected' : ''; ?>>İtiraz Edildi</option>
                                    </select>
                                    <span class="invalid-feedback"><?php echo (!empty($data['payment_status_err'])) ? $data['payment_status_err'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6 payment-field" style="display:none;">
                                <div class="form-group">
                                    <label for="payment_date"><strong>Ödeme Tarihi:</strong></label>
                                    <input type="date" name="payment_date" class="form-control" value="<?php echo $data['payment_date']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row payment-field" style="display:none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_amount"><strong>Ödenen Tutar (₺):</strong></label>
                                    <input type="text" name="payment_amount" class="form-control" value="<?php echo $data['payment_amount']; ?>" placeholder="0,00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="document_url"><strong>Belge URL:</strong></label>
                                    <input type="text" name="document_url" class="form-control" value="<?php echo $data['document_url']; ?>" placeholder="Ceza belgesi linki">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description"><strong>Açıklama:</strong></label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Ceza hakkında açıklama..."><?php echo $data['description']; ?></textarea>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-1"></i> <strong>Not:</strong> Trafik cezası bilgilerini değiştirdiğinizde, ödeme durumu güncellenir. Ödeme kayıtlarını ayrıca yönetmek için ceza detay sayfasını kullanın.
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
    </div>
</div>

<script>
    // Ödeme alanlarını göster/gizle
    function togglePaymentFields(status) {
        if (status === 'Ödendi' || status === 'Taksitli Ödeme') {
            document.querySelectorAll('.payment-field').forEach(function(el) {
                el.style.display = 'block';
            });
        } else {
            document.querySelectorAll('.payment-field').forEach(function(el) {
                el.style.display = 'none';
            });
        }
    }

    // Sayfa yüklendiğinde ödeme durumuna göre alanları ayarla
    document.addEventListener('DOMContentLoaded', function() {
        togglePaymentFields(document.getElementById('payment_status').value);
    });

    // Ceza tipine göre varsayılan tutarı doldur
    function updateDefaultAmount(selectedType) {
        if (selectedType) {
            const option = document.querySelector(`#fine_type_id option[value="${selectedType}"]`);
            if (option) {
                const amount = option.getAttribute('data-amount');
                if (amount) {
                    document.getElementById('fine_amount').value = amount.replace('.', ',');
                }
            }
        }
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 