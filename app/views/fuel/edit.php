<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-gas-pump me-2"></i> Yakıt Kaydı Düzenle</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/fuel" class="btn btn-light float-end">
            <i class="fa fa-backward"></i> Geri
        </a>
    </div>
</div>

<?php flash('success'); ?>
<?php flash('error'); ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light">
            <form action="<?php echo URLROOT; ?>/fuel/edit/<?php echo $data['id']; ?>" method="post">
                <!-- Araç ve Sürücü Bilgileri -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3"><i class="fas fa-car me-2"></i> Araç ve Sürücü Bilgileri</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="vehicle_id" class="fw-bold">Araç <sup class="text-danger">*</sup></label>
                            <select name="vehicle_id" id="vehicle_id" class="form-control <?php echo (!empty($data['vehicle_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Araç Seçin</option>
                                <?php foreach($data['vehicles'] as $vehicle) : ?>
                                    <option value="<?php echo $vehicle->id; ?>" <?php echo ($data['vehicle_id'] == $vehicle->id) ? 'selected' : ''; ?>>
                                        <?php echo $vehicle->vehicle_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['vehicle_id_err']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="driver_id" class="fw-bold">Sürücü</label>
                            <select name="driver_id" id="driver_id" class="form-control <?php echo (!empty($data['driver_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Sürücü Seçin</option>
                                <?php foreach($data['drivers'] as $driver) : ?>
                                    <option value="<?php echo $driver->id; ?>" <?php echo ($data['driver_id'] == $driver->id) ? 'selected' : ''; ?>>
                                        <?php echo $driver->full_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['driver_id_err']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="dispenser_id" class="fw-bold">Yakıt Dağıtan Personel</label>
                            <select name="dispenser_id" id="dispenser_id" class="form-control <?php echo (!empty($data['dispenser_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Personel Seçin</option>
                                <?php foreach($data['users'] as $user) : ?>
                                    <option value="<?php echo $user->id; ?>" <?php echo ($data['dispenser_id'] == $user->id) ? 'selected' : ''; ?>>
                                        <?php echo $user->name . ' ' . $user->surname; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['dispenser_id_err']; ?></span>
                        </div>
                    </div>
                </div>
        
                <!-- Yakıt Bilgileri -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3"><i class="fas fa-gas-pump me-2"></i> Yakıt Bilgileri</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="tank_id" class="fw-bold">Yakıt Tankı <sup class="text-danger">*</sup></label>
                            <select name="tank_id" id="tank_id" class="form-control" disabled>
                                <?php foreach($data['tanks'] as $tank) : ?>
                                    <?php if ($data['tank_id'] == $tank->id) : ?>
                                        <option value="<?php echo $tank->id; ?>" selected>
                                            <?php echo $tank->name; ?> (<?php echo $tank->current_amount; ?> lt - <?php echo $tank->fuel_type; ?>)
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="tank_id" value="<?php echo $data['tank_id']; ?>">
                            <small class="form-text text-warning">
                                <i class="fas fa-info-circle"></i> Güvenlik nedeniyle yakıt kaydı oluşturulduktan sonra tank değiştirilememektedir.
                            </small>
                            <div id="tankInfo" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="fuel_type" class="fw-bold">Yakıt Türü <sup class="text-danger">*</sup></label>
                            <input type="text" id="fuel_type_display" class="form-control" value="<?php echo $data['fuel_type']; ?>" readonly disabled>
                            <input type="hidden" name="fuel_type" id="fuel_type" value="<?php echo $data['fuel_type']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="amount" class="fw-bold">Yakıt Miktarı (lt) <sup class="text-danger">*</sup></label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control <?php echo (!empty($data['amount_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['amount']; ?>">
                            <span class="invalid-feedback"><?php echo $data['amount_err']; ?></span>
                            <small id="amountHelp" class="form-text text-muted"></small>
                        </div>
                    </div>
                </div>

                <!-- Diğer Bilgiler -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i> Sayaç Bilgileri</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="km_reading" class="fw-bold">Kilometre</label>
                            <div class="input-group">
                                <input type="number" name="km_reading" id="km_reading" class="form-control <?php echo (!empty($data['km_reading_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['km_reading']; ?>" placeholder="0">
                                <span class="input-group-text">Km</span>
                            </div>
                            <span class="invalid-feedback"><?php echo $data['km_reading_err']; ?></span>
                            <small class="form-text text-muted">Kilometre bilgisi olan araçlar için</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="hour_reading" class="fw-bold">Çalışma Saati</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="hour_reading" id="hour_reading" class="form-control <?php echo (!empty($data['hour_reading_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['hour_reading']; ?>" placeholder="0.00">
                                <span class="input-group-text">Saat</span>
                            </div>
                            <span class="invalid-feedback"><?php echo $data['hour_reading_err']; ?></span>
                            <small class="form-text text-muted">Saat bilgisi olan araçlar için</small>
                        </div>
                    </div>
                </div>
            
                <!-- Tarih ve Notlar -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3"><i class="fas fa-calendar-alt me-2"></i> Tarih Bilgileri</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="date" class="fw-bold">Tarih <sup class="text-danger">*</sup></label>
                            <input type="date" name="date" id="date" class="form-control <?php echo (!empty($data['date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['date']; ?>">
                            <span class="invalid-feedback"><?php echo $data['date_err']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="time" class="fw-bold">Saat</label>
                            <input type="time" name="time" id="time" class="form-control" value="<?php echo $data['time'] ?? date('H:i'); ?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="notes" class="fw-bold">Notlar</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Ek notlar..."><?php echo $data['notes']; ?></textarea>
                        </div>
                    </div>
                </div>
        
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-save"></i> Güncelle
                        </button>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo URLROOT; ?>/fuel/show/<?php echo $data['id']; ?>" class="btn btn-secondary w-100 mb-2">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
        
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const vehicleSelect = document.getElementById('vehicle_id');
        const amountInput = document.getElementById('amount');
        const originalAmount = <?php echo $data['amount']; ?>;
        
        // Tank bilgilerini göster (artık tank değiştirilemez)
        const tankInfo = document.getElementById('tankInfo');
        const tankSelect = document.getElementById('tank_id');
        const selectedOption = tankSelect.options[tankSelect.selectedIndex]; 
        
        // Tankın mevcut miktarını al
        <?php foreach($data['tanks'] as $tank) : ?>
            <?php if ($data['tank_id'] == $tank->id) : ?>
                const currentAmount = <?php echo $tank->current_amount; ?>;
                const tankType = "<?php echo $tank->name; ?>";
                const fuelType = "<?php echo $tank->fuel_type; ?>";
            <?php endif; ?>
        <?php endforeach; ?>
        
        // Tank bilgilerini göster
        tankInfo.innerHTML = `<i class="fas fa-info-circle text-info"></i> Seçilen Tank: <strong>${tankType}</strong>, Mevcut Miktar: <strong>${currentAmount} litre</strong>`;
        
        // Yakıt tipi göster
        const fuelTypeDisplay = document.getElementById('fuel_type_display');
        const fuelTypeInput = document.getElementById('fuel_type');
        fuelTypeDisplay.value = fuelType;
        fuelTypeInput.value = fuelType;
        
        // Maksimum çekilebilecek miktar bilgisini hesapla ve göster
        const amountHelp = document.getElementById('amountHelp');
        let availableAmount = parseFloat(currentAmount) + parseFloat(originalAmount);
        amountHelp.innerHTML = `<i class="fas fa-exclamation-circle text-warning"></i> Maksimum çekilebilecek miktar: <strong>${availableAmount} litre</strong>`;
        
        // Miktar input alanı için maksimum değeri ayarla
        amountInput.max = availableAmount;
        
        // Yakıt miktarı değiştiğinde kontroller
        amountInput.addEventListener('input', function() {
            if (parseFloat(this.value) > availableAmount) {
                this.classList.add('is-invalid');
                amountHelp.classList.add('text-danger');
                amountHelp.innerHTML = `<i class="fas fa-exclamation-triangle text-danger"></i> <strong>Uyarı:</strong> Girilen miktar (${this.value} lt) tank kapasitesinden (${availableAmount} lt) fazla!`;
                document.querySelector('button[type="submit"]').disabled = true;
            } else {
                this.classList.remove('is-invalid');
                amountHelp.classList.remove('text-danger');
                amountHelp.innerHTML = `<i class="fas fa-exclamation-circle text-warning"></i> Maksimum çekilebilecek miktar: <strong>${availableAmount} litre</strong>`;
                document.querySelector('button[type="submit"]').disabled = false;
            }
        });
        
        // Araç seçildiğinde ilgili sürücüyü otomatik seç
        vehicleSelect.addEventListener('change', function() {
            const vehicleId = this.value;
            if (vehicleId) {
                // AJAX ile aracın atanmış sürücüsünü kontrol et
                fetch('<?php echo URLROOT; ?>/assignments/getDriverForVehicle/' + vehicleId)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.driver_id) {
                            document.getElementById('driver_id').value = data.driver_id;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                
                // Aracın kilometre ve saat bilgilerini getir
                fetch('<?php echo URLROOT; ?>/vehicles/getLastKm/' + vehicleId)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            if (data.last_km) {
                                document.getElementById('km_reading').value = data.last_km;
                            }
                            if (data.last_hour) {
                                document.getElementById('hour_reading').value = data.last_hour;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });
        
        // Sayfa yüklendiğinde araç seçili ise, sürücü ve kilometre bilgilerini otomatik getir
        if (vehicleSelect.value !== '') {
            vehicleSelect.dispatchEvent(new Event('change'));
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 