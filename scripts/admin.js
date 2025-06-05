async function getModelTable(tableName)
{
    fetch(`/lost_admin/admins/getTable?tableName=${tableName}`)
    .then(response => response.json())
    .then(data => {
        workplace = document.getElementById('workplace');
        workplace.innerHTML = "";
        let table = document.createElement('table');
        table.id = "data_table"
        let keys = Object.keys(data[0]);
        keys.forEach(key => {
            let th = document.createElement('th');
            th.innerHTML = key;
            th.classList.add('cell');
            table.appendChild(th);
        })
        data.forEach(element => {
            let tr = document.createElement('tr');
            Object.values(element).forEach(value => {
                let td = document.createElement('td');
                let input = document.createElement('input');
                input.value = value;
                input.classList.add("inputData");
                input.classList.add(tableName + "_" + element["id"]);
                td.appendChild(input);
                td.classList.add('cell');
                tr.appendChild(td);
            })
            let saveActionButton = document.createElement("button");
            saveActionButton.onclick = function(){ saveData(tableName, element['id']) };
            saveActionButton.classList.add('actionButtonSave');
            saveActionButton.innerHTML = "зберегти";
            let tdSave = document.createElement('td');
            tdSave.appendChild(saveActionButton);
            tdSave.classList.add('cell');
            tr.appendChild(tdSave);

            let deleteActionButton = document.createElement("button");
            deleteActionButton.onclick = function(){ deleteData(tableName, element['id']) };
            deleteActionButton.classList.add('actionButtonDelete');
            deleteActionButton.innerHTML = "видалити";
            let tdDelete = document.createElement('td');
            tdDelete.appendChild(deleteActionButton);
            tdDelete.classList.add('cell');
            tr.appendChild(tdDelete);

            table.appendChild(tr);
        });
        workplace.appendChild(table);
    })
}

async function deleteData(table, id)
{
    result = confirm("Ви певні, що хочете видалити поле " + id + " в таблиці " + table + "?");
    if(result)
    {
        try{
            await fetch(`/lost_admin/admins/deleteData?table=${table}&id=${id}`);
            await getModelTable(table);
        }
        catch(error){
            console.error(error);
        }
    }    
}

async function saveData(table, id)
{
    try{
        jsonData = [];
        jsonData.push(table);
        data = document.getElementsByClassName(`${table}_${id}`)
        Object.values(data).forEach(element => {
            jsonData.push(element.value);
        })
        await fetch(`/lost_admin/admins/saveData`,{
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(jsonData)});
        await getModelTable(table);
    }
    catch(error){
        console.error(error);
    }  
}

async function saveAllData()
{
    saveButtons = document.getElementsByClassName('actionButtonSave');
    Object.values(saveButtons).forEach(button => {
        button.click();
    })
}