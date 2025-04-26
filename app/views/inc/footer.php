                    </div>
                </div>
            </main>
        </div>
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">&copy; <?php echo date('Y'); ?> <?php echo SITENAME; ?> | Tüm Hakları Saklıdır</span>
        </div>
    </footer>

    <!-- Bootstrap JS - CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS - Uyumlu CDN Sürümleri -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    
    <!-- DataTables Türkçe dil desteği (CDN'e bağlı olmamak için inline) -->
    <script>
        // DataTables Türkçe dil tanımlaması
        var dataTablesTurkishLanguage = {
            "sDecimal": ",",
            "sEmptyTable": "Tabloda herhangi bir veri mevcut değil",
            "sInfo": "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
            "sInfoEmpty": "Kayıt yok",
            "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "Sayfada _MENU_ kayıt göster",
            "sLoadingRecords": "Yükleniyor...",
            "sProcessing": "İşleniyor...",
            "sSearch": "Ara:",
            "sZeroRecords": "Eşleşen kayıt bulunamadı",
            "oPaginate": {
                "sFirst": "İlk",
                "sLast": "Son",
                "sNext": "Sonraki",
                "sPrevious": "Önceki"
            },
            "oAria": {
                "sSortAscending": ": artan sütun sıralamasını aktifleştir",
                "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
            },
            "select": {
                "rows": {
                    "_": "%d kayıt seçildi",
                    "0": "",
                    "1": "1 kayıt seçildi"
                }
            }
        };
        
        // Global dil ayarı
        if (typeof $.fn !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
            $.extend(true, $.fn.DataTable.defaults, {
                language: dataTablesTurkishLanguage
            });
        }
    </script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Ana JS Dosyası - En son yüklenmeli -->
    <script src="<?php echo getPublicUrl('js/main.js'); ?>"></script>
    
    <script>
        // Bootstrap 5 dropdown menüler için
        document.addEventListener('DOMContentLoaded', function() {
            // Bootstrap 5 için dropdown'ları manuel başlat
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
            
            // jQuery ve DataTables kontrolü - kütüphanelerin yüklü olduğundan emin ol
            var initDataTablesAndSelect2 = function() {
                try {
                    // jQuery kontrolü
                    if (typeof jQuery === 'undefined') {
                        console.warn('jQuery yüklenemedi, 500ms sonra tekrar deneniyor...');
                        setTimeout(initDataTablesAndSelect2, 500);
                        return;
                    }
                    
                    var $ = jQuery;
                    
                    // DataTables kontrolü
                    if (typeof $.fn === 'undefined' || typeof $.fn.DataTable === 'undefined') {
                        console.warn('DataTables yüklenemedi, 500ms sonra tekrar deneniyor...');
                        setTimeout(initDataTablesAndSelect2, 500);
                        return;
                    }
                    
                    console.log('jQuery ve DataTables başarıyla yüklendi, tabloları başlatılıyor...');
                    
                    // Filtrelenebilir tablolar için ayarlar
                    $('table.data-table').each(function() {
                        try {
                            if (!$.fn.dataTable.isDataTable(this)) {
                                var tableId = $(this).attr('id') || 'table-' + Math.random().toString(36).substring(2, 9);
                                if (!$(this).attr('id')) {
                                    $(this).attr('id', tableId);
                                }
                                
                                $(this).DataTable({
                                    responsive: false,
                                    language: {
                                        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
                                    },
                                    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                                         "<'row'<'col-sm-12'tr>>" +
                                         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
                                    columnDefs: [
                                        { orderable: false, targets: -1 } // Son sütun (işlemler) sıralanabilir olmasın
                                    ]
                                });
                                
                                console.log('DataTable initialized for #' + tableId);
                            }
                        } catch (err) {
                            console.error('DataTable başlatılırken hata oluştu:', err);
                        }
                    });

                    // Filtrelenebilir ve dışa aktarma butonlu tablolar
                    $('table.data-table-buttons').each(function() {
                        try {
                            if (!$.fn.dataTable.isDataTable(this)) {
                                var tableId = $(this).attr('id') || 'table-buttons-' + Math.random().toString(36).substring(2, 9);
                                if (!$(this).attr('id')) {
                                    $(this).attr('id', tableId);
                                }
                                
                                $(this).DataTable({
                                    responsive: false,
                                    language: {
                                        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
                                    },
                                    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                                         "<'row'<'col-sm-12'tr>>" +
                                         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
                                         "<'row'<'col-sm-12'B>>",
                                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
                                    columnDefs: [
                                        { orderable: false, targets: -1 } // Son sütun (işlemler) sıralanabilir olmasın
                                    ],
                                    buttons: [
                                        {extend: 'copy', text: 'Kopyala'},
                                        {extend: 'excel', text: 'Excel'},
                                        {extend: 'pdf', text: 'PDF'},
                                        {extend: 'print', text: 'Yazdır'},
                                        {extend: 'colvis', text: 'Sütunlar'}
                                    ]
                                });
                                
                                console.log('DataTable with buttons initialized for #' + tableId);
                            }
                        } catch (err) {
                            console.error('DataTable başlatılırken hata oluştu:', err);
                        }
                    });
                    
                    // Select2'leri başlat
                    if (typeof $.fn.select2 !== 'undefined') {
                        try {
                            $('select.select2').each(function() {
                                if (!$(this).hasClass('select2-hidden-accessible')) {
                                    $(this).select2({
                                        theme: 'bootstrap-5',
                                        width: '100%',
                                        language: 'tr'
                                    });
                                }
                            });
                            console.log('Select2 bileşenleri başarıyla başlatıldı');
                        } catch (err) {
                            console.error('Select2 başlatılırken hata oluştu:', err);
                        }
                    } else {
                        console.warn('Select2 kütüphanesi bulunamadı');
                    }
                    
                    // Özel tablo sınıfları için özel işleme
                    try {
                        // bootstrap-datatable sınıfına sahip tabloları da başlat (eski sürüm uyumluluğu için)
                        $('table.bootstrap-datatable').each(function() {
                            try {
                                if (!$.fn.dataTable.isDataTable(this)) {
                                    var tableId = $(this).attr('id') || 'bootstrap-datatable-' + Math.random().toString(36).substring(2, 9);
                                    if (!$(this).attr('id')) {
                                        $(this).attr('id', tableId);
                                    }
                                    
                                    $(this).DataTable({
                                        responsive: false,
                                        language: {
                                            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
                                        },
                                        pageLength: 5,
                                        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                                             "<'row'<'col-sm-12'tr>>" +
                                             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                                        order: [[0, "desc"]],
                                        columnDefs: [
                                            { orderable: false, targets: -1 } // Son sütun (işlemler) sıralanabilir olmasın
                                        ]
                                    });
                                    
                                    console.log('Bootstrap DataTable initialized for #' + tableId);
                                }
                            } catch (err) {
                                console.error('Bootstrap DataTable başlatılırken hata oluştu:', err);
                            }
                        });
                    } catch (error) {
                        console.error('Özel tablolar başlatılırken beklenmedik bir hata oluştu:', error);
                    }
                    
                } catch (error) {
                    console.error('Bileşenler başlatılırken beklenmedik bir hata oluştu:', error);
                    // 1 saniye sonra tekrar dene
                    setTimeout(initDataTablesAndSelect2, 1000);
                }
            };
            
            // Kütüphanelerin yüklenmesi için bekle ve başlat
            setTimeout(initDataTablesAndSelect2, 100);
            
            // DataTables çizimi sonrası butonları zorlayıcı görünür hale getirmek için
            setTimeout(function() {
                if (typeof jQuery !== 'undefined') {
                    jQuery('.btn-sm').css({
                        'display': 'inline-block',
                        'visibility': 'visible',
                        'opacity': '1'
                    });
                    
                    // İşlem sütunlarını düzenle
                    jQuery('td:last-child').css({
                        'min-width': '140px',
                        'white-space': 'nowrap',
                        'text-align': 'center'
                    });
                    
                    console.log('Butonlar manuel olarak görünür hale getirildi');
                }
            }, 800);
            
            // Flash mesajları için
            const flashMessages = document.querySelectorAll('.alert-dismissible');
            if (flashMessages.length > 0) {
                flashMessages.forEach(function(flash) {
                    setTimeout(function() {
                        if (typeof jQuery !== 'undefined') {
                            jQuery(flash).fadeOut('slow');
                        } else {
                            flash.style.display = 'none';
                        }
                    }, 4000);
                });
            }
        });
    </script>

    <?php
    // Sayfaya özel ekstra JS dosyaları veya kod bloğu
    if(isset($data['extraJS'])) {
        echo $data['extraJS'];
    }
    ?>
</body>
</html> 