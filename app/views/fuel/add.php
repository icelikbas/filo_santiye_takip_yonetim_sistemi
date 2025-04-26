<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-gas-pump me-2"></i> Yeni Yakıt Kaydı</h1>
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
            <form action="<?php echo URLROOT; ?>/fuel/add" method="post">
                <!-- Araç ve Sürücü Bilgileri -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3"><i class="fas fa-car me-2"></i> Araç ve Sürücü Bilgileri</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="plateSearch" class="fw-bold">Plakaya Göre Ara</label>
                            <div class="input-group">
                                <input type="text" id="plateSearch" class="form-control" placeholder="Plaka no yazın..." aria-label="Plaka no">
                                <button class="btn btn-outline-secondary" type="button" id="plateSearchBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="vehicle_id" class="fw-bold">Araç <sup class="text-danger">*</sup></label>
                            <select name="vehicle_id" id="vehicle_id" class="form-control <?php echo (!empty($data['vehicle_id_err'] ?? '')) ? 'is-invalid' : ''; ?>">
                                <option value="">Araç Seçin</option>
                                <?php foreach($data['vehicles'] as $vehicle) : ?>
                                    <option value="<?php echo $vehicle->id; ?>" <?php echo ($data['vehicle_id'] == $vehicle->id) ? 'selected' : ''; ?> 
                                        data-plate="<?php echo $vehicle->plate_number; ?>"
                                        data-brand="<?php echo $vehicle->brand; ?>"
                                        data-model="<?php echo $vehicle->model; ?>"
                                        data-work-site="<?php echo isset($vehicle->work_site) ? $vehicle->work_site : ''; ?>">
                                        <?php echo $vehicle->plate_number . ' - ' . $vehicle->brand . ' ' . $vehicle->model; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['vehicle_id_err'] ?? ''; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="driver_id" class="fw-bold">Sürücü</label>
                            <select name="driver_id" id="driver_id" class="form-control <?php echo (!empty($data['driver_id_err'] ?? '')) ? 'is-invalid' : ''; ?>">
                                <option value="">Sürücü Seçin</option>
                                <?php foreach($data['drivers'] as $driver) : ?>
                                    <option value="<?php echo $driver->id; ?>" <?php echo ($data['driver_id'] == $driver->id) ? 'selected' : ''; ?>>
                                        <?php echo $driver->name . ' ' . $driver->surname; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['driver_id_err'] ?? ''; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="dispenser_id" class="fw-bold">Yakıt Dağıtan Personel</label>
                            <select name="dispenser_id" id="dispenser_id" class="form-control <?php echo (!empty($data['dispenser_id_err'] ?? '')) ? 'is-invalid' : ''; ?>">
                                <option value="">Personel Seçin</option>
                                <?php foreach($data['users'] as $user) : ?>
                                    <option value="<?php echo $user->id; ?>" <?php echo ($data['dispenser_id'] == $user->id) ? 'selected' : ''; ?>>
                                        <?php echo $user->name . (isset($user->surname) ? ' ' . $user->surname : ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['dispenser_id_err'] ?? ''; ?></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="alert alert-info mb-0" id="vehicleInfo" style="display: none;">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Araç Bilgileri</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Araç:</strong> <span id="vehicleDetails"></span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Çalışma Sahası:</strong> <span id="workSiteInfo">-</span>
                                </div>
                            </div>
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
                            <select name="tank_id" id="tank_id" class="form-control <?php echo (!empty($data['tank_id_err'] ?? '')) ? 'is-invalid' : ''; ?>">
                                <option value="">Tank Seçin</option>
                                <?php foreach($data['tanks'] as $tank) : ?>
                                    <option value="<?php echo $tank->id; ?>" <?php echo ($data['tank_id'] == $tank->id) ? 'selected' : ''; ?> 
                                            data-amount="<?php echo $tank->current_amount; ?>" data-type="<?php echo $tank->type; ?>"
                                            data-fuel-type="<?php echo $tank->fuel_type; ?>">
                                        <?php echo $tank->name; ?> (<?php echo $tank->current_amount; ?> lt - <?php echo $tank->fuel_type; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['tank_id_err'] ?? ''; ?></span>
                            <small id="tankInfo" class="form-text text-muted"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="fuel_type" class="fw-bold">Yakıt Türü <sup class="text-danger">*</sup></label>
                            <input type="text" id="fuel_type_display" class="form-control" value="" readonly>
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

                <!-- Sayaç Bilgileri -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i> Sayaç Bilgileri</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="km_reading" class="fw-bold">Kilometre</label>
                            <div class="input-group">
                                <input type="number" name="km_reading" id="km_reading" class="form-control <?php echo (!empty($data['km_reading_err'] ?? '')) ? 'is-invalid' : ''; ?>" value="<?php echo $data['km_reading']; ?>" placeholder="0">
                                <span class="input-group-text">Km</span>
                            </div>
                            <span class="invalid-feedback"><?php echo $data['km_reading_err'] ?? ''; ?></span>
                            <small class="form-text text-muted">Kilometre bilgisi olan araçlar için</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="hour_reading" class="fw-bold">Çalışma Saati</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="hour_reading" id="hour_reading" class="form-control <?php echo (!empty($data['hour_reading_err'] ?? '')) ? 'is-invalid' : ''; ?>" value="<?php echo $data['hour_reading']; ?>" placeholder="0.00">
                                <span class="input-group-text">Saat</span>
                            </div>
                            <span class="invalid-feedback"><?php echo $data['hour_reading_err'] ?? ''; ?></span>
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
                            <input type="date" name="date" id="date" class="form-control <?php echo (!empty($data['date_err'] ?? '')) ? 'is-invalid' : ''; ?>" value="<?php echo $data['date']; ?>">
                            <span class="invalid-feedback"><?php echo $data['date_err'] ?? ''; ?></span>
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
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo URLROOT; ?>/fuel" class="btn btn-secondary w-100 mb-2">
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
        // Tank seçildiğinde bilgileri göster
        document.getElementById('tank_id').addEventListener('change', function() {
            updateTankInfo();
        });
        
        // Miktar değiştiğinde kontroller
        document.getElementById('amount').addEventListener('input', function() {
            // Tank seçiliyse miktarı kontrol et
            const tankSelect = document.getElementById('tank_id');
            if(tankSelect.value !== '') {
                const selectedOption = tankSelect.options[tankSelect.selectedIndex];
                const currentAmount = parseFloat(selectedOption.getAttribute('data-amount'));
                const amountHelp = document.getElementById('amountHelp');
                
                if (parseFloat(this.value) > currentAmount) {
                    this.classList.add('is-invalid');
                    amountHelp.classList.add('text-danger');
                    amountHelp.innerHTML = `<i class="fas fa-exclamation-triangle text-danger me-1"></i> <strong>Uyarı:</strong> Girilen miktar (${this.value} lt) tank kapasitesinden (${currentAmount} lt) fazla!`;
                    // Form gönderim butonunu devre dışı bırak
                    document.querySelector('button[type="submit"]').disabled = true;
                } else {
                    this.classList.remove('is-invalid');
                    amountHelp.classList.remove('text-danger');
                    amountHelp.innerHTML = `<i class="fas fa-exclamation-circle text-warning me-1"></i> Maksimum çekilebilecek miktar: <strong>${currentAmount} litre</strong>`;
                    // Form gönderim butonunu etkinleştir
                    document.querySelector('button[type="submit"]').disabled = false;
                }
            }
        });
        
        // Araç seçildiğinde bilgileri göster ve sürücüyü otomatik seç
        document.getElementById('vehicle_id').addEventListener('change', function() {
            updateTankInfo();
            updateVehicleInfo();
            getLastDriverForVehicle();
        });
        
        // Plaka ile arama 
        document.getElementById('plateSearchBtn').addEventListener('click', function() {
            searchByPlate();
        });
        
        document.getElementById('plateSearch').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchByPlate();
            }
        });
        
        // Tank bilgilerini güncelle
        function updateTankInfo() {
            const selectedOption = document.getElementById('tank_id').options[document.getElementById('tank_id').selectedIndex];
            const tankInfo = document.getElementById('tankInfo');
            const amountInput = document.getElementById('amount');
            const amountHelp = document.getElementById('amountHelp');
            const fuelTypeDisplay = document.getElementById('fuel_type_display');
            const fuelTypeInput = document.getElementById('fuel_type');
            
            if (document.getElementById('tank_id').value === '') {
                tankInfo.textContent = '';
                amountHelp.textContent = '';
                fuelTypeDisplay.value = '';
                fuelTypeInput.value = '';
            } else {
                const currentAmount = selectedOption.getAttribute('data-amount');
                const tankType = selectedOption.getAttribute('data-type');
                const fuelType = selectedOption.getAttribute('data-fuel-type');
                
                tankInfo.innerHTML = `<i class="fas fa-info-circle text-info me-1"></i> Seçilen Tank: <strong>${tankType}</strong>, Mevcut Miktar: <strong>${currentAmount} litre</strong>`;
                amountHelp.innerHTML = `<i class="fas fa-exclamation-circle text-warning me-1"></i> Maksimum çekilebilecek miktar: <strong>${currentAmount} litre</strong>`;
                fuelTypeDisplay.value = fuelType;
                fuelTypeInput.value = fuelType;
                
                // Miktar input alanı için maksimum değeri ayarla
                amountInput.max = currentAmount;
                
                // Miktar input değerini kontrol et ve form butonunu güncelle
                const amountValue = parseFloat(amountInput.value || 0);
                const currentAmountValue = parseFloat(currentAmount);
                if (amountValue > currentAmountValue) {
                    amountInput.classList.add('is-invalid');
                    amountHelp.classList.add('text-danger');
                    amountHelp.innerHTML = `<i class="fas fa-exclamation-triangle text-danger me-1"></i> <strong>Uyarı:</strong> Girilen miktar (${amountValue} lt) tank kapasitesinden (${currentAmount} lt) fazla!`;
                    document.querySelector('button[type="submit"]').disabled = true;
                } else if (amountValue > 0) {
                    amountInput.classList.remove('is-invalid');
                    amountHelp.classList.remove('text-danger');
                    amountHelp.innerHTML = `<i class="fas fa-exclamation-circle text-warning me-1"></i> Maksimum çekilebilecek miktar: <strong>${currentAmount} litre</strong>`;
                    document.querySelector('button[type="submit"]').disabled = false;
                }
            }
        }
        
        // Araç bilgilerini güncelle
        function updateVehicleInfo() {
            const vehicleSelect = document.getElementById('vehicle_id');
            const vehicleInfo = document.getElementById('vehicleInfo');
            const vehicleDetails = document.getElementById('vehicleDetails');
            const workSiteInfo = document.getElementById('workSiteInfo');
            
            if (vehicleSelect.value === '') {
                vehicleInfo.style.display = 'none';
            } else {
                const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
                const brand = selectedOption.getAttribute('data-brand');
                const model = selectedOption.getAttribute('data-model');
                const plate = selectedOption.getAttribute('data-plate');
                const workSite = selectedOption.getAttribute('data-work-site');
                
                vehicleDetails.textContent = `${brand} ${model} (${plate})`;
                workSiteInfo.textContent = workSite || '-';
                vehicleInfo.style.display = 'block';
            }
        }
        
        // Araç için son atanan sürücüyü getir
        function getLastDriverForVehicle() {
            var vehicleId = document.getElementById('vehicle_id').value;
            if (!vehicleId) return;

            // Sürücü seçeneğini devre dışı bırak
            document.getElementById('driver_id').disabled = true;

            // Toast göster
            Swal.fire({
                title: 'Sürücü Bilgisi Alınıyor',
                text: 'Lütfen bekleyin...',
                icon: 'info',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            console.log('Araç ID ile sürücü getiriliyor:', vehicleId);

            // AJAX isteği
            fetch('<?php echo URLROOT; ?>/fuel/getLastDriverForVehicle/' + vehicleId)
                .then(response => {
                    // Ham yanıtı kontrol et
                    return response.text().then(text => {
                        console.log('Ham sunucu yanıtı:', text);
                        
                        // Yanıt OK değilse hata fırlat
                        if (!response.ok) {
                            throw new Error(`Sunucu hatası: ${response.status} - ${text.substring(0, 100)}...`);
                        }
                        
                        // JSON ayrıştırma denemesi
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('JSON ayrıştırma hatası:', e);
                            throw new Error(`Geçersiz JSON yanıtı: ${text.substring(0, 100)}...`);
                        }
                    });
                })
                .then(data => {
                    // Sürücü seçeneğini etkinleştir
                    document.getElementById('driver_id').disabled = false;
                    console.log('Sürücü verisi:', data);

                    if (data.success) {
                        document.getElementById('driver_id').value = data.driver_id;
                        console.log('Sürücü seçildi, ID:', data.driver_id, 'Kaynak:', data.source || 'belirsiz');

                        // Başarılı toast
                        Swal.fire({
                            title: 'Sürücü Bulundu',
                            text: 'Aktif görevlendirmeden sürücü bilgisi alındı',
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        console.log('Sürücü bulunamadı:', data.message);
                        // Sürücü bulunamadı toast
                        Swal.fire({
                            title: 'Sürücü Bulunamadı',
                            text: data.message || 'Bu araç için sürücü kaydı bulunamadı',
                            icon: 'warning',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                })
                .catch(error => {
                    // Hata toast
                    console.error('Sürücü bilgisi alınamadı:', error);
                    document.getElementById('driver_id').disabled = false;
                    
                    Swal.fire({
                        title: 'Hata',
                        text: 'Sürücü bilgisi alınamadı: ' + error.message,
                        icon: 'error',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
        }
        
        // Plaka ile araç arama
        function searchByPlate() {
            const plateSearch = document.getElementById('plateSearch').value.trim().toUpperCase();
            if (!plateSearch) return;
            
            const vehicleSelect = document.getElementById('vehicle_id');
            let found = false;
            
            for (let i = 0; i < vehicleSelect.options.length; i++) {
                const plate = vehicleSelect.options[i].getAttribute('data-plate');
                if (plate && plate.toUpperCase().includes(plateSearch)) {
                    vehicleSelect.selectedIndex = i;
                    found = true;
                    
                    // Araç bilgilerini güncelle
                    updateVehicleInfo();
                    getLastDriverForVehicle();
                    break;
                }
            }
            
            if (!found) {
                alert(`"${plateSearch}" plakası ile eşleşen araç bulunamadı.`);
            }
        }
        
        // İlk yükleme zamanında bilgileri güncelle
        updateTankInfo();
        updateVehicleInfo();
        
        // Eğer araç seçili ise sürücü bilgisini al
        const selectedVehicle = document.getElementById('vehicle_id').value;
        if (selectedVehicle) {
            getLastDriverForVehicle();
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 