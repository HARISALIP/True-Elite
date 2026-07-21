// assets/js/list.js

function filterList() {
    const input = document.getElementById("list-search");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("list-body");
    const tr = table.getElementsByTagName("tr");

    for (let i = 0; i < tr.length; i++) {
        const row = tr[i];
        const tds = row.getElementsByTagName("td");
        let rowContainsFilter = false;
        
        // Skip the checkbox column (index 0)
        for (let j = 1; j < tds.length; j++) {
            if (tds[j]) {
                const cellText = tds[j].textContent || tds[j].innerText;
                if (cellText.toLowerCase().indexOf(filter) > -1) {
                    rowContainsFilter = true;
                    break;
                }
            }
        }
        
        if (rowContainsFilter) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    }
}

let sortDirection = false;

function sortTable(columnIndex) {
    const table = document.getElementById("list-table");
    const tbody = document.getElementById("list-body");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    
    sortDirection = !sortDirection;

    const sortedRows = rows.sort((a, b) => {
        const aColText = a.querySelector(`td:nth-child(${columnIndex + 1})`).textContent.trim();
        const bColText = b.querySelector(`td:nth-child(${columnIndex + 1})`).textContent.trim();

        // Attempt numeric sort if applicable
        const aNum = parseFloat(aColText.replace(/,/g, ''));
        const bNum = parseFloat(bColText.replace(/,/g, ''));

        if (!isNaN(aNum) && !isNaN(bNum)) {
            return sortDirection ? aNum - bNum : bNum - aNum;
        }
        
        // String sort
        return sortDirection 
            ? aColText.localeCompare(bColText) 
            : bColText.localeCompare(aColText);
    });
    
    // Remove all existing rows
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    
    // Append sorted rows
    tbody.append(...sortedRows);
}
