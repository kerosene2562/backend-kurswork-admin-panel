document.getElementById('search').oninput = function () {
    getModelTable(currentTable);
}

let currentDirection = 'asc';
let currentColumn = null;

async function getModelTable(tableName, order_by = null) {
    if (order_by && order_by === currentColumn) {
        currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
    }
    else {
        currentDirection = 'asc';
    }
    currentColumn = order_by;

    currentTable = tableName;
    let search = document.getElementById('search').value;
    fetch(`/lost_admin/admins/getTable?tableName=${tableName}&order_by=${order_by}&direction=${currentDirection}`)
        .then(response => response.json())
        .then(data => {
            if (tableName == 'categories') {
                document.getElementById("create_button").style.display = 'block';
            }
            else {
                document.getElementById("create_button").style.display = 'none';
            }
            workplace = document.getElementById('workplace');
            workplace.innerHTML = "";
            let table = document.createElement('table');
            table.id = "data_table"
            let keys = Object.keys(data[0]);
            keys.forEach(key => {
                let th = document.createElement('th');
                th.innerHTML = key;
                th.classList.add('cell');
                th.onclick = function () { getModelTable(tableName, key) };
                table.appendChild(th);
            })

            let searchedData = data.filter((element) => {
                return Object.values(element).some(value => {
                    if (value != null)
                        return value.toString().toLowerCase().includes(search.toLowerCase());
                    return false;
                })
            })

            searchedData.forEach(element => {
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
                saveActionButton.onclick = function () { saveData(tableName, element['id']) };
                saveActionButton.classList.add('actionButtonSave');
                saveActionButton.innerHTML = "зберегти";
                let tdSave = document.createElement('td');
                tdSave.appendChild(saveActionButton);
                tr.appendChild(tdSave);

                let deleteActionButton = document.createElement("button");
                deleteActionButton.onclick = function () { deleteData(tableName, element['id']) };
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

async function deleteData(table, id) {
    result = confirm("Ви певні, що хочете видалити поле " + id + " в таблиці " + table + "?");
    if (result) {
        try {
            await fetch(`/lost_admin/admins/deleteData?table=${table}&id=${id}`);
            await getModelTable(table);
        }
        catch (error) {
            console.error(error);
        }
    }
}

async function saveData(table, id) {
    try {
        jsonData = [];
        jsonData.push(table);
        data = document.getElementsByClassName(`${table}_${id}`);
        Object.values(data).forEach(element => {
            jsonData.push(element.value);
        })
        await fetch(`/lost_admin/admins/saveData`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(jsonData)
        });
        await getModelTable(table);
    }
    catch (error) {
        console.error(error);
    }
}

async function saveAllData() {
    saveButtons = document.getElementsByClassName('actionButtonSave');
    Object.values(saveButtons).forEach(button => {
        button.click();
    })
}

function getReportWork() {
    fetch(`/lost_admin/admins/getReportsWork`)
        .then(response => response.json())
        .then(data => {
            let reportComment = 0;
            let comment = data.at(-1);
            let reports = data.slice(0, -1);

            workplace = document.getElementById('workplace');
            workplace.innerHTML = "";

            let workflow = document.createElement('div');
            workflow.classList.add("workflow");

            if ("title" in comment) {
                commentBlock = createThreadReportWindow(comment);
            }
            else {
                commentBlock = createCommentReportWindow(comment);
            }

            workflow.appendChild(commentBlock);

            let mainReportBlock = document.createElement('div');
            mainReportBlock.id = "main_report_block";

            let reportBlock = document.createElement('div');
            reportBlock.id = 'report_block';

            let buttonNext = document.createElement('button');
            buttonNext.classList.add('changeButton');
            buttonNext.onclick = function () {
                reportComment++;
                if (reportComment >= reports.length)
                    reportComment = 0;
                showReportMessage(reports[reportComment]["reason"], reports[reportComment]["reported_at"])
            };
            buttonNext.innerHTML = ">";

            let buttonPrev = document.createElement('button');
            buttonPrev.classList.add('changeButton');
            buttonPrev.onclick = function () {
                reportComment--;
                if (reportComment < 0)
                    reportComment = reports.length - 1;
                showReportMessage(reports[reportComment]["reason"], reports[reportComment]["reported_at"])
            };
            buttonPrev.innerHTML = "<";

            let buttonDelete = document.createElement('button');
            buttonDelete.classList.add('delete_button');
            if ("title" in comment) {
                buttonDelete.onclick = function () { deleteReportedMessage(comment["id"], false) }
            }
            else {
                buttonDelete.onclick = function () { deleteReportedMessage(comment["id"], true) }
            }
            buttonDelete.innerHTML = "Видалити";

            let buttonIgnore = document.createElement('button');
            buttonIgnore.classList.add('ignore_button');
            buttonIgnore.onclick = function () { ignoreReportedMessage(comment["id"]) }
            buttonIgnore.innerHTML = "Ігнорувати";

            let buttonsBlock = document.createElement('div');
            buttonsBlock.classList.add("buttons_block");

            mainReportBlock.appendChild(reportBlock);

            buttonsBlock.appendChild(buttonPrev);
            buttonsBlock.appendChild(buttonNext);
            buttonsBlock.appendChild(buttonDelete);
            buttonsBlock.appendChild(buttonIgnore);
            mainReportBlock.appendChild(buttonsBlock);
            workflow.appendChild(mainReportBlock);

            workplace.appendChild(workflow);

            showReportMessage(reports[reportComment]["reason"], reports[reportComment]["reported_at"]);
        });
}

function createThreadReportWindow(thread) {
    let commentBlock = document.createElement('div');
    commentBlock.classList.add('comment_block');
    commentBlock.id = "comment" + thread['id'];

    let commentInfoBlock = document.createElement('div');
    commentInfoBlock.classList.add('comment_info_block');

    let commentTextInfoBlock = document.createElement('div');
    commentTextInfoBlock.classList.add('comment_info_text_block');

    let commentInfoText = document.createElement('p');
    commentInfoText.classList.add('comment_info_text');

    let commentRefToIland = document.createElement('a');
    commentRefToIland.href = "/lost_island/discussion/index?thread_id=" + thread["id"];
    commentRefToIland.innerHTML = thread['id'];
    commentRefToIland.target = "_blank";

    commentInfoText.innerHTML = "Тред №";
    commentInfoText.appendChild(commentRefToIland);
    commentTextInfoBlock.appendChild(commentInfoText);

    let commentInfoDate = document.createElement('p');
    commentInfoDate.classList.add('comment_info_text');
    commentInfoDate.innerHTML = " | " + thread['created_at'];
    commentTextInfoBlock.appendChild(commentInfoDate);

    commentInfoBlock.appendChild(commentTextInfoBlock);
    commentBlock.appendChild(commentInfoBlock);

    let titleBlock = document.createElement('div');
    titleBlock.classList.add('title_block');
    titleBlock.innerHTML = thread["title"];
    commentBlock.appendChild(titleBlock);

    let imgsBlock = document.createElement('div');
    imgsBlock.classList.add('imgs_block');

    if (thread['imgs_refs'] != null) {
        let imgsArr = JSON.parse(thread["imgs_refs"]);
        imgsArr.forEach((imgRef) => {
            let div = document.createElement('div');

            let imgContainer = document.createElement('div');
            imgContainer.classList.add('img_container');

            if (imgRef.split(".")[1] == "mp4") {
                let video = document.createElement('video');
                video.src = "/lost_island/pics/" + imgRef;
                video.alt = imgRef;
                imgContainer.appendChild(video);
                div.appendChild(imgContainer);
            }
            else {
                let img = document.createElement('img');
                img.src = "/lost_island/pics/" + imgRef;
                img.alt = imgRef;
                imgContainer.appendChild(img);
            }

            let video = document.createElement('video');
            video.src = "/lost_island/pics/" + imgRef;
            video.alt = imgRef;
            div.appendChild(imgContainer);

            let aImgRef = document.createElement('a');
            aImgRef.classList.add('img_name_text');
            let splitedRef = imgRef.split("/");
            aImgRef.innerHTML = splitedRef[1];
            aImgRef.href = "#";
            div.appendChild(aImgRef);

            imgsBlock.appendChild(div);
        });
        commentBlock.appendChild(imgsBlock);
    }
    let commentTextBlock = document.createElement('div');
    commentTextBlock.classList.add('comment_text_block');

    let commentText = document.createElement('p');
    commentText.classList.add('comment_text');
    commentText.innerHTML = thread['description'].replace(/\r?\n/g, "<br>");
    commentTextBlock.appendChild(commentText);

    commentBlock.appendChild(commentTextBlock);
    return commentBlock;

}

function createCommentReportWindow(comment) {
    let commentBlock = document.createElement('div');
    commentBlock.classList.add('comment_block');
    commentBlock.id = "comment" + comment['id'];

    let commentInfoBlock = document.createElement('div');
    commentInfoBlock.classList.add('comment_info_block');

    let commentTextInfoBlock = document.createElement('div');
    commentTextInfoBlock.classList.add('comment_info_text_block');

    let commentInfoText = document.createElement('p');
    commentInfoText.classList.add('comment_info_text');

    let commentRefToIland = document.createElement('a');
    commentRefToIland.href = "/lost_island/discussion/index?thread_id=" + comment["thread_id"] + "#comment" + comment['id'];
    commentRefToIland.innerHTML = comment['id'];
    commentRefToIland.target = "_blank";

    commentInfoText.innerHTML = "Анонімний коментар №";
    commentInfoText.appendChild(commentRefToIland);
    commentTextInfoBlock.appendChild(commentInfoText);

    if (comment['parent_comment_id'] != null) {
        let commentReplyTo = document.createElement('p');
        commentReplyTo.classList.add('comment_info_text');
        let refReplyedTo = document.createElement('a');
        refReplyedTo.innerHTML = comment['parent_comment_id'];
        commentReplyTo.innerHTML = " | відповідь на <";
        commentReplyTo.appendChild(refReplyedTo);
        commentReplyTo.innerHTML += ">";
        commentTextInfoBlock.appendChild(commentReplyTo);
    }

    let commentInfoDate = document.createElement('p');
    commentInfoDate.classList.add('comment_info_text');
    commentInfoDate.innerHTML = " | " + comment['post_datetime'];
    commentTextInfoBlock.appendChild(commentInfoDate);

    commentInfoBlock.appendChild(commentTextInfoBlock);
    commentBlock.appendChild(commentInfoBlock);

    let imgsBlock = document.createElement('div');
    imgsBlock.classList.add('imgs_block');

    if (comment['imgs_refs'] != null) {
        let imgsArr = JSON.parse(comment["imgs_refs"]);
        imgsArr.forEach((imgRef) => {
            let div = document.createElement('div');

            let imgContainer = document.createElement('div');
            imgContainer.classList.add('img_container');

            if (imgRef.split(".")[1] == "mp4") {
                let video = document.createElement('video');
                video.src = "/lost_island/pics/" + imgRef;
                video.alt = imgRef;
                imgContainer.appendChild(video);
                div.appendChild(imgContainer);
            }
            else {
                let img = document.createElement('img');
                img.src = "/lost_island/pics/" + imgRef;
                img.alt = imgRef;
                imgContainer.appendChild(img);
            }


            let video = document.createElement('video');
            video.src = "/lost_island/pics/" + imgRef;
            video.alt = imgRef;
            div.appendChild(imgContainer);

            let aImgRef = document.createElement('a');
            aImgRef.classList.add('img_name_text');
            let splitedRef = imgRef.split("/");
            aImgRef.innerHTML = splitedRef[1];
            aImgRef.href = "#";
            div.appendChild(aImgRef);

            imgsBlock.appendChild(div);
        });
        commentBlock.appendChild(imgsBlock);
    }
    let commentTextBlock = document.createElement('div');
    commentTextBlock.classList.add('comment_text_block');

    let commentText = document.createElement('p');
    commentText.classList.add('comment_text');
    commentText.innerHTML = comment['comment'].replace(/\r?\n/g, "<br>");
    commentTextBlock.appendChild(commentText);

    commentBlock.appendChild(commentTextBlock);
    return commentBlock;
}

function showReportMessage(reportMessage, reported_at) {
    let result = document.getElementById('report_block');
    result.innerHTML = "";

    let reportedAtBlock = document.createElement('div');
    reportedAtBlock.classList.add("reported_at_block");
    reportedAtBlock.innerHTML = reported_at;
    result.appendChild(reportedAtBlock);

    let messageBlock = document.createElement('div');
    messageBlock.classList.add('message_block');
    messageBlock.innerHTML = reportMessage;
    result.appendChild(messageBlock);
}

async function deleteReportedMessage(id, isComment) {
    try {
        await fetch(`/lost_admin/admins/clearComment?id=${id}&is_comment=${isComment}`);
        getReportWork();
    }
    catch (e) {
        console.error(e);
    }

}

async function ignoreReportedMessage(id) {
    try {
        await fetch(`/lost_admin/admins/ignoreReports?id=${id}`);
        getReportWork();
    }
    catch (e) {
        console.error(e);
    }
}

function showCreateModal() {
    document.getElementById("create_modal_window").style.display = 'block';
    document.getElementById("overlay").style.display = 'block';
}

function closeModal() {
    document.getElementById("create_modal_window").style.display = 'none';
    document.getElementById("overlay").style.display = 'none';
    document.getElementById("new_category_input").value = "";
    document.getElementById('modal_media').style.display = 'none';
    document.getElementById('media_video').style.display = 'none';
    document.getElementById('media_video').pause();
    document.getElementById('media_img').style.display = 'none';
}

async function createCategory() {
    categoryName = document.getElementById('new_category_input').value;

    try {
        await fetch(`/lost_admin/admins/createCategory?name=${categoryName}`);
        await getModelTable('categories');
    }
    catch (e) {
        console.error(e);
    }

    closeModal();
}

function showMedia(media) {
    document.getElementById("modal_media").style.display = 'block';
    document.getElementById('overlay').style.display = 'block';

    if (media.src.split(".")[1] == "mp4") {
        document.getElementById('media_video').style.display = 'block';
        document.getElementById('media_video').src = media.src;
        document.getElementById('bottom_info').innerHTML = "(" + media.videoWidth + "x" + media.videoHeight + ")";
    }
    else {
        document.getElementById('media_img').style.display = 'block';
        document.getElementById('media_img').src = media.src;
        document.getElementById('bottom_info').innerHTML = "(" + media.naturalWidth + "x" + media.naturalHeight + ")";
    }
    let mediaRef = media.src.split("/");
    document.getElementById('top_info').innerHTML = mediaRef[6];
}

document.querySelector('body').addEventListener('click', function (media) {
    if (media.target.tagName === "IMG" || media.target.tagName === "VIDEO") {
        showMedia(media.target);
    }
})

/////////////      move modal media window
let keyPressed = false;
let offsetX = 0;
let offsetY = 0;
let modalWindow = document.getElementById('modal_media');
modalWindow.addEventListener('mousedown', (event) => {
    let clientWindow = modalWindow.getBoundingClientRect();
    modalWindow.style.top = `${clientWindow.top}px`;
    modalWindow.style.left = `${clientWindow.left}px`;
    modalWindow.style.position = 'fixed';
    modalWindow.style.transform = 'none';
    keyPressed = true;
    offsetX = event.clientX - clientWindow.left;
    offsetY = event.clientY - clientWindow.top;
})

modalWindow.addEventListener('mouseup', (event) => {
    keyPressed = false;
})

document.body.addEventListener('mousemove', (event) => {
    if (keyPressed) {
        modalWindow.style.top = `${event.clientY - offsetY}px`;
        modalWindow.style.left = `${event.clientX - offsetX}px`;
    }
})
/////////////