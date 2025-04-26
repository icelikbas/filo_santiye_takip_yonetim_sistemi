<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><i class="fas fa-tools mr-2"></i>Bakım Planlaması</h2>
            <a href="<?php echo URLROOT; ?>/maintenance" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left mr-1"></i> Geri Dön
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Bakım Planlama Bilgileri</h5>
        </div>
        <div class="card-body">
            <?php flash('success'); ?>
            <?php flash('error'); ?>

            <form action="<?php echo URLROOT; ?>/maintenance/add" method="post">
                <div class="row">
                    <!-- Araç Seçimi -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vehicle_id">Araç: <sup>*</sup></label>
                            <select name="vehicle_id" id="vehicle_id" class="form-control <?php echo (!empty($data['vehicle_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Araç Seçin</option>
                                <?php foreach($data['vehicles'] as $vehicle) : ?>
                                    <option value="<?php echo $vehicle->id; ?>" <?php echo ($data['vehicle_id'] == $vehicle->id) ? 'selected' : ''; ?>>
                                        <?php echo $vehicle->plate_number . ' - ' . $vehicle->vehicle_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['vehicle_id_err']; ?></span>
                        </div>
                    </div>
                    
                    <!-- Bakım Türü -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="maintenance_type">Bakım Türü: <sup>*</sup></label>
                            <select name="maintenance_type" id="maintenance_type" class="form-control <?php echo (!empty($data['maintenance_type_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Bakım Türü Seçin</option>
                                <option value="Periyodik Bakım" <?php echo ($data['maintenance_type'] == 'Periyodik Bakım') ? 'selected' : ''; ?>>Periyodik Bakım</option>
                                <option value="Arıza" <?php echo ($data['maintenance_type'] == 'Arıza') ? 'selected' : ''; ?>>Arıza</option>
                                <option value="Lastik Değişimi" <?php echo ($data['maintenance_type'] == 'Lastik Değişimi') ? 'selected' : ''; ?>>Lastik Değişimi</option>
                                <option value="Yağ Değişimi" <?php echo ($data['maintenance_type'] == 'Yağ Değişimi') ? 'selected' : ''; ?>>Yağ Değişimi</option>
                                <option value="Diğer" <?php echo ($data['maintenance_type'] == 'Diğer') ? 'selected' : ''; ?>>Diğer</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['maintenance_type_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Bakım Açıklaması -->
                <div class="form-group">
                    <label for="description">Bakım Açıklaması: <sup>*</sup></label>
                    <textarea name="description" id="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" rows="3"><?php echo $data['description']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                </div>
                
                <div class="row">
                    <!-- Planlama Tarihi -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="planning_date">Planlama Tarihi: <sup>*</sup></label>
                            <input type="date" name="planning_date" id="planning_date" class="form-control <?php echo (!empty($data['planning_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['planning_date']; ?>">
                            <span class="invalid-feedback"><?php echo $data['planning_date_err']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Kilometre/Saat Bilgisi -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="km_reading">Kilometre:</label>
                            <input type="text" name="km_reading" id="km_reading" class="form-control <?php echo (!empty($data['km_reading_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['km_reading']; ?>">
                            <span class="invalid-feedback"><?php echo $data['km_reading_err']; ?></span>
                            <small class="form-text text-muted">Eğer araç kilometre ile takip ediliyorsa doldurunuz.</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="hour_reading">Çalışma Saati:</label>
                            <input type="text" name="hour_reading" id="hour_reading" class="form-control <?php echo (!empty($data['hour_reading_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['hour_reading']; ?>">
                            <span class="invalid-feedback"><?php echo $data['hour_reading_err']; ?></span>
                            <small class="form-text text-muted">Eğer araç çalışma saati ile takip ediliyorsa doldurunuz.</small>
                        </div>
                    </div>
                </div>
                
                <!-- Notlar -->
                <div class="form-group">
                    <label for="notes">Notlar:</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3"><?php echo $data['notes']; ?></textarea>
                </div>
                
                <!-- Hidden fields -->
                <input type="hidden" name="status" value="Planlandı">
                
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Planla" class="btn btn-success btn-block">
                    </div>
                    <div class="col">
                        <a href="<?php echo URLROOT; ?>/maintenance" class="btn btn-secondary btn-block">İptal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 