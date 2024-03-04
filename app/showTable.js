function loadCSV(filename) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var csvData = xhr.responseText;
            displayTable(csvData);
        }
    };
    xhr.open('GET', filename, true);
    xhr.send();
}

function displayTable(csvData) {
    var rows = csvData.split('\n');
    var tableHtml = '<table id="data-table">';

    rows.forEach(function(row, rowIndex) {
        var columns = row.split(',');
        tableHtml += '<tr>';

        columns.forEach(function(column, colIndex) {
            // セルの値が「公休」の場合、背景を黄色に
            var cellColor = column.trim() === '公休' ? 'background-color: yellow;' : '';
            
            // セルの値が「特別休」の場合、文字色を水色に
            var cellColor2 = column.trim() === '特殊休' ? 'background-color: aqua;' : '';

            // 一番左の列と一番上の行にクラスを追加
            var cellClass = '';
            
            if (rowIndex === 0 && colIndex === 0) {
                column = '日付';
                cellClass += ' crossed';
                
            }
            else if (colIndex === 0) {
                    
                cellClass += ' col-header';
            }
            else if (rowIndex === 0) {
                cellClass += ' row-header';
            }
            

            tableHtml += '<td class="' + cellClass + '" style="' + cellColor + cellColor2 + '">' + column + '</td>';
        });

        tableHtml += '</tr>';
    });

    tableHtml += '</table>';
    document.getElementById('table-container').innerHTML = tableHtml;
   
}



function filterFiles() {
    var searchInput = document.getElementById('search').value.toLowerCase();
    var labels = document.querySelectorAll('form label');

    labels.forEach(function(label) {
        var filename = label.textContent.trim().toLowerCase();
        var isVisible = filename.includes(searchInput);
        label.style.display = isVisible ? 'block' : 'none';
    });

    // ファイルが一つも表示されていない場合、全てのラベルを表示する
    var visibleLabels = document.querySelectorAll('form label[style="display: block;"]');
    if (visibleLabels.length === 0) {
        labels.forEach(function(label) {
            label.style.display = 'block';
        });
    }
}

function exportToCSV() {
    var table = document.getElementById('data-table');
    if (table) {
        var csvContent = "\uFEFF"; // UTF-8 BOMを追加
        var rows = table.querySelectorAll('tr');

        rows.forEach(function(row) {
            var rowData = [];
            var cells = row.querySelectorAll('td');

            cells.forEach(function(cell) {
                rowData.push('"' + cell.innerText + '"');
            });

            csvContent += rowData.join(',') + '\n';
        });

        var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8' });
        var a = document.createElement('a');
        a.href = window.URL.createObjectURL(blob);
        a.download = 'table_data.csv';
        a.click();
    }
}

function exportToExcel() {
    var table = document.getElementById('data-table');
    if (table) {
        // ここでテーブルのデータをExcel形式に変換し、ダウンロードする処理を行います
        // 以下はサンプルのコードで、実際のデータ変換とダウンロード処理はライブラリや手法によって異なります
        var data = '<table>' + table.innerHTML + '</table>';
        var blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8' });
        var a = document.createElement('a');
        a.href = window.URL.createObjectURL(blob);
        a.download = 'table_data.xlsx';
        a.click();
    }
}
