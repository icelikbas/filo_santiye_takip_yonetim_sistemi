<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid">
    <!-- Başlık ve Geri Dön Butonu -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit text-warning mr-2"></i> Görevlendirme Düzenle
        </h1>
        <div>
            <a href="<?php echo URLROOT; ?>/assignments" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Görevlendirme Listesine Dön
            </a>
            <a href="<?php echo URLROOT; ?>/assignments/show/<?php echo $data['id']; ?>" class="btn btn-info">
                <i class="fas fa-eye mr-1"></i> Detayları Görüntüle
            </a>
        </div>
    </div>
    
    <!-- Flash Mesajları -->
    <?php flash('assignment_message'); ?>

    <!-- Görevlendirme Düzenleme Formu -->
    <form action="<?php echo URLROOT; ?>/assignments/edit/<?php echo $data['id']; ?>" method="post">
        <div class="row">
            <!-- Araç ve Sürücü Bilgileri -->
            <div class="col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-car mr-2"></i>Araç ve Sürücü Bilgileri
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Araç Seçimi -->
                        <div class="form-group">
                            <label for="vehicle_id">
                                <i class="fas fa-truck mr-1 text-primary"></i>
                                <strong>Araç Seçimi</strong> <sup class="text-danger">*</sup>
                            </label>
                            <select name="vehicle_id" id="vehicle_id" class="form-control <?php echo (!empty($data['vehicle_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">-- Araç Seçin --</option>
                                <?php foreach($data['vehicles'] as $vehicle) : ?>
                                    <option value="<?php echo $vehicle->id; ?>" <?php echo ($data['vehicle_id'] == $vehicle->id) ? 'selected' : ''; ?>>
                                        <?php echo $vehicle->plate_number . ' - ' . $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->vehicle_type . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"><?php echo $data['vehicle_id_err']; ?></div>
                            <small class="form-text text-muted">Görevlendirmek istediğiniz aracı seçin</small>
                        </div>
                        
                        <!-- Sürücü Seçimi -->
                        <div class="form-group">
                            <label for="driver_id">
                                <i class="fas fa-user mr-1 text-primary"></i>
                                <strong>Sürücü Seçimi</strong> <sup class="text-danger">*</sup>
                            </label>
                            <select name="driver_id" id="driver_id" class="form-control <?php echo (!empty($data['driver_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">-- Sürücü Seçin --</option>
                                <?php foreach($data['drivers'] as $driver) : ?>
                                    <option value="<?php echo $driver->id; ?>" <?php echo ($data['driver_id'] == $driver->id) ? 'selected' : ''; ?>>
                                        <?php echo $driver->name . ' ' . $driver->surname . ' - ' . $driver->identity_number; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"><?php echo $data['driver_id_err']; ?></div>
                            <small class="form-text text-muted">Görevlendirmek istediğiniz sürücüyü seçin</small>
                        </div>
                        
                        <!-- Durum -->
                        <div class="form-group">
                            <label for="status">Durum: <sup>*</sup></label>
                            <select name="status" id="status" class="form-control <?php echo (!empty($data['status_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="Aktif" <?php echo ($data['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                                <option value="Tamamlandı" <?php echo ($data['status'] == 'Tamamlandı') ? 'selected' : ''; ?>>Tamamlandı</option>
                                <option value="İptal" <?php echo ($data['status'] == 'İptal') ? 'selected' : ''; ?>>İptal</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['status_err']; ?></span>
                        </div>
                        
                        <!-- Konum -->
                        <div class="form-group">
                            <label for="location">Konum: <sup>*</sup></label>
                            <input type="text" name="location" id="location" class="form-control <?php echo (!empty($data['location_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['location']; ?>" placeholder="Görevlendirme konumu">
                            <span class="invalid-feedback"><?php echo $data['location_err']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tarih ve Detay Bilgileri -->
            <div class="col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-info">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-calendar-alt mr-2"></i>Tarih ve Detay Bilgileri
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Başlangıç Tarihi -->
                        <div class="form-group">
                            <label for="start_date">
                                <i class="fas fa-calendar-plus mr-1 text-info"></i>
                                <strong>Başlangıç Tarihi</strong> <sup class="text-danger">*</sup>
                            </label>
                            <input type="date" name="start_date" id="start_date" 
                                class="form-control <?php echo (!empty($data['start_date_err'])) ? 'is-invalid' : ''; ?>" 
                                value="<?php echo $data['start_date']; ?>">
                            <div class="invalid-feedback"><?php echo $data['start_date_err']; ?></div>
                            <small class="form-text text-muted">Görevlendirmenin başlangıç tarihini seçin</small>
                        </div>
                        
                        <!-- Bitiş Tarihi (Opsiyonel) -->
                        <div class="form-group">
                            <label for="end_date">
                                <i class="fas fa-calendar-check mr-1 text-info"></i>
                                <strong>Bitiş Tarihi</strong> <small class="text-muted">(Opsiyonel)</small>
                            </label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo $data['end_date']; ?>">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Tamamlanan görevlendirmeler için belirtin. Belirtilmezse, durumu "Tamamlandı" olarak değiştirdiğinizde otomatik olarak bugünün tarihi kaydedilir
                            </small>
                        </div>
                        
                        <!-- Notlar -->
                        <div class="form-group">
                            <label for="notes">Notlar:</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"><?php echo $data['notes']; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- İşlem Butonları -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body d-flex justify-content-between">
                        <a href="<?php echo URLROOT; ?>/assignments" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i> Vazgeç
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Değişiklikleri Kaydet
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status değiştiğinde bitiş tarihi kontrolü
    const statusSelect = document.getElementById('status');
    const endDateInput = document.getElementById('end_date');
    const driverSelect = document.getElementById('driver_id');
    const vehicleSelect = document.getElementById('vehicle_id');
    
    // Sürücü verilerini tutacak nesne
    const driverVehicleMap = {};
    
    // Sayfa yüklendiğinde mevcut görevlendirme verilerini sakla
    const currentVehicleId = '<?php echo $data['vehicle_id']; ?>';
    const currentDriverId = '<?php echo $data['driver_id']; ?>';
    const currentAssignmentId = '<?php echo $data['id']; ?>';
    
    console.log("Mevcut görevlendirme bilgileri:", {
        assignmentId: currentAssignmentId,
        driverId: currentDriverId,
        vehicleId: currentVehicleId
    });
    
    // Sayfa yüklendiğinde araç ve sürücü seçimlerini kontrol et
    function checkInitialSelection() {
        console.log("Sayfa yüklendiğinde araç ve sürücü seçimlerini kontrol ediyorum");
        
        // Eğer araç seçili değilse ve geçerli bir görevlendirme araç ID'si varsa
        if (!vehicleSelect.value && currentVehicleId) {
            console.log("Araç otomatik olarak seçiliyor:", currentVehicleId);
            vehicleSelect.value = currentVehicleId;
        }
        
        // Eğer şoför seçili değilse ve geçerli bir görevlendirme şoför ID'si varsa
        if (!driverSelect.value && currentDriverId) {
            console.log("Sürücü otomatik olarak seçiliyor:", currentDriverId);
            driverSelect.value = currentDriverId;
        }
        
        // Eğer hala araç seçili değilse ve sürücü seçili ise, sürücünün aktif bir atanmış aracı var mı kontrol et
        if (!vehicleSelect.value && driverSelect.value && driverVehicleMap[driverSelect.value]) {
            console.log("Sürücünün aktif atanmış aracı seçiliyor:", driverVehicleMap[driverSelect.value]);
            vehicleSelect.value = driverVehicleMap[driverSelect.value];
            alert("Sürücünün aktif görevlendirmesindeki araç otomatik olarak seçildi.");
        }
    }
    
    // AJAX ile aktif görevlendirmeleri al
    function fetchActiveAssignments() {
        // AJAX fetch için X-Requested-With header'ı eklendi
        fetch('<?php echo URLROOT; ?>/assignments/getActiveAssignmentsByDriver', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log("API'den gelen veriler:", data);
                
                // Her bir sürücü için aktif görevlendirme araç bilgisini sakla
                if (Array.isArray(data)) {
                    data.forEach(assignment => {
                        // Bu görevlendirme mevcut düzenlediğimiz görevlendirme değilse sakla
                        if (assignment.id != currentAssignmentId) {
                            driverVehicleMap[assignment.driver_id] = assignment.vehicle_id;
                            console.log(`Sürücü ${assignment.driver_id} için aktif araç: ${assignment.vehicle_id} (${assignment.plate_number || 'Plaka bilinmiyor'})`);
                        } else {
                            console.log(`Mevcut görevlendirme (ID: ${assignment.id}) eşleştirme listesine eklenmedi`);
                        }
                    });
                    console.log("Eşleştirme listesi oluşturuldu:", driverVehicleMap);
                } else {
                    console.error("API beklenmeyen bir yanıt döndürdü:", data);
                }
                
                // Sayfa ilk yüklendiğinde mevcut sürücünün diğer aktif görevlendirmeleri var mı kontrol et
                if (currentDriverId) {
                    checkDriverAssignments(currentDriverId);
                }
                
                // Sayfa yüklendiğinde seçimleri kontrol et
                checkInitialSelection();
            })
            .catch(error => {
                console.error('Aktif görevlendirmeler alınırken hata oluştu:', error);
                // Hata durumunda da seçimleri kontrol et
                checkInitialSelection();
            });
    }
    
    // Sürücünün aktif görevlendirmelerini kontrol et
    function checkDriverAssignments(driverId) {
        if (driverId && driverVehicleMap[driverId]) {
            console.log(`Sürücünün (ID: ${driverId}) başka bir aktif görevlendirmesi var!`);
            
            // Eğer bu başka bir araçsa, uyarı göster ve araç seçimini güncelle
            if (driverVehicleMap[driverId] != currentVehicleId) {
                alert(`Dikkat: Bu sürücü zaten başka bir araçta (ID: ${driverVehicleMap[driverId]}) aktif görevde!`);
                
                // Aktif araç seçilsin mi sorusu
                if (confirm('Sürücünün mevcut aktif görevdeki aracını seçmek ister misiniz?')) {
                    vehicleSelect.value = driverVehicleMap[driverId];
                }
            }
        } else {
            console.log(`Sürücünün (ID: ${driverId}) başka bir aktif görevlendirmesi yok`);
        }
    }
    
    // Sayfa yüklendiğinde aktif görevlendirmeleri al
    fetchActiveAssignments();
    
    // Araç değiştiğinde kontrol et
    vehicleSelect.addEventListener('change', function() {
        const selectedVehicleId = this.value;
        console.log(`Araç seçildi: ${selectedVehicleId}`);
        
        // Eğer araç seçilmemişse ve bir düzenleme yapılıyorsa, otomatik olarak mevcut aracı seç
        if (!selectedVehicleId && currentVehicleId) {
            console.log("Araç otomatik olarak geri seçiliyor:", currentVehicleId);
            this.value = currentVehicleId;
            alert("Görevlendirme için bir araç seçmeniz gerekmektedir. Mevcut araç tekrar seçildi.");
        }
    });
    
    // Sürücü değiştiğinde aracı otomatik seç
    driverSelect.addEventListener('change', function() {
        const selectedDriverId = this.value;
        console.log(`Sürücü seçildi: ${selectedDriverId}`);
        
        if (selectedDriverId) {
            // Aktif görevlendirme bilgisini kontrol et
            if (driverVehicleMap[selectedDriverId]) {
                // Sürücünün aktif bir görevlendirmesi varsa, işlem yap
                const activeVehicleId = driverVehicleMap[selectedDriverId];
                console.log(`Sürücü ${selectedDriverId} için aktif araç bulundu: ${activeVehicleId}`);

                // Aracı otomatik seç
                vehicleSelect.value = activeVehicleId;
                
                // Kullanıcıya bilgi ver
                alert(`Bu sürücü (ID: ${selectedDriverId}) başka bir araçta aktif görevdedir. Araç otomatik olarak seçildi.`);
                
                console.log(`Araç ${activeVehicleId} sürücü ${selectedDriverId} için otomatik seçildi`);
            } else {
                // Eğer sürücünün aktif bir görevlendirmesi yoksa, mevcut düzenlemeyi kontrol et
                if (currentDriverId === selectedDriverId && currentVehicleId) {
                    console.log(`Mevcut seçili sürücü için araç korundu: ${currentVehicleId}`);
                    vehicleSelect.value = currentVehicleId;
                } else {
                    console.log("Sürücünün aktif görevlendirmesi yok");
                    // Kullanıcının araç seçimini değiştirmeden önce onay iste
                    if (!vehicleSelect.value && currentVehicleId) {
                        vehicleSelect.value = currentVehicleId;
                        console.log("Mevcut görevlendirmenin aracı seçildi:", currentVehicleId);
                    }
                }
            }
        } else if (currentDriverId && currentVehicleId) {
            // Eğer sürücü seçimi temizlendiyse ve düzenleme sayfasındaysak, mevcut sürücüyü otomatik seç
            console.log("Sürücü otomatik olarak geri seçiliyor:", currentDriverId);
            this.value = currentDriverId;
            alert("Görevlendirme için bir sürücü seçmeniz gerekmektedir. Mevcut sürücü tekrar seçildi.");
        }
    });
    
    // Status değiştiğinde bitiş tarihi kontrolü
    statusSelect.addEventListener('change', function() {
        if (this.value === 'Tamamlandı' && !endDateInput.value) {
            const today = new Date().toISOString().split('T')[0];
            endDateInput.value = today;
        }
    });
});
</script>

<style>
.form-group label {
    font-weight: 500;
}
.invalid-feedback {
    font-weight: 500;
}
.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?> 