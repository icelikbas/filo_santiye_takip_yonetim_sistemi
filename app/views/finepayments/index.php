<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mt-4 text-primary">
                    <i class="fas fa-money-bill-wave mr-2"></i> <?php echo $data['title']; ?>
                </h2>
                <div>
                    <a href="<?php echo URLROOT; ?>/trafficfines" class="btn btn-secondary">
                        <i class="fas fa-car mr-1"></i> Trafik Cezaları
                    </a>
                </div>
            </div>
            <hr class="bg-primary">
        </div>
    </div>

    <?php flash('payment_message'); ?>

    <!-- İstatistik Kartları -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-left-primary shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Toplam Ödeme Sayısı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($data['payments']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-left-success shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Toplam Ödeme Tutarı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                $total = 0;
                                foreach($data['payments'] as $payment) {
                                    $total += $payment->amount;
                                }
                                echo number_format($total, 2, ',', '.'); 
                                ?> ₺
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-left-info shadow-sm mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Ortalama Ödeme Tutarı</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                $avgAmount = (count($data['payments']) > 0) ? ($total / count($data['payments'])) : 0;
                                echo number_format($avgAmount, 2, ',', '.'); 
                                ?> ₺
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Arama ve Filtreleme -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-search mr-2"></i>Arama ve Filtreleme</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="searchInput"><strong>Hızlı Arama:</strong></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" id="searchInput" placeholder="Makbuz no, ceza no, plaka...">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="dateFilter"><strong>Tarih Filtresi:</strong></label>
                    <select class="form-control" id="dateFilter">
                        <option value="">Tüm Tarihler</option>
                        <option value="today">Bugün</option>
                        <option value="week">Bu Hafta</option>
                        <option value="month">Bu Ay</option>
                        <option value="year">Bu Yıl</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="methodFilter"><strong>Ödeme Yöntemi:</strong></label>
                    <select class="form-control" id="methodFilter">
                        <option value="">Tüm Yöntemler</option>
                        <option value="Nakit">Nakit</option>
                        <option value="Kredi Kartı">Kredi Kartı</option>
                        <option value="Maaş Kesintisi">Maaş Kesintisi</option>
                        <option value="Diğer">Diğer</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="d-block">&nbsp;</label>
                    <button id="resetFilters" class="btn btn-secondary btn-block">
                        <i class="fas fa-sync-alt mr-1"></i> Sıfırla
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ödeme Listesi -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-list mr-2"></i>Ödeme Listesi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover data-table-buttons" id="paymentsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Ödeme Tarihi</th>
                            <th>Ceza No</th>
                            <th>Plaka</th>
                            <th>Ceza Tipi</th>
                            <th>Tutar</th>
                            <th>Ödeme Yöntemi</th>
                            <th>Makbuz No</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['payments'] as $payment) : ?>
                            <tr>
                                <td><?php echo $payment->id; ?></td>
                                <td><?php echo date('d.m.Y', strtotime($payment->payment_date)); ?></td>
                                <td><a href="<?php echo URLROOT; ?>/trafficfines/show/<?php echo $payment->fine_id; ?>"><?php echo $payment->fine_number; ?></a></td>
                                <td><?php echo $payment->plate_number; ?></td>
                                <td>
                                    <?php 
                                    if (property_exists($payment, 'fine_type_name') && !empty($payment->fine_type_name)) {
                                        echo $payment->fine_type_name;
                                    } else {
                                        echo '<span class="text-muted">Belirtilmemiş</span>';
                                    }
                                    ?>
                                </td>
                                <td class="text-right font-weight-bold"><?php echo number_format($payment->amount, 2, ',', '.'); ?> ₺</td>
                                <td><?php echo $payment->payment_method; ?></td>
                                <td>
                                    <?php 
                                    if (!empty($payment->receipt_number)) {
                                        echo $payment->receipt_number;
                                    } else {
                                        echo '<span class="text-muted">-</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo URLROOT; ?>/finepayments/show/<?php echo $payment->id; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo URLROOT; ?>/finepayments/edit/<?php echo $payment->id; ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-delete" data-id="<?php echo $payment->id; ?>" data-toggle="modal" data-target="#deleteModal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Ödeme Dağılımı Grafikleri -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-chart-pie mr-2"></i>Ödeme Yöntemi Dağılımı</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-chart-line mr-2"></i>Aylık Ödeme Dağılımı</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="monthlyPaymentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Silme Onay Modalı -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Ödeme Kaydını Sil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Bu ödeme kaydını silmek istediğinizden emin misiniz? Bu işlem geri alınamaz ve ilgili cezanın ödeme durumu güncellenecektir.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <form id="deleteForm" action="" method="POST">
                    <button type="submit" class="btn btn-danger">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Arama ve filtreleme için olayları ata
        setTimeout(function() {
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.DataTable !== 'undefined') {
                var $ = jQuery;
                var table = $('#paymentsTable').DataTable();
                
                // Hızlı arama
                $('#searchInput').on('keyup', function() {
                    table.search(this.value).draw();
                });
                
                // Ödeme yöntemi filtresi
                $('#methodFilter').on('change', function() {
                    table.column(6).search(this.value).draw();
                });
                
                // Tarih filtresi
                $('#dateFilter').on('change', function() {
                    var value = this.value;
                    var today = new Date();
                    var dateStr = '';
                    
                    if (value === 'today') {
                        dateStr = today.toLocaleDateString('tr-TR');
                        table.column(1).search(dateStr).draw();
                    } else if (value === 'week') {
                        // Son 7 günlük tarih aralığını hesapla
                        var lastWeek = new Date(today);
                        lastWeek.setDate(today.getDate() - 7);
                        
                        // Özel filtreleme fonksiyonu ekle
                        $.fn.dataTable.ext.search.push(
                            function(settings, data, dataIndex) {
                                if (value !== 'week') return true;
                                
                                var date = new Date(data[1].split('.').reverse().join('-'));
                                return date >= lastWeek && date <= today;
                            }
                        );
                        table.draw();
                    } else if (value === 'month') {
                        // Bu ay için filtreleme
                        var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                        
                        $.fn.dataTable.ext.search.push(
                            function(settings, data, dataIndex) {
                                if (value !== 'month') return true;
                                
                                var date = new Date(data[1].split('.').reverse().join('-'));
                                return date >= firstDay && date <= today;
                            }
                        );
                        table.draw();
                    } else if (value === 'year') {
                        // Bu yıl için filtreleme
                        var firstDay = new Date(today.getFullYear(), 0, 1);
                        
                        $.fn.dataTable.ext.search.push(
                            function(settings, data, dataIndex) {
                                if (value !== 'year') return true;
                                
                                var date = new Date(data[1].split('.').reverse().join('-'));
                                return date >= firstDay && date <= today;
                            }
                        );
                        table.draw();
                    } else {
                        // Filtreleri temizle
                        $.fn.dataTable.ext.search.pop();
                        table.column(1).search('').draw();
                    }
                });
                
                // Filtreleri sıfırlama
                $('#resetFilters').on('click', function() {
                    $('#searchInput').val('');
                    $('#methodFilter').val('');
                    $('#dateFilter').val('');
                    // Özel tarih filtrelerini temizle
                    $.fn.dataTable.ext.search = [];
                    table.search('').columns().search('').draw();
                });
                
                // Silme modalı
                $('.btn-delete').on('click', function() {
                    var id = $(this).data('id');
                    $('#deleteForm').attr('action', '<?php echo URLROOT; ?>/finepayments/delete/' + id);
                });
            }
        }, 1000);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Grafik oluşturma işlemleri için biraz bekle
        setTimeout(function() {
            // Ödeme yöntemi grafiği
            var methodData = {
                labels: ['Nakit', 'Kredi Kartı', 'Maaş Kesintisi', 'Diğer'],
                datasets: [{
                    data: [
                        <?php 
                        $methods = ['Nakit' => 0, 'Kredi Kartı' => 0, 'Maaş Kesintisi' => 0, 'Diğer' => 0];
                        foreach($data['payments'] as $payment) {
                            if(isset($methods[$payment->payment_method])) {
                                $methods[$payment->payment_method] += $payment->amount;
                            } else {
                                $methods['Diğer'] += $payment->amount;
                            }
                        }
                        echo implode(',', $methods);
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(201, 203, 207, 0.6)'
                    ],
                    borderColor: [
                        'rgb(54, 162, 235)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 159, 64)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1
                }]
            };

            new Chart(document.getElementById('paymentMethodChart'), {
                type: 'doughnut',
                data: methodData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                            text: 'Ödeme Yöntemine Göre Tutar Dağılımı'
                        }
                    }
                }
            });

            // Aylık ödeme grafiği
            var monthlyData = {
                labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
                datasets: [{
                    label: 'Aylık Toplam (₺)',
                    data: [
                        <?php 
                        $months = array_fill(0, 12, 0);
                        foreach($data['payments'] as $payment) {
                            $month = date('n', strtotime($payment->payment_date)) - 1;
                            $months[$month] += $payment->amount;
                        }
                        echo implode(',', $months);
                        ?>
                    ],
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            };

            new Chart(document.getElementById('monthlyPaymentChart'), {
                type: 'line',
                data: monthlyData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }, 1000);
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 