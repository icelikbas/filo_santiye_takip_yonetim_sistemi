<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><i class="fas fa-tools mr-2"></i>Servis İşlemleri</h2>
            <div class="float-right">
                <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $data['id']; ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Bakım Detayına Dön
                </a>
            </div>
        </div>
    </div>

    <?php flash('success'); ?>
    <?php flash('error'); ?>

    <div class="row">
        <!-- Araç ve Bakım Bilgileri -->
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Bakım Bilgileri</h6>
                </div>
                <div class="card-body">
                    <div class="row py-2 border-bottom">
                        <div class="col-5 font-weight-bold">Araç:</div>
                        <div class="col-7">
                            <?php echo $data['record']->plate_number; ?> - <?php echo $data['record']->brand . ' ' . $data['record']->model; ?>
                        </div>
                    </div>
                    <div class="row py-2 border-bottom">
                        <div class="col-5 font-weight-bold">Bakım Türü:</div>
                        <div class="col-7">
                            <?php if($data['record']->maintenance_type == 'Periyodik Bakım'): ?>
                                <span class="badge badge-primary"><?php echo $data['record']->maintenance_type; ?></span>
                            <?php elseif($data['record']->maintenance_type == 'Arıza'): ?>
                                <span class="badge badge-danger"><?php echo $data['record']->maintenance_type; ?></span>
                            <?php elseif($data['record']->maintenance_type == 'Lastik Değişimi'): ?>
                                <span class="badge badge-warning"><?php echo $data['record']->maintenance_type; ?></span>
                            <?php elseif($data['record']->maintenance_type == 'Yağ Değişimi'): ?>
                                <span class="badge badge-info"><?php echo $data['record']->maintenance_type; ?></span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?php echo $data['record']->maintenance_type; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row py-2 border-bottom">
                        <div class="col-5 font-weight-bold">Açıklama:</div>
                        <div class="col-7"><?php echo $data['record']->description; ?></div>
                    </div>
                    <div class="row py-2 border-bottom">
                        <div class="col-5 font-weight-bold">Planlama Tarihi:</div>
                        <div class="col-7">
                            <?php if ($data['record']->planning_date): ?>
                                <?php echo date('d.m.Y', strtotime($data['record']->planning_date)); ?>
                            <?php else: ?>
                                <span class="text-muted">Belirtilmemiş</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row py-2 border-bottom">
                        <div class="col-5 font-weight-bold">Durum:</div>
                        <div class="col-7">
                            <?php if($data['record']->status == 'Planlandı'): ?>
                                <span class="badge badge-info">Planlandı</span>
                            <?php elseif($data['record']->status == 'Devam Ediyor'): ?>
                                <span class="badge badge-warning">Devam Ediyor</span>
                            <?php elseif($data['record']->status == 'Tamamlandı'): ?>
                                <span class="badge badge-success">Tamamlandı</span>
                            <?php elseif($data['record']->status == 'İptal'): ?>
                                <span class="badge badge-danger">İptal</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row py-2 border-bottom">
                        <div class="col-5 font-weight-bold">Notlar:</div>
                        <div class="col-7">
                            <?php if ($data['record']->notes): ?>
                                <?php echo $data['record']->notes; ?>
                            <?php else: ?>
                                <span class="text-muted">Belirtilmemiş</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Servis Formu -->
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Servis İşlem Bilgileri</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/maintenance/service/<?php echo $data['id']; ?>" method="post">
                        <div class="row">
                            <!-- Başlangıç Tarihi -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Servis Başlangıç Tarihi: <sup>*</sup></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control <?php echo (!empty($data['start_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['start_date']) ? $data['start_date'] : date('Y-m-d'); ?>">
                                    <span class="invalid-feedback"><?php echo $data['start_date_err']; ?></span>
                                </div>
                            </div>
                            
                            <!-- Bitiş Tarihi -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Servis Bitiş Tarihi:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo isset($data['end_date']) ? $data['end_date'] : ''; ?>">
                                    <small class="form-text text-muted">Servis tamamlandıysa girin, devam ediyorsa boş bırakın.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="service_provider">Servis Sağlayıcı/Yetkili:</label>
                            <input type="text" name="service_provider" id="service_provider" class="form-control" value="<?php echo isset($data['service_provider']) ? $data['service_provider'] : $data['record']->service_provider; ?>">
                        </div>
                        
                        <div class="row">
                            <!-- Kilometre -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="km_reading">Kilometre: <sup>*</sup></label>
                                    <input type="text" name="km_reading" id="km_reading" class="form-control <?php echo (!empty($data['km_reading_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['km_reading']) ? $data['km_reading'] : $data['record']->km_reading; ?>">
                                    <span class="invalid-feedback"><?php echo $data['km_reading_err']; ?></span>
                                    <small class="form-text text-muted">Eğer araç kilometre ile takip ediliyorsa doldurunuz.</small>
                                </div>
                            </div>
                            
                            <!-- Çalışma Saati -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hour_reading">Çalışma Saati:</label>
                                    <input type="text" name="hour_reading" id="hour_reading" class="form-control" value="<?php echo isset($data['hour_reading']) ? $data['hour_reading'] : $data['record']->hour_reading; ?>">
                                    <small class="form-text text-muted">Eğer araç çalışma saati ile takip ediliyorsa doldurunuz.</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Maliyet -->
                        <div class="form-group">
                            <label for="cost">Maliyet (TL): <sup>*</sup></label>
                            <input type="text" name="cost" id="cost" class="form-control <?php echo (!empty($data['cost_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['cost']) ? $data['cost'] : $data['record']->cost; ?>">
                            <span class="invalid-feedback"><?php echo $data['cost_err']; ?></span>
                        </div>
                        
                        <!-- Durum -->
                        <div class="form-group">
                            <label for="status">Bakım Durumu: <sup>*</sup></label>
                            <select name="status" id="status" class="form-control <?php echo (!empty($data['status_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="Planlandı" <?php echo ($data['record']->status == 'Planlandı') ? 'selected' : ''; ?>>Planlandı</option>
                                <option value="Devam Ediyor" <?php echo ($data['record']->status == 'Devam Ediyor') ? 'selected' : ''; ?>>Devam Ediyor</option>
                                <option value="Tamamlandı" <?php echo ($data['record']->status == 'Tamamlandı') ? 'selected' : ''; ?>>Tamamlandı</option>
                                <option value="İptal" <?php echo ($data['record']->status == 'İptal') ? 'selected' : ''; ?>>İptal</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['status_err']; ?></span>
                        </div>
                        
                        <div class="row">
                            <!-- Sonraki bakım tarihi -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="next_maintenance_date">Sonraki Bakım Tarihi:</label>
                                    <input type="date" name="next_maintenance_date" id="next_maintenance_date" class="form-control" value="<?php echo isset($data['next_maintenance_date']) ? $data['next_maintenance_date'] : $data['record']->next_maintenance_date; ?>">
                                </div>
                            </div>
                            
                            <!-- Sonraki bakım km -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="next_maintenance_km">Sonraki Bakım KM:</label>
                                    <input type="number" name="next_maintenance_km" id="next_maintenance_km" class="form-control" value="<?php echo isset($data['next_maintenance_km']) ? $data['next_maintenance_km'] : $data['record']->next_maintenance_km; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sonraki bakım çalışma saati -->
                        <div class="form-group">
                            <label for="next_maintenance_hours">Sonraki Bakım Çalışma Saati:</label>
                            <input type="text" name="next_maintenance_hours" id="next_maintenance_hours" class="form-control" value="<?php echo isset($data['next_maintenance_hours']) ? $data['next_maintenance_hours'] : $data['record']->next_maintenance_hours; ?>">
                        </div>
                        
                        <!-- Notlar -->
                        <div class="form-group">
                            <label for="notes">Servis Notları:</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"><?php echo isset($data['notes']) ? $data['notes'] : $data['record']->notes; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col">
                                <input type="submit" value="Servis Bilgilerini Kaydet" class="btn btn-success btn-block">
                            </div>
                            <div class="col">
                                <a href="<?php echo URLROOT; ?>/maintenance/show/<?php echo $data['id']; ?>" class="btn btn-secondary btn-block">İptal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 