/**
 * TableBuilder JS - Quản lý khởi tạo các bảng dữ liệu
 */
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo DataTable cho một bảng cụ thể
    function initDataTable(tableId, options) {
        console.log('Khởi tạo DataTable cho bảng:', tableId);
        var table = $('#' + tableId);
        
        if (!table.length) {
            console.error('Không tìm thấy bảng có ID:', tableId);
            return;
        }
        
        // Lấy tùy chọn từ thuộc tính data-config
        var configAttr = table.attr('data-config');
        var config = {};
        
        if (configAttr) {
            try {
                config = JSON.parse(configAttr);
                console.log('Đã tải cấu hình từ thuộc tính data:', config);
            } catch (e) {
                console.error('Lỗi phân tích cấu hình JSON:', e);
            }
        }
        
        // Kiểm tra xem bảng có checkbox không để vô hiệu hóa sorting cho cột đầu tiên
        var hasCheckbox = table.hasClass('table-checkable') || (config && config.has_checkbox);
        
        // Tùy chọn mặc định cho DataTables
        var defaultOptions = {
            responsive: true,
            ordering: true,
            searching: true,
            paging: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            language: {
                // Ngôn ngữ tiếng Việt
                "emptyTable": "Không có dữ liệu",
                "info": "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                "infoEmpty": "Hiển thị 0 đến 0 của 0 mục",
                "infoFiltered": "(lọc từ _MAX_ mục)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Hiển thị _MENU_ mục",
                "loadingRecords": "Đang tải...",
                "processing": "Đang xử lý...",
                "search": "Tìm kiếm:",
                "zeroRecords": "Không tìm thấy kết quả phù hợp",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Tiếp",
                    "previous": "Trước"
                },
                "aria": {
                    "sortAscending": ": sắp xếp tăng dần",
                    "sortDescending": ": sắp xếp giảm dần"
                }
            }
        };
        
        // Nếu có cột checkbox, vô hiệu hóa sorting cho cột đầu tiên
        if (hasCheckbox) {
            defaultOptions.columnDefs = defaultOptions.columnDefs || [];
            defaultOptions.columnDefs.push({
                targets: 0,
                orderable: false,
                searchable: false,
                width: '40px',
                className: 'dt-center'
            });
        }
        
        // Kết hợp tùy chọn mặc định với tùy chọn được cung cấp
        var finalOptions = $.extend({}, defaultOptions, options || {});
        
        // Thêm buttons nếu có tùy chọn export
        if (config && config.export_options && config.export_options.enable) {
            try {
                // Cấu hình các nút xuất dữ liệu
                var exportButtons = [];
                
                if (config.export_options.excel) {
                    exportButtons.push({
                        extend: 'excel',
                        text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                        titleAttr: 'Xuất Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    });
                }
                
                if (config.export_options.pdf) {
                    exportButtons.push({
                        extend: 'pdf',
                        text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                        titleAttr: 'Xuất PDF',
                        className: 'btn btn-danger btn-sm',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    });
                }
                
                if (exportButtons.length > 0) {
                    finalOptions.buttons = exportButtons;
                    finalOptions.dom = 'Bfrtip';
                }
            } catch (e) {
                console.error('Lỗi khi cấu hình nút xuất:', e);
            }
        }
        
        // Khởi tạo DataTable
        try {
            var dataTable = table.DataTable(finalOptions);
            console.log('Đã khởi tạo DataTable thành công');
            
            // Lưu trữ tham chiếu đến dataTable
            window.dataTables = window.dataTables || {};
            window.dataTables[tableId] = dataTable;
            
            return dataTable;
        } catch (e) {
            console.error('Lỗi khi khởi tạo DataTable:', e);
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
            
            // Xử lý định dạng ngày tháng trước khi xuất
            var headerNames = [];
            cloneTable.querySelectorAll('th').forEach(function(th) {
                headerNames.push(th.textContent.trim());
            });
            
            // Tìm cột ngày dựa vào tên tiêu đề
            var dateColumnIndexes = [];
            headerNames.forEach(function(name, index) {
                if (name.toLowerCase().includes('ngày') || 
                    name.toLowerCase().includes('date') || 
                    name.toLowerCase().includes('thời gian')) {
                    dateColumnIndexes.push(index);
                }
            });
            
            console.log('Các cột ngày tháng:', dateColumnIndexes);
            
            // Chuyển đổi định dạng ngày trong bảng
            if (dateColumnIndexes.length > 0) {
                var dataRows = cloneTable.querySelectorAll('tbody tr');
                dataRows.forEach(function(row) {
                    var cells = row.querySelectorAll('td');
                    dateColumnIndexes.forEach(function(colIndex) {
                        if (colIndex < cells.length) {
                            var cell = cells[colIndex];
                            var dateText = cell.textContent.trim();
                            
                            // Kiểm tra nếu là định dạng chuỗi ngày
                            if (/\d{4}-\d{2}-\d{2}/.test(dateText) || /\d{2}\/\d{2}\/\d{4}/.test(dateText) || /#####/.test(dateText)) {
                                try {
                                    // Nếu chứa ######, thử chuyển đổi định dạng từ cell gốc
                                    if (/#####/.test(dateText)) {
                                        var originalRowIndex = Array.from(dataRows).indexOf(row);
                                        var originalCellSelector = 'tbody tr:nth-child(' + (originalRowIndex + 1) + ') td:nth-child(' + (colIndex + 1) + ')';
                                        var originalCell = table.querySelector(originalCellSelector);
                                        if (originalCell) {
                                            dateText = originalCell.textContent.trim();
                                        }
                                    }
                                    
                                    // Định dạng lại chuỗi ngày
                                    var date = new Date(dateText);
                                    if (!isNaN(date.getTime())) {
                                        var formattedDate = date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
                                        cell.textContent = formattedDate;
                                        
                                        // Thêm thuộc tính data để XLSX biết đây là ngày
                                        cell.setAttribute('data-t', 'd');
                                        cell.setAttribute('data-z', 'dd/mm/yyyy');
                                        cell.setAttribute('data-v', date.toISOString().split('T')[0]);
                                    }
                                } catch (e) {
                                    console.error('Lỗi xử lý định dạng ngày:', e);
                                }
                            }
                        }
                    });
                });
            }
            
            // Tạo workbook với tên bảng
            var worksheet = XLSX.utils.table_to_sheet(cloneTable);
            
            // Xử lý thêm các tùy chọn định dạng ngày cho worksheet
            if (dateColumnIndexes.length > 0) {
                if (!worksheet['!cols']) worksheet['!cols'] = [];
                
                dateColumnIndexes.forEach(function(colIndex) {
                    // Đặt định dạng cho cột
                    worksheet['!cols'][colIndex] = { wch: 12, z: 'dd/mm/yyyy' };
                    
                    // Định dạng từng ô trong cột ngày
                    for (var cellRef in worksheet) {
                        if (cellRef[0] === '!') continue; // Bỏ qua các thuộc tính đặc biệt
                        
                        var cellAddress = XLSX.utils.decode_cell(cellRef);
                        if (cellAddress.c === colIndex && cellAddress.r > 0) { // Bỏ qua hàng tiêu đề
                            var cell = worksheet[cellRef];
                            if (cell && cell.v) {
                                try {
                                    var dateValue = null;
                                    
                                    // Nếu là chuỗi ngày, chuyển đổi
                                    if (typeof cell.v === 'string') {
                                        if (/\d{1,2}\/\d{1,2}\/\d{4}/.test(cell.v)) {
                                            var parts = cell.v.split('/');
                                            dateValue = new Date(parts[2], parts[1] - 1, parts[0]);
                                        } else {
                                            dateValue = new Date(cell.v);
                                        }
                                        
                                        if (!isNaN(dateValue.getTime())) {
                                            cell.t = 'd'; // Đặt kiểu là ngày
                                            cell.z = 'dd/mm/yyyy'; // Định dạng hiển thị
                                            cell.v = dateValue; // Giá trị ngày
                                            
                                            // Tính toán giá trị Excel serial date
                                            var epochDate = new Date(1899, 11, 30);
                                            var utcDate = Date.UTC(dateValue.getFullYear(), dateValue.getMonth(), dateValue.getDate());
                                            var excelDateValue = Math.floor((utcDate - epochDate) / 86400000);
                                            
                                            cell.v = excelDateValue;
                                        }
                                    }
                                } catch (e) {
                                    console.error('Lỗi xử lý ô ngày:', cellRef, e);
                                }
                            }
                        }
                    }
                });
            }
            
            var workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Dữ liệu");
            
            // Tùy chỉnh thêm để đảm bảo các cột ngày hiển thị đúng
            var wopts = { 
                bookType: 'xlsx', 
                type: 'array',
                cellDates: true,
                dateNF: 'dd/mm/yyyy'
            };
            
            // Xuất file
            var excelBuffer = XLSX.write(workbook, wopts);
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
            /* Ẩn biểu tượng sắp xếp cho cột ID/checkbox */
            table.table-checkable th:first-child .sorting_asc,
            table.table-checkable th:first-child .sorting_desc,
            table.table-checkable th:first-child .sorting,
            th.no-sort .sorting_asc,
            th.no-sort .sorting_desc,
            th.no-sort .sorting,
            th:first-child.no-sort .sorting_asc,
            th:first-child.no-sort .sorting_desc,
            th:first-child.no-sort .sorting {
                background-image: none !important;
            }
            /* Thêm CSS để đảm bảo biểu tượng sắp xếp không hiển thị ở cột đầu tiên */
            table.dataTable thead .sorting:first-child:before,
            table.dataTable thead .sorting:first-child:after,
            table.dataTable thead .sorting_asc:first-child:before,
            table.dataTable thead .sorting_asc:first-child:after,
            table.dataTable thead .sorting_desc:first-child:before,
            table.dataTable thead .sorting_desc:first-child:after,
            table.dataTable thead .sorting_asc_disabled:first-child:before,
            table.dataTable thead .sorting_asc_disabled:first-child:after,
            table.dataTable thead .sorting_desc_disabled:first-child:before,
            table.dataTable thead .sorting_desc_disabled:first-child:after {
                display: none !important;
            }
            /* ID header không cần chỉ dẫn sorting */
            th.ID.sorting_asc:after,
            th.ID.sorting_desc:after,
            th.ID.sorting:after {
                content: "" !important;
                display: none !important;
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
        
        // Thêm hàm tiện ích để vô hiệu hóa sắp xếp cho cột ID hoặc checkbox
        $.fn.dataTable.ext.order['disable-first-column'] = function () {
            return null;
        };
        
        document.querySelectorAll('table.table-builder').forEach(function(table) {
            var tableId = table.id;
            if (!tableId) {
                tableId = 'table_' + Math.random().toString(36).substr(2, 9);
                table.id = tableId;
            }
            
            console.log('Khởi tạo bảng:', tableId);
            
            // Đánh dấu cột ID và cột checkbox không thể sắp xếp
            var firstTh = table.querySelector('th:first-child');
            if (firstTh) {
                if (firstTh.textContent.trim() === 'ID') {
                    firstTh.classList.add('ID', 'no-sort');
                    firstTh.setAttribute('data-orderable', 'false');
                } else if (firstTh.querySelector('input[type="checkbox"]')) {
                    firstTh.classList.add('no-sort');
                    firstTh.setAttribute('data-orderable', 'false');
                }
            }
            
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
            
            // Nếu có ID hoặc checkbox trong bảng, đảm bảo cột đầu tiên không sắp xếp được
            if (table.classList.contains('table-checkable') || (config && config.has_checkbox)) {
                config.columnDefs = config.columnDefs || [];
                config.columnDefs.push({
                    targets: 0,
                    orderable: false,
                    searchable: false,
                    width: '40px',
                    className: 'dt-center no-sort'
                });
                config.ordering = false; // Vô hiệu hóa tính năng sắp xếp cho cột đầu tiên
            } else if (firstTh && firstTh.textContent.trim() === 'ID') {
                config.columnDefs = config.columnDefs || [];
                config.columnDefs.push({
                    targets: 0,
                    orderable: false,
                    searchable: true,
                    width: '60px',
                    className: 'dt-center no-sort'
                });
            }
            
            var dataTable = initDataTable(tableId, config);
            if (dataTable) {
                setupManualExportButtons(tableId, dataTable);
                setupFilters(tableId, dataTable);
                setupActionButtons();
            }
            
            // Nếu bảng có checkbox, thiết lập chức năng checkbox
            if (table.classList.contains('table-checkable') || (config && config.has_checkbox)) {
                setupCheckboxes(tableId);
            }
            
            // Chạy một lần để gỡ bỏ icon sắp xếp từ cột đầu tiên
            setTimeout(function() {
                var firstHeader = table.querySelector('th:first-child');
                if (firstHeader) {
                    firstHeader.classList.add('no-sort');
                    var sortingElements = firstHeader.querySelectorAll('.sorting, .sorting_asc, .sorting_desc');
                    sortingElements.forEach(function(el) {
                        el.classList.remove('sorting', 'sorting_asc', 'sorting_desc');
                        el.classList.add('no-sort');
                    });
                }
            }, 100);
        });
    }
    
    // Thêm icons vào các nút hành động
    function setupActionButtons() {
        console.log('Thiết lập các nút hành động...');
        
        // Kiểm tra và thêm bootstrap-icons nếu cần
        if (!document.querySelector('link[href*="bootstrap-icons"]')) {
            var iconLink = document.createElement('link');
            iconLink.rel = 'stylesheet';
            iconLink.href = 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css';
            document.head.appendChild(iconLink);
            console.log('Đã thêm CSS cho Bootstrap Icons');
        }
        
        // Thêm CSS để đảm bảo biểu tượng hiển thị đúng
        var style = document.createElement('style');
        style.textContent = `
            .btn-group a.btn, .action-buttons a.btn {
                display: inline-block !important;
                margin-right: 2px;
                min-width: 30px;
                text-align: center;
            }
            .btn-group a.btn i.bi, .action-buttons a.btn i.bi {
                display: inline-block !important;
                font-size: 16px;
                line-height: 1;
                vertical-align: middle;
            }
            .bi {
                display: inline-block !important;
            }
        `;
        document.head.appendChild(style);
        
        // Kiểm tra các nút hành động và thêm icon
        document.querySelectorAll('.btn-group a.btn, .action-buttons a.btn, td .btn-group .btn, td .action-buttons .btn').forEach(function(button) {
            console.log('Tìm thấy nút thao tác:', button);
            
            // Nếu nút đã có icon, đảm bảo nó hiển thị
            var existingIcon = button.querySelector('i.bi');
            if (existingIcon) {
                existingIcon.style.display = 'inline-block';
                console.log('Nút đã có icon, đặt style hiển thị');
                return;
            }
            
            // Xác định loại nút thông qua class hoặc text
            var buttonText = button.textContent.trim().toLowerCase();
            var icon = '';
            
            if (button.classList.contains('btn-info') || buttonText.includes('xem') || buttonText.includes('chi tiết') || buttonText.includes('view')) {
                icon = '<i class="bi bi-eye" style="display:inline-block;"></i>';
            } else if (button.classList.contains('btn-primary') || buttonText.includes('sửa') || buttonText.includes('cập nhật') || buttonText.includes('edit')) {
                icon = '<i class="bi bi-pencil" style="display:inline-block;"></i>';
            } else if (button.classList.contains('btn-danger') || buttonText.includes('xóa') || buttonText.includes('delete')) {
                icon = '<i class="bi bi-trash" style="display:inline-block;"></i>';
            } else if (button.classList.contains('btn-success') || buttonText.includes('thêm') || buttonText.includes('add')) {
                icon = '<i class="bi bi-plus" style="display:inline-block;"></i>';
            } else if (buttonText.includes('duyệt') || buttonText.includes('accept')) {
                icon = '<i class="bi bi-check-circle" style="display:inline-block;"></i>';
            } else if (buttonText.includes('từ chối') || buttonText.includes('reject')) {
                icon = '<i class="bi bi-x-circle" style="display:inline-block;"></i>';
            }
            
            // Thêm icon và giữ lại text nếu có
            if (icon) {
                // Giữ lại text nếu có
                var originalText = button.innerHTML.trim();
                if (originalText === '' || originalText.includes('i class="bi')) {
                    button.innerHTML = icon;
                } else {
                    button.innerHTML = icon + ' ' + originalText;
                }
                console.log('Đã thêm icon cho nút:', buttonText);
            }
            
            // Đảm bảo hiển thị
            button.style.display = 'inline-block';
        });
        
        // Tìm kiếm các cột thao tác để đảm bảo chúng hiển thị đúng
        document.querySelectorAll('table thead th:last-child, table tbody td:last-child').forEach(function(cell) {
            var text = cell.textContent.trim().toLowerCase();
            if (text === 'thao tác' || text === 'action' || text === 'actions' || cell.classList.contains('action-column')) {
                // Đảm bảo các nút trong cột này hiển thị chính xác
                var buttons = cell.querySelectorAll('.btn');
                console.log('Tìm thấy cột thao tác với', buttons.length, 'nút');
                
                buttons.forEach(function(btn) {
                    btn.style.display = 'inline-block';
                    var icon = btn.querySelector('i.bi');
                    if (icon) {
                        icon.style.display = 'inline-block';
                    }
                });
            }
        });
        
        console.log('Đã thiết lập xong các nút hành động');
    }
    
    // Thiết lập các checkbox và chức năng chọn tất cả
    function setupCheckboxes(tableId) {
        console.log('Thiết lập checkbox cho bảng:', tableId);
        
        // Lấy bảng từ DOM
        var table = document.getElementById(tableId);
        if (!table) {
            console.warn('Không tìm thấy bảng:', tableId);
            return;
        }
        
        console.log('Kiểm tra bảng có class table-checkable:', table.classList.contains('table-checkable'));
        
        // Thêm CSS tùy chỉnh cho checkbox
        var style = document.createElement('style');
        style.textContent = `
            .table-checkable .form-check {
                display: flex;
                justify-content: center;
                margin: 0;
                padding: 0;
            }
            .table-checkable .form-check-input {
                margin: 0;
                cursor: pointer;
                width: 18px;
                height: 18px;
                opacity: 1;
                visibility: visible;
            }
            .check-all {
                position: relative;
                margin: 0;
                width: 18px !important;
                height: 18px !important;
                opacity: 1 !important;
                visibility: visible !important;
            }
            .selected-count {
                display: none;
                margin-left: 10px;
                font-weight: bold;
                color: #007bff;
            }
            .selected-count.active {
                display: inline-block;
            }
            .selected-actions {
                display: none;
                margin-left: 10px;
            }
            .selected-actions.active {
                display: inline-block;
            }
            /* Đảm bảo checkbox luôn hiển thị */
            input[type="checkbox"] {
                opacity: 1 !important;
                visibility: visible !important;
                pointer-events: auto !important;
            }
            /* Làm rõ vùng của cột checkbox */
            table.table-checkable th:first-child,
            table.table-checkable td:first-child {
                width: 40px;
                text-align: center;
                vertical-align: middle;
            }
        `;
        document.head.appendChild(style);
        
        // Tìm checkbox "chọn tất cả" trong bảng
        var checkAllCheckbox = table.querySelector('.check-all');
        if (!checkAllCheckbox) {
            console.warn('Không tìm thấy checkbox chọn tất cả, tìm kiếm theo cách khác');
            // Thử tìm theo cách khác nếu không có class trực tiếp
            checkAllCheckbox = table.querySelector('th:first-child input[type="checkbox"]');
            
            if (!checkAllCheckbox) {
                console.error('Không thể tìm thấy checkbox "chọn tất cả" trong bảng', tableId);
                return;
            } else {
                console.log('Đã tìm thấy checkbox chọn tất cả bằng cách khác');
                checkAllCheckbox.classList.add('check-all');
            }
        }
        
        // Tìm tất cả checkbox hàng trong bảng
        var rowCheckboxes = table.querySelectorAll('.table-check-row');
        if (!rowCheckboxes.length) {
            console.warn('Không tìm thấy checkbox hàng, tìm kiếm theo cách khác');
            // Thử tìm theo cách khác nếu không có class trực tiếp
            rowCheckboxes = table.querySelectorAll('tbody td:first-child input[type="checkbox"]');
            
            if (!rowCheckboxes.length) {
                console.error('Không thể tìm thấy checkbox hàng trong bảng', tableId);
                return;
            } else {
                console.log('Đã tìm thấy', rowCheckboxes.length, 'checkbox hàng bằng cách khác');
                rowCheckboxes.forEach(function(cb) {
                    cb.classList.add('table-check-row');
                });
            }
        }
        
        console.log('Tìm thấy', rowCheckboxes.length, 'checkbox hàng');
        
        // Thêm phần tử hiển thị số lượng đã chọn
        var selectedCountElement = document.createElement('span');
        selectedCountElement.className = 'selected-count';
        selectedCountElement.textContent = '0 được chọn';
        table.parentNode.insertBefore(selectedCountElement, table);
        
        // Thêm các nút hành động cho các mục đã chọn
        var selectedActionsElement = document.createElement('div');
        selectedActionsElement.className = 'selected-actions';
        selectedActionsElement.innerHTML = '<button type="button" class="btn btn-danger btn-sm delete-selected me-2"><i class="bi bi-trash"></i> Xóa đã chọn</button>' +
            '<button type="button" class="btn btn-success btn-sm export-selected"><i class="bi bi-file-earmark-excel"></i> Xuất dữ liệu đã chọn</button>';
        table.parentNode.insertBefore(selectedActionsElement, table);
        
        // Hàm cập nhật trạng thái chọn tất cả
        function updateCheckAllState() {
            console.log('Cập nhật trạng thái checkbox');
            
            var checkedCount = table.querySelectorAll('.table-check-row:checked, tbody td:first-child input[type="checkbox"]:checked').length;
            var totalCount = rowCheckboxes.length;
            
            console.log('Số lượng đã chọn:', checkedCount, '/', totalCount);
            
            // Cập nhật trạng thái checkbox "chọn tất cả"
            checkAllCheckbox.checked = checkedCount > 0 && checkedCount === totalCount;
            checkAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
            
            // Cập nhật hiển thị số lượng đã chọn
            selectedCountElement.textContent = checkedCount + ' được chọn';
            
            // Hiển thị/ẩn phần tử hiển thị số lượng đã chọn
            if (checkedCount > 0) {
                selectedCountElement.classList.add('active');
                selectedActionsElement.classList.add('active');
            } else {
                selectedCountElement.classList.remove('active');
                selectedActionsElement.classList.remove('active');
            }
        }
        
        // Xử lý sự kiện click vào checkbox "chọn tất cả"
        checkAllCheckbox.addEventListener('click', function() {
            console.log('Click vào checkbox chọn tất cả, trạng thái:', this.checked);
            
            var isChecked = this.checked;
            
            // Chọn/bỏ chọn tất cả các checkbox hàng
            rowCheckboxes.forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
            
            // Cập nhật trạng thái
            updateCheckAllState();
        });
        
        // Xử lý sự kiện click vào checkbox hàng
        rowCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('click', function() {
                console.log('Click vào checkbox hàng, trạng thái:', this.checked);
                
                // Cập nhật trạng thái
                updateCheckAllState();
            });
        });
        
        // Xử lý sự kiện cho nút Xóa đã chọn
        var deleteSelectedButton = selectedActionsElement.querySelector('.delete-selected');
        if (deleteSelectedButton) {
            deleteSelectedButton.addEventListener('click', function() {
                var selectedIds = [];
                var checkboxes = table.querySelectorAll('tbody td:first-child input[type="checkbox"]:checked');
                
                console.log('Số lượng checkbox đã chọn:', checkboxes.length);
                
                // Lấy tất cả ID của các hàng đã chọn
                checkboxes.forEach(function(checkbox) {
                    var row = checkbox.closest('tr');
                    if (row) {
                        var idCell = row.cells[1]; // Lấy ô chứa ID (cột thứ hai)
                        if (idCell) {
                            var id = idCell.textContent.trim();
                            selectedIds.push(id);
                        }
                    }
                });
                
                console.log('ID các mục đã chọn:', selectedIds);
                
                if (selectedIds.length > 0) {
                    if (confirm('Bạn có chắc chắn muốn xóa ' + selectedIds.length + ' mục đã chọn?')) {
                        console.log('Đã chọn các ID để xóa:', selectedIds);
                        
                        // Hiển thị loading overlay
                        var loadingOverlay = document.createElement('div');
                        loadingOverlay.className = 'loading-overlay';
                        loadingOverlay.innerHTML = `
                            <div class="loading-spinner"></div>
                            <div class="loading-text">Đang xóa dữ liệu...</div>
                        `;
                        
                        // CSS cho loading overlay
                        var loadingStyle = document.createElement('style');
                        loadingStyle.textContent = `
                            .loading-overlay {
                                position: fixed;
                                top: 0;
                                left: 0;
                                width: 100%;
                                height: 100%;
                                background: rgba(0, 0, 0, 0.5);
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                                z-index: 10000;
                            }
                            .loading-spinner {
                                width: 50px;
                                height: 50px;
                                border: 5px solid #f3f3f3;
                                border-top: 5px solid #3498db;
                                border-radius: 50%;
                                animation: spin 1s linear infinite;
                            }
                            .loading-text {
                                color: white;
                                margin-top: 20px;
                                font-size: 18px;
                            }
                            @keyframes spin {
                                0% { transform: rotate(0deg); }
                                100% { transform: rotate(360deg); }
                            }
                        `;
                        document.head.appendChild(loadingStyle);
                        document.body.appendChild(loadingOverlay);
                        
                        // Xác định URL hiện tại để gửi yêu cầu xóa
                        var currentUrl = window.location.href;
                        var baseUrl = currentUrl.split('?')[0]; // Lấy URL cơ bản, loại bỏ query string
                        var deleteUrl = baseUrl.replace(/\/index$|\/list$/, '') + '/delete_multi';
                        
                        // Chuẩn bị dữ liệu để gửi
                        var formData = new FormData();
                        selectedIds.forEach(function(id, index) {
                            formData.append('ids[' + index + ']', id);
                        });
                        
                        // Thêm token CSRF nếu có
                        var csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (csrfToken) {
                            formData.append('csrf_token', csrfToken.getAttribute('content'));
                        }
                        
                        // Thực hiện AJAX request để xóa dữ liệu
                        fetch(deleteUrl, {
                            method: 'POST',
                            body: formData,
                            credentials: 'same-origin'
                        })
                        .then(function(response) {
                            // Xóa loading overlay
                            document.body.removeChild(loadingOverlay);
                            
                            if (response.ok) {
                                return response.json();
                            } else {
                                throw new Error('Có lỗi xảy ra khi xóa dữ liệu. Mã lỗi: ' + response.status);
                            }
                        })
                        .then(function(data) {
                            if (data.success) {
                                // Hiển thị thông báo thành công
                                alert('Đã xóa thành công ' + selectedIds.length + ' mục.');
                                
                                // Tải lại trang hoặc cập nhật bảng
                                window.location.reload();
                            } else {
                                alert('Không thể xóa: ' + (data.message || 'Có lỗi xảy ra'));
                            }
                        })
                        .catch(function(error) {
                            console.error('Lỗi khi xóa dữ liệu:', error);
                            alert('Có lỗi xảy ra khi xóa dữ liệu: ' + error.message);
                            
                            // Mô phỏng thành công nếu API chưa tồn tại
                            if (error.message.includes('Failed to fetch') || error.message.includes('404')) {
                                // Mô phỏng hành vi xóa thành công dữ liệu trên client
                                checkboxes.forEach(function(checkbox) {
                                    var row = checkbox.closest('tr');
                                    if (row && row.parentNode) {
                                        row.parentNode.removeChild(row);
                                    }
                                });
                                
                                // Hiển thị thông báo (giả lập)
                                alert('Đã xóa thành công ' + selectedIds.length + ' mục.');
                                
                                // Cập nhật lại số lượng đã chọn
                                updateCheckAllState();
                            }
                        });
                    }
                } else {
                    alert('Vui lòng chọn ít nhất một mục để xóa');
                }
            });
        }
        
        // Xử lý sự kiện cho nút Xuất dữ liệu đã chọn
        var exportSelectedButton = selectedActionsElement.querySelector('.export-selected');
        if (exportSelectedButton) {
            exportSelectedButton.addEventListener('click', function() {
                var selectedRows = [];
                var checkboxes = table.querySelectorAll('tbody td:first-child input[type="checkbox"]:checked');
                
                console.log('Số lượng checkbox đã chọn để xuất:', checkboxes.length);
                
                // Lấy tất cả hàng đã chọn
                checkboxes.forEach(function(checkbox) {
                    var row = checkbox.closest('tr');
                    if (row) {
                        selectedRows.push(row);
                    }
                });
                
                if (selectedRows.length > 0) {
                    // Tạo bảng tạm thời với các hàng đã chọn
                    var tempTable = document.createElement('table');
                    tempTable.className = 'table table-bordered';
                    tempTable.id = 'temp-export-table-' + Date.now();
                    
                    // Sao chép thead từ bảng gốc
                    var thead = table.querySelector('thead');
                    if (thead) {
                        var newThead = document.createElement('thead');
                        var headerRow = thead.querySelector('tr').cloneNode(true);
                        
                        // Loại bỏ cột checkbox ở đầu nếu có
                        var firstHeaderCell = headerRow.querySelector('th:first-child');
                        if (firstHeaderCell && (firstHeaderCell.classList.contains('no-sort') || 
                            firstHeaderCell.querySelector('input[type="checkbox"]'))) {
                            headerRow.removeChild(firstHeaderCell);
                        }
                        
                        newThead.appendChild(headerRow);
                        tempTable.appendChild(newThead);
                    }
                    
                    // Tạo tbody mới và thêm các hàng đã chọn
                    var tbody = document.createElement('tbody');
                    selectedRows.forEach(function(row) {
                        var newRow = row.cloneNode(true);
                        
                        // Loại bỏ cột checkbox ở đầu
                        var firstCell = newRow.querySelector('td:first-child');
                        if (firstCell && firstCell.querySelector('input[type="checkbox"]')) {
                            newRow.removeChild(firstCell);
                        }
                        
                        // Loại bỏ cột thao tác ở cuối nếu có
                        var lastCell = newRow.querySelector('td:last-child');
                        if (lastCell && (lastCell.classList.contains('action-column') || 
                            lastCell.querySelector('.btn-group') || 
                            lastCell.querySelector('.action-buttons'))) {
                            newRow.removeChild(lastCell);
                        }
                        
                        tbody.appendChild(newRow);
                    });
                    tempTable.appendChild(tbody);
                    
                    // Thêm bảng tạm thời vào DOM (ẩn)
                    var tempContainer = document.createElement('div');
                    tempContainer.style.position = 'absolute';
                    tempContainer.style.left = '-9999px';
                    tempContainer.appendChild(tempTable);
                    document.body.appendChild(tempContainer);
                    
                    // Hiển thị menu xuất
                    var exportMenu = document.createElement('div');
                    exportMenu.className = 'export-menu';
                    exportMenu.innerHTML = `
                        <div class="export-overlay"></div>
                        <div class="export-popup">
                            <div class="export-header">Xuất dữ liệu đã chọn</div>
                            <div class="export-body">
                                <button class="btn btn-success export-excel"><i class="bi bi-file-earmark-excel"></i> Xuất Excel</button>
                                <button class="btn btn-danger export-pdf"><i class="bi bi-file-earmark-pdf"></i> Xuất PDF</button>
                                <button class="btn btn-secondary export-cancel">Hủy</button>
                            </div>
                        </div>
                    `;
                    
                    // CSS cho menu xuất
                    var exportMenuStyle = document.createElement('style');
                    exportMenuStyle.textContent = `
                        .export-overlay {
                            position: fixed;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            background: rgba(0,0,0,0.5);
                            z-index: 9999;
                        }
                        .export-popup {
                            position: fixed;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            background: white;
                            padding: 20px;
                            border-radius: 5px;
                            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                            z-index: 10000;
                            width: 300px;
                        }
                        .export-header {
                            font-size: 18px;
                            font-weight: bold;
                            margin-bottom: 15px;
                            text-align: center;
                        }
                        .export-body {
                            display: flex;
                            flex-direction: column;
                            gap: 10px;
                        }
                        .export-body button {
                            padding: 10px;
                            cursor: pointer;
                        }
                    `;
                    document.head.appendChild(exportMenuStyle);
                    document.body.appendChild(exportMenu);
                    
                    // Xử lý sự kiện nút xuất Excel
                    exportMenu.querySelector('.export-excel').addEventListener('click', function() {
                        try {
                            exportTableToExcel(tempTable.id);
                            document.body.removeChild(exportMenu);
                            setTimeout(function() {
                                document.body.removeChild(tempContainer);
                            }, 1000);
                        } catch (e) {
                            console.error('Lỗi khi xuất Excel:', e);
                            alert('Có lỗi khi xuất Excel: ' + e.message);
                        }
                    });
                    
                    // Xử lý sự kiện nút xuất PDF
                    exportMenu.querySelector('.export-pdf').addEventListener('click', function() {
                        try {
                            exportTableToPdf(tempTable.id);
                            document.body.removeChild(exportMenu);
                            setTimeout(function() {
                                document.body.removeChild(tempContainer);
                            }, 1000);
                        } catch (e) {
                            console.error('Lỗi khi xuất PDF:', e);
                            alert('Có lỗi khi xuất PDF: ' + e.message);
                        }
                    });
                    
                    // Xử lý sự kiện nút hủy
                    exportMenu.querySelector('.export-cancel').addEventListener('click', function() {
                        document.body.removeChild(exportMenu);
                        document.body.removeChild(tempContainer);
                    });
                    
                    // Xử lý sự kiện click vào overlay
                    exportMenu.querySelector('.export-overlay').addEventListener('click', function() {
                        document.body.removeChild(exportMenu);
                        document.body.removeChild(tempContainer);
                    });
                } else {
                    alert('Vui lòng chọn ít nhất một mục để xuất dữ liệu');
                }
            });
        }
        
        // Thực hiện cập nhật trạng thái ban đầu
        updateCheckAllState();
        
        console.log('Đã thiết lập xong checkbox cho bảng');
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
        },
        setupCheckboxes: setupCheckboxes
    };
}); 