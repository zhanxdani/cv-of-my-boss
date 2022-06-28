function send(event, php){
    console.log("Отправка запроса");
    event.preventDefault ? event.preventDefault() : event.returnValue = false;
    var req = new XMLHttpRequest();
    req.open('POST', php, true);
    req.onload = function() {
    if (req.status >= 200 && req.status < 400) {
    json = JSON.parse(this.response);
        if (json.result == "success") {
            form.reset()
            document.querySelector('.succes').classList.add('succesfade');
            document.querySelector('.succestxt').classList.add('succestxtfade');
            setTimeout(function(){
            location.href = 'index.html';
            }, 3500);
        } else {
            alert("Ошибка. Сообщение не отправлено");
        }
    // Если не удалось связаться с php файлом
    } else {alert("Ошибка сервера. Номер: "+req.status);}}; 

// Если не удалось отправить запрос. Стоит блок на хостинге
req.onerror = function() {alert("Ошибка отправки запроса");};
req.send(new FormData(event.target));
}


    