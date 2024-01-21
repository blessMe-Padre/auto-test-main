const selectElementMarka = document.getElementById('markaAuc');
const selectElementModel = document.getElementById('modelAuc');
const selectElementKuzov = document.getElementById('kuzovAuction');

selectElementMarka.addEventListener('change', async function (event) {
    const selectedValue = selectElementMarka.value;
    const url = '/model.php';
    const data = { marka: selectedValue };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            createModelSelectOptions(data);
            // console.log(data); // Содержимое ответа от сервера
        })
        .catch(function (error) {
            // Обработка ошибок, если необходимо
        });

});

const createModelSelectOptions = (data) => {
    let dataFotmat = data.data;
    selectElementModel.innerHTML = '';

    if (data) {
        let firstOption = document.createElement('option');
        firstOption.value = "any";
        firstOption.text = "Любая";
        selectElementModel.prepend(firstOption);

        dataFotmat.forEach(model => {
            let option = document.createElement('option');
            option.value = model.MODEL_NAME;
            option.text = model.MODEL_NAME;

            selectElementModel.appendChild(option);
        });
    } else {
        let option = document.createElement('option');

        option.value = '';
        option.text = 'No models available';
        selectElementModel.appendChild(option);
    }
}

selectElementModel.addEventListener('change', () => {
    const selectedModel = selectElementModel.value;
    const url = '/kuzov.php';
    const data = { model: selectedModel };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            createKuzovSelectOptions(data);
        })
        .catch(function (error) {
            // Обработка ошибок, если необходимо
        });
});

// Функция создания option у селекта выбора кузова
const createKuzovSelectOptions = (kuzov) => {
    let dataFotmat = kuzov.kuzov;
    selectElementKuzov.innerHTML = '';

    if (kuzov) {
        let firstOption = document.createElement('option');
        firstOption.value = "any";
        firstOption.text = "Любой";
        selectElementKuzov.prepend(firstOption);

        dataFotmat.forEach(kuzov => {
            let option = document.createElement('option');
            option.value = kuzov.KUZOV;
            option.text = kuzov.KUZOV;


            selectElementKuzov.appendChild(option);
        });
    } else {
        let option = document.createElement('option');
        option.value = '';
        option.text = 'No models available';
        selectElementModel.appendChild(option);
    }
}




