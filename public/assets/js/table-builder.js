/**
 * TableBuilder JS - Quản lý khởi tạo các bảng dữ liệu
 */
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo DataTable cho một bảng cụ thể
    function initDataTable(tableId, config) {
        var table = $('#' + tableId);
        if (table.length === 0) return null;
        
        console.log('Khởi tạo DataTable cho bảng:', tableId);
        
        // Kiểm tra xem DataTable đã được khởi tạo chưa
        if ($.fn.DataTable.isDataTable('#' + tableId)) {
            console.log('DataTable đã tồn tại, trả về instance');
            return table.DataTable();
        }
        
        // Thiết lập các tùy chọn mặc định
        var defaultConfig = {
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
            pageLength: 10,
            responsive: true,
            dom: 'Blfrtip', // Để hiển thị các nút xuất
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn-sm btn-success hidden-button',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'btn-sm btn-danger hidden-button',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                }
            ]
        };
        
        // Kết hợp tùy chọn mặc định với tùy chọn người dùng
        var finalConfig = $.extend(true, {}, defaultConfig, config || {});
        
        console.log('Cấu hình cuối cùng cho DataTable:', finalConfig);
        
        // Khởi tạo DataTable
        try {
            var dt = table.DataTable(finalConfig);
            console.log('DataTable đã được khởi tạo thành công với buttons:', dt.buttons);
            
            // Ẩn các nút mặc định
            setTimeout(function() {
                $('.dt-buttons button').addClass('hidden-button');
                $('.dt-buttons').css('display', 'none');
            }, 100);
            
            return dt;
        } catch (error) {
            console.error('Lỗi khởi tạo DataTable:', error);
            return null;
        }
    }
    
    // Tải các thư viện cần thiết
    function loadRequiredLibraries(callback) {
        console.log('Kiểm tra và tải các thư viện cần thiết');
        
        var requiredScripts = [];
        
        // Kiểm tra DataTables
        if (typeof $.fn.DataTable === 'undefined') {
            requiredScripts.push('https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js');
            requiredScripts.push('https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js');
        }
        
        // Kiểm tra Buttons
        if (typeof $.fn.dataTable.Buttons === 'undefined') {
            requiredScripts.push('https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js');
            requiredScripts.push('https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js');
            requiredScripts.push('https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js');
            requiredScripts.push('https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js');
        }
        
        // Kiểm tra JSZip
        if (typeof JSZip === 'undefined') {
            requiredScripts.push('https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js');
        }
        
        // Kiểm tra PDFMake
        if (typeof pdfMake === 'undefined') {
            requiredScripts.push('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js');
            requiredScripts.push('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js');
        }
        
        // Nếu không cần tải thêm thư viện, gọi callback ngay
        if (requiredScripts.length === 0) {
            console.log('Tất cả thư viện đã được tải');
            if (callback) callback();
            return;
        }
        
        console.log('Cần tải các thư viện sau:', requiredScripts);
        
        // Tải các script theo thứ tự
        var loadScript = function(index) {
            if (index >= requiredScripts.length) {
                console.log('Tất cả thư viện đã được tải xong');
                if (callback) callback();
                return;
            }
            
            var script = document.createElement('script');
            script.src = requiredScripts[index];
            script.onload = function() {
                console.log('Đã tải:', requiredScripts[index]);
                loadScript(index + 1);
            };
            script.onerror = function() {
                console.error('Lỗi khi tải:', requiredScripts[index]);
                loadScript(index + 1);
            };
            document.head.appendChild(script);
        };
        
        loadScript(0);
    }
    
    // Xử lý các nút xuất thủ công
    function setupManualExportButtons(tableId, dataTable) {
        console.log('Thiết lập các nút xuất thủ công cho bảng:', tableId);
        
        // Chỉ thiết lập các nút nếu có lớp .manual-export-buttons
        var exportContainer = document.querySelector('.manual-export-buttons[data-tableid="' + tableId + '"]');
        if (!exportContainer) {
            // Tìm container chung
            exportContainer = document.querySelector('.manual-export-buttons');
            if (!exportContainer) {
                console.warn('Không tìm thấy container cho các nút xuất dữ liệu');
                return;
            }
        }
        
        console.log('Container xuất dữ liệu:', exportContainer);
        
        // Lấy các nút xuất
        var excelBtn = exportContainer.querySelector('.btn-excel');
        var pdfBtn = exportContainer.querySelector('.btn-pdf');
        
        console.log('Nút Excel:', excelBtn);
        console.log('Nút PDF:', pdfBtn);
        
        // Kiểm tra DataTable
        if (!dataTable) {
            console.warn('Không tìm thấy DataTable cho bảng:', tableId);
        }
        
        // Thiết lập sự kiện cho nút Excel
        if (excelBtn) {
            excelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Click nút Excel cho bảng:', tableId);
                exportTableToExcel(tableId);
            });
        }
        
        // Thiết lập sự kiện cho nút PDF
        if (pdfBtn) {
            pdfBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Click nút PDF cho bảng:', tableId);
                exportTableToPdf(tableId);
            });
        }
    }
    
    // Xuất bảng sang Excel
    function exportTableToExcel(tableId) {
        console.log('Xuất Excel nội bộ cho bảng:', tableId);
        var table = document.getElementById(tableId);
        if (!table) return;
        
        try {
            if (typeof XLSX === 'undefined') {
                console.error('Thư viện XLSX chưa được tải');
                alert('Thư viện xuất Excel chưa được tải. Vui lòng tải lại trang và thử lại.');
                return;
            }
            
            // Tạo clone của bảng để xử lý, loại bỏ cột thao tác
            var cloneTable = table.cloneNode(true);
            var headers = cloneTable.querySelectorAll('th');
            var lastColumnIndex = headers.length - 1;
            
            // Ẩn cột thao tác (cuối cùng)
            var rows = cloneTable.querySelectorAll('tr');
            rows.forEach(function(row) {
                var cells = row.querySelectorAll('th, td');
                if (cells.length > 0 && lastColumnIndex >= 0 && lastColumnIndex < cells.length) {
                    cells[lastColumnIndex].style.display = 'none';
                }
            });
            
            // Tạo workbook với tên bảng
            var worksheet = XLSX.utils.table_to_sheet(cloneTable);
            var workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Dữ liệu");
            
            // Xuất file
            var excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
            saveExcelFile(excelBuffer, 'bang_du_lieu_' + new Date().toISOString().slice(0, 10) + '.xlsx');
            
            console.log('Xuất Excel thành công!');
        } catch (e) {
            console.error('Lỗi khi xuất Excel:', e);
            alert('Có lỗi khi xuất dữ liệu sang Excel: ' + e.message);
        }
    }
    
    // Hàm hỗ trợ lưu file Excel
    function saveExcelFile(buffer, filename) {
        var blob = new Blob([buffer], { type: 'application/octet-stream' });
        
        // Tạo đường dẫn tải xuống
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = filename;
        
        // Thêm vào trang web và kích hoạt sự kiện click
        document.body.appendChild(link);
        link.click();
        
        // Dọn dẹp
        setTimeout(function() {
            document.body.removeChild(link);
            window.URL.revokeObjectURL(link.href);
        }, 100);
    }
    
    // Xuất bảng sang PDF
    function exportTableToPdf(tableId) {
        console.log('Xuất PDF nội bộ cho bảng:', tableId);
        var table = document.getElementById(tableId);
        if (!table) return;
        
        try {
            if (typeof html2canvas === 'undefined' || typeof jspdf === 'undefined') {
                console.error('Thư viện html2canvas hoặc jspdf chưa được tải');
                alert('Thư viện xuất PDF chưa được tải. Vui lòng tải lại trang và thử lại.');
                return;
            }
            
            // Tạo clone của bảng để xử lý, loại bỏ cột thao tác
            var cloneTable = table.cloneNode(true);
            var headers = cloneTable.querySelectorAll('th');
            var lastColumnIndex = headers.length - 1;
            
            // Ẩn cột thao tác (cuối cùng)
            var rows = cloneTable.querySelectorAll('tr');
            rows.forEach(function(row) {
                var cells = row.querySelectorAll('th, td');
                if (cells.length > 0 && lastColumnIndex >= 0 && lastColumnIndex < cells.length) {
                    cells[lastColumnIndex].style.display = 'none';
                }
            });
            
            // Chèn clone vào trang nhưng không hiển thị
            var container = document.createElement('div');
            container.style.position = 'absolute';
            container.style.left = '-9999px';
            container.appendChild(cloneTable);
            document.body.appendChild(container);
            
            // Sử dụng html2canvas để chuyển đổi bảng sang hình ảnh
            html2canvas(cloneTable, {
                scale: 1.5, // Tăng độ phân giải
                useCORS: true, // Cho phép CORS để tải hình ảnh từ các nguồn khác
                allowTaint: true, // Cho phép "taint" canvas để xử lý một số trường hợp đặc biệt
                logging: false // Tắt logging để giảm độ phức tạp
            }).then(function(canvas) {
                // Tạo file PDF từ canvas
                var imgData = canvas.toDataURL('image/png');
                
                // Tính toán kích thước để phù hợp với trang A4
                var pdf;
                try {
                    pdf = new jspdf.jsPDF({
                        orientation: 'portrait',
                        unit: 'mm',
                        format: 'a4'
                    });
                } catch (e) {
                    console.error('Lỗi khi tạo jsPDF:', e);
                    alert('Thư viện PDF không hoạt động đúng. Vui lòng thử lại với trình duyệt khác.');
                    document.body.removeChild(container);
                    return;
                }
                
                var pageWidth = pdf.internal.pageSize.getWidth();
                var pageHeight = pdf.internal.pageSize.getHeight();
                var imageWidth = canvas.width;
                var imageHeight = canvas.height;
                
                // Tính toán tỉ lệ để hình ảnh vừa với trang PDF
                var ratio = Math.min(pageWidth / imageWidth, pageHeight / imageHeight);
                var imgWidth = imageWidth * ratio - 20; // Margin 10mm mỗi bên
                var imgHeight = imageHeight * ratio - 20; // Margin 10mm mỗi bên
                
                // Thêm hình ảnh vào PDF
                pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
                
                // Tải xuống file PDF
                try {
                    pdf.save('bang_du_lieu_' + new Date().toISOString().slice(0, 10) + '.pdf');
                    console.log('Xuất PDF thành công!');
                } catch (e) {
                    console.error('Lỗi khi lưu PDF:', e);
                    alert('Có lỗi khi tải xuống file PDF. Vui lòng thử lại.');
                }
                
                // Dọn dẹp
                document.body.removeChild(container);
            }).catch(function(error) {
                console.error('Lỗi khi tạo canvas từ bảng:', error);
                alert('Có lỗi khi tạo file PDF. Vui lòng thử lại.');
                document.body.removeChild(container);
            });
        } catch (e) {
            console.error('Lỗi khi xuất PDF:', e);
            alert('Có lỗi khi xuất dữ liệu ra PDF: ' + e.message);
        }
    }
    
    // Thiết lập các bộ lọc cho bảng
    function setupFilters(tableId, dataTable) {
        var filterForm = document.querySelector('.table-filter-form');
        if (!filterForm) return;
        
        var filterSubmit = filterForm.querySelector('.table-filter-submit');
        var filterReset = filterForm.querySelector('.table-filter-reset');
        
        // Xử lý sự kiện nút lọc
        if (filterSubmit) {
            filterSubmit.addEventListener('click', function() {
                applyFilters(tableId, dataTable);
            });
        }
        
        // Xử lý sự kiện nút làm mới
        if (filterReset) {
            filterReset.addEventListener('click', function() {
                resetFilters(filterForm, tableId, dataTable);
            });
        }

        // Console.log để debug
        console.log('Đã thiết lập bộ lọc cho bảng', tableId);
        console.log('Filter form:', filterForm);
        console.log('Filter submit button:', filterSubmit);
        console.log('Filter reset button:', filterReset);
    }
    
    // Áp dụng bộ lọc
    function applyFilters(tableId, dataTable) {
        if (!dataTable) {
            console.error('Không thể áp dụng bộ lọc: DataTable không tồn tại');
            return;
        }
        
        console.log('Đang áp dụng bộ lọc cho bảng', tableId);
        
        // Xóa tất cả bộ lọc custom trước đó
        $.fn.dataTable.ext.search = [];
        
        // Xóa tất cả bộ lọc trước đó
        dataTable.search('').columns().search('').draw();
        
        // Áp dụng bộ lọc text
        document.querySelectorAll('.text-filter').forEach(function(filter) {
            var column = filter.getAttribute('data-column');
            var value = filter.value;
            
            if (value) {
                console.log('Áp dụng bộ lọc text:', column, value);
                dataTable.column(column).search(value);
            }
        });
        
        // Áp dụng bộ lọc select
        document.querySelectorAll('.select-filter').forEach(function(filter) {
            var column = filter.getAttribute('data-column');
            var value = filter.value;
            
            if (value) {
                console.log('Áp dụng bộ lọc select:', column, value);
                dataTable.column(column).search(value);
            }
        });
        
        // Áp dụng bộ lọc ngày
        document.querySelectorAll('.date-filter').forEach(function(filter) {
            var column = filter.getAttribute('data-column');
            var value = filter.value;
            
            if (value) {
                console.log('Áp dụng bộ lọc ngày:', column, value);
                dataTable.column(column).search(value);
            }
        });
        
        // Áp dụng bộ lọc khoảng ngày
        document.querySelectorAll('.date-range-filter').forEach(function(filter) {
            var column = filter.getAttribute('data-column');
            var fromDate = filter.querySelector('.date-from').value;
            var toDate = filter.querySelector('.date-to').value;
            
            if (fromDate || toDate) {
                console.log('Áp dụng bộ lọc khoảng ngày:', column, fromDate, toDate);
                
                // Custom filtering function
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    if (settings.nTable.id !== tableId) {
                        return true;
                    }
                    
                    var dateValue = data[column];
                    
                    // Chuyển đổi chuỗi ngày thành đối tượng Date
                    var date = new Date(dateValue);
                    var from = fromDate ? new Date(fromDate) : null;
                    var to = toDate ? new Date(toDate) : null;
                    
                    // Nếu không có giá trị ngày hoặc ngày không hợp lệ, trả về true
                    if (!dateValue || isNaN(date.getTime())) {
                        return true;
                    }
                    
                    // Kiểm tra khoảng
                    var result = true;
                    if (from) {
                        result = result && date >= from;
                    }
                    if (to) {
                        result = result && date <= to;
                    }
                    
                    return result;
                });
            }
        });
        
        // Áp dụng bộ lọc khoảng số
        document.querySelectorAll('.number-range-filter').forEach(function(filter) {
            var column = filter.getAttribute('data-column');
            var minValue = filter.querySelector('.number-min').value;
            var maxValue = filter.querySelector('.number-max').value;
            
            if (minValue || maxValue) {
                console.log('Áp dụng bộ lọc khoảng số:', column, minValue, maxValue);
                
                // Custom filtering function
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    if (settings.nTable.id !== tableId) {
                        return true;
                    }
                    
                    var value = parseFloat(data[column].replace(/[^\d.-]/g, ''));
                    var min = minValue ? parseFloat(minValue) : null;
                    var max = maxValue ? parseFloat(maxValue) : null;
                    
                    // Nếu không có giá trị hoặc không phải số, trả về true
                    if (isNaN(value)) {
                        return true;
                    }
                    
                    // Kiểm tra khoảng
                    var result = true;
                    if (min !== null) {
                        result = result && value >= min;
                    }
                    if (max !== null) {
                        result = result && value <= max;
                    }
                    
                    return result;
                });
            }
        });
        
        // Vẽ lại bảng với bộ lọc mới
        dataTable.draw();
    }
    
    // Làm mới bộ lọc
    function resetFilters(form, tableId, dataTable) {
        console.log('Đang làm mới bộ lọc cho bảng', tableId);
        
        // Xóa tất cả giá trị trong form
        form.reset();
        
        // Xóa tất cả bộ lọc custom
        $.fn.dataTable.ext.search = [];
        
        // Xóa tất cả bộ lọc và vẽ lại bảng
        if (dataTable) {
            dataTable.search('').columns().search('').draw();
        }
    }
    
    // Thêm CSS để ẩn các nút datatable mặc định
    function addRequiredStyles() {
        var style = document.createElement('style');
        style.textContent = `
            .hidden-button { 
                display: none !important; 
            }
            .dt-buttons {
                display: none !important;
            }
            .bi {
                display: inline-block !important;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Khởi tạo tất cả các bảng có class table-builder
    function initAllTables() {
        console.log('Đang khởi tạo tất cả các bảng...');
        
        if (typeof $.fn.DataTable === 'undefined') {
            console.warn('DataTables không được tải. Các bảng sẽ hiển thị ở chế độ thông thường.');
            loadRequiredLibraries(initAllTables);
            return;
        }
        
        // Thêm class table-builder-enabled vào body để đảm bảo table-builder.js được tải
        document.documentElement.classList.add('table-builder-enabled');
        
        // Thêm các styles cần thiết
        addRequiredStyles();
        
        document.querySelectorAll('table.table-builder').forEach(function(table) {
            var tableId = table.id;
            if (!tableId) {
                tableId = 'table_' + Math.random().toString(36).substr(2, 9);
                table.id = tableId;
            }
            
            console.log('Khởi tạo bảng:', tableId);
            
            // Lấy cấu hình từ data attribute nếu có
            var config = {};
            try {
                if (table.dataset.config) {
                    config = JSON.parse(table.dataset.config);
                }
            } catch (e) {
                console.error('Lỗi khi phân tích cấu hình bảng:', e);
            }
            
            // Thêm cấu hình nút button và chuẩn bị cho xuất dữ liệu
            config.dom = 'Blfrtip'; // Thêm 'B' vào DOM để hỗ trợ các nút xuất dữ liệu
            config.buttons = [
                {
                    extend: 'excel',
                    text: 'Excel',
                    className: 'hidden-button',
                    exportOptions: {
                        columns: ':visible:not(:last-child)' // Loại trừ cột thao tác
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'hidden-button',
                    exportOptions: {
                        columns: ':visible:not(:last-child)' // Loại trừ cột thao tác
                    }
                }
            ];
            
            var dataTable = initDataTable(tableId, config);
            if (dataTable) {
                setupManualExportButtons(tableId, dataTable);
                setupFilters(tableId, dataTable);
                setupActionButtons();
            }
        });
    }
    
    // Thêm icons vào các nút hành động
    function setupActionButtons() {
        // Kiểm tra và thêm bootstrap-icons nếu cần
        if (!document.querySelector('link[href*="bootstrap-icons"]')) {
            var iconLink = document.createElement('link');
            iconLink.rel = 'stylesheet';
            iconLink.href = 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css';
            document.head.appendChild(iconLink);
        }
        
        // Kiểm tra các nút hành động và thêm icon
        document.querySelectorAll('.btn-group a.btn, .action-buttons a.btn').forEach(function(button) {
            // Nếu nút đã có icon, bỏ qua
            if (button.querySelector('i.bi')) {
                var icon = button.querySelector('i.bi');
                if (!icon.style.display) {
                    icon.style.display = 'inline-block';
                }
                return;
            }
            
            // Xác định loại nút thông qua class
            var icon = '';
            if (button.classList.contains('btn-info')) {
                icon = '<i class="bi bi-eye" style="display:inline-block;"></i>';
            } else if (button.classList.contains('btn-primary')) {
                icon = '<i class="bi bi-pencil" style="display:inline-block;"></i>';
            } else if (button.classList.contains('btn-danger')) {
                icon = '<i class="bi bi-trash" style="display:inline-block;"></i>';
            }
            
            // Nếu không có nội dung và có icon, thêm icon
            if (button.innerHTML.trim() === '' && icon) {
                button.innerHTML = icon;
            }
        });
    }
    
    // Ẩn các nút DataTable tự động tạo
    function hideDuplicateButtons() {
        document.querySelectorAll('.dt-buttons, .dt-button').forEach(function(el) {
            el.style.display = 'none';
        });
        
        // Thêm CSS để ẩn các nút
        var style = document.createElement('style');
        style.textContent = '.dt-buttons, .dt-button, .buttons-excel, .buttons-pdf { display: none !important; }';
        document.head.appendChild(style);
        
        console.log('Đã ẩn các nút DataTable tự động tạo');
    }
    
    // Kiểm tra và khởi tạo TableBuilder
    console.log('Bắt đầu khởi tạo TableBuilder');
    
    // Tải các thư viện cần thiết trước
    loadRequiredLibraries(function() {
        console.log('Các thư viện đã được tải, bắt đầu khởi tạo');
        
        // Tự động thêm class để buộc file JS được tải
        document.documentElement.classList.add('table-builder-enabled');
        
        // Thêm styles cần thiết
        addRequiredStyles();
        
        // Khởi tạo tất cả các bảng
        setTimeout(function() {
            initAllTables();
            setupActionButtons();
            hideDuplicateButtons();
        }, 500);
    });
    
    // Định nghĩa các phương thức toàn cục để sử dụng từ bên ngoài
    window.TableBuilder = {
        init: function(tableId, config) {
            var dataTable = initDataTable(tableId, config);
            if (dataTable) {
                setupManualExportButtons(tableId, dataTable);
                setupFilters(tableId, dataTable);
                setupActionButtons();
            }
            return dataTable;
        },
        reinitAll: initAllTables,
        applyFilters: applyFilters,
        resetFilters: resetFilters,
        setupActions: setupActionButtons,
        hideDuplicateButtons: hideDuplicateButtons,
        exportToExcel: function(tableId) {
            exportTableToExcel(tableId);
        },
        exportToPdf: function(tableId) {
            exportTableToPdf(tableId);
        }
    };
}); 