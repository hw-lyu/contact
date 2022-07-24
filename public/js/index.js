//인풋박스 네임 전송시 리다이렉트
let hiddenWriter = document.querySelector('input[name="writer"]'),
    inputId = document.querySelector(".label-box input[name='id']"),
    nameBox = document.querySelector('.name-box'),
    tableTbodyTr = document.querySelectorAll('.common-table tbody tr'),
    search = document.querySelector('.search-wrap');

// 첫화면 메인 스크립트 url : /contact
if (inputId) {
    inputId.closest('.label-box').querySelector('.btn').addEventListener('click', () => {
        let inputIdValue = inputId.value,
            inputIdMatch = inputIdValue.match(/[가-힣]/g);

        //공백처리
        inputId.value = inputId.value.replaceAll(' ', '');

        if (inputIdValue === '' || !inputIdMatch) {
            alert('이름을 입력해주세요');
            return false;
        }
        location.href = `/contact/${inputId.value.trim()}`;
    });
}

// 등록할 때 인풋 스크립트 url : /contact/{사용자이름}
if (nameBox) {
    nameBox.querySelector('.btn').addEventListener('click', () => {
        let name = nameBox.querySelector('.input-name'),
            tel = nameBox.querySelector('.input-tel'),
            nameMatch = name.value.match(/[가-힣]/g),
            telMatch = tel.value.match(/^(?:[0-9]{1,11})/g);

        //공백처리
        name.value = name.value.replaceAll(' ', '');

        console.log(nameMatch);

        if (!name.value || !nameMatch) {
            alert('한글 이름을 입력해주세요.');
            return false;
        } else if (!tel.value || tel.value !== (telMatch !== null ? telMatch.join('') : '')) {
            alert('연락처를 입력해주세요.\n최대 11글자 입니다.');
            return false;
        }

        if (name.value && tel.value) {
            const data = {"writer": hiddenWriter.value, "name": name.value, "phone_number": tel.value};

            fetch('http://127.0.0.1:8000/list', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data),
            })
                .then(data => data.json())
                .then((json) => {
                    if (!json || typeof json !== 'object') {
                        alert('잘못된 응답입니다.');
                        return;
                    }
                    if (typeof json.errors === 'object') {
                        let msg = json.message;
                        for (const field in json.errors) {
                            msg += "\n" + json.errors[field];
                        }
                        alert(msg);
                        return;
                    }

                    let tableTbody = document.querySelector('.common-table tbody'),
                        pagination = document.querySelector('.pagination-wrap');
                    tableTbody.innerHTML = ``;
                    json.contact.data.map((ele) => {
                        let trEle = document.createElement('tr');
                        trEle.dataset.idx = `${ele.idx}`;
                        trEle.innerHTML = `
                                <td class="name"><input type="text" class="input-name" readonly="" value="${ele.name}"></td>
                                <td class="tel"><input type="tel" class="input-tel" readonly="" value="${ele.phone_number}"></td>
                                <td class="date">
                                    <div class="date-at">${ele.created_at !== ele.updated_at ? ele.updated_at : ele.created_at}</div>
                                </td>
                                <td class="btn-group">
                                    <div class="btn-wrap">
                                        <button type="button" class="btn amend">수정</button>
                                        <button type="button" class="btn delete">삭제</button>
                                    </div>
                                </td>
                            `;
                        tableTbody.appendChild(trEle);
                    });
                    pagination.innerHTML = `${json.link}`;

                    location.href = `/contact/${hiddenWriter.value}`;
                })
                .catch((error) => {
                    console.error('실패:', error);
                });
        }
    })
}

//테이블 버튼 영역 스크립트 / 수정,삭제,저장,취소
if (tableTbodyTr) {
    document.addEventListener('click', (e) => {
        let eTarget = e.target,
            tr = eTarget.closest('tr');

        if (!tr) {
            return;
        }

        let name = tr.querySelector('.input-name'),
            tel = tr.querySelector('.input-tel');

        //수정
        if (eTarget.classList.contains('amend')) {
            name.readOnly = false;
            tel.readOnly = false;

            console.log('amend');
            tr.querySelector('.btn-wrap').innerHTML = `
                    <button type="button" class="btn save">저장</button>
                    <button type="button" class="btn cancel">취소</button>
                `;
        }
        //삭제
        if (eTarget.classList.contains('delete')) {
            let con = confirm('삭제하시겠습니까?');
            const data = {
                "idx": tr.dataset.idx,
                "writer": hiddenWriter.value,
                "phone_number": tel.value,
                "page": (new URLSearchParams(location.search)).get('page')
            };

            if (con) {
                fetch('http://127.0.0.1:8000/delete', {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data),
                })
                    .then(data => data.json())
                    .then((json) => {
                        if (!json || typeof json !== 'object') {
                            alert('잘못된 응답입니다.');
                            return;
                        }
                        if (typeof json.errors === 'object') {
                            let msg = json.message;
                            for (const field in json.errors) {
                                msg += "\n" + json.errors[field];
                            }
                            alert(msg);
                            return;
                        }

                        let tableTbody = document.querySelector('.common-table tbody'),
                            pagination = document.querySelector('.pagination-wrap');
                        tableTbody.innerHTML = ``;
                        json.contact.data.map((ele) => {
                            let trEle = document.createElement('tr');
                            trEle.dataset.idx = `${ele.idx}`;
                            trEle.innerHTML = `
                                <td class="name"><input type="text" class="input-name" readonly="" value="${ele.name}"></td>
                                <td class="tel"><input type="tel" class="input-tel" readonly="" value="${ele.phone_number}"></td>
                                <td class="date">
                                    <div class="date-at">${ele.created_at !== ele.updated_at ? ele.updated_at : ele.created_at}</div>
                                </td>
                                <td class="btn-group">
                                    <div class="btn-wrap">
                                        <button type="button" class="btn amend">수정</button>
                                        <button type="button" class="btn delete">삭제</button>
                                    </div>
                                </td>
                            `;
                            tableTbody.appendChild(trEle);
                        });
                        pagination.innerHTML = `${json.link}`;

                    })
                    .catch((error) => {
                        console.error('실패:', error);
                    });
            }
        }
        //저장
        if (eTarget.classList.contains('save')) {
            let nameMatch = name.value.match(/[가-힣]/g),
                telMatch = tel.value.match(/^(?:[0-9]{1,11})/g);

            //공백처리
            name.value = name.value.replaceAll(' ', '');

            if (!name.value || name.value !== (nameMatch !== null ? nameMatch.join('') : '')) {
                alert('한글 이름을 입력해주세요.');
                return false;
            } else if (!tel.value || tel.value !== (telMatch !== null ? telMatch.join('') : '')) {
                alert('연락처를 입력해주세요.\n최대 11글자 입니다.');
                return false;
            }

            if (name.value && tel.value) {
                let con = confirm('저장하시겠습니까?');
                if (con) {
                    const data = {
                        "idx": tr.dataset.idx,
                        "writer": hiddenWriter.value,
                        "name": name.value,
                        "phone_number": tel.value
                    };

                    fetch('http://127.0.0.1:8000/save', {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data),
                    })
                        .then(data => data.json())
                        .then((json) => {
                            if (!json || typeof json !== 'object') {
                                alert('잘못된 응답입니다.');
                                return;
                            }
                            if (typeof json.errors === 'object') {
                                let msg = json.message;
                                for (const field in json.errors) {
                                    msg += "\n" + json.errors[field];
                                }
                                alert(msg);
                                return;
                            }

                            tr.innerHTML = `
                                    <td class="name">
                                        <input type="hidden" class="hidden-name" value="${json.name}">
                                        <input type="text" class="input-name" value="${json.name}" readonly>
                                    </td>
                                    <td class="tel">
                                    <input type="hidden" class="hidden-tel" value="${json.phone_number}">
                                        <input type="tel" class="input-tel" value="${json.phone_number}" readonly>
                                    </td>
                                    <td class="date">
                                        <div class="date-at">${json.created_at !== json.updated_at ? json.updated_at : json.created_at}</div>
                                    </td>
                                    <td class="btn-group">
                                        <div class="btn-wrap">
                                        <button type="button" class="btn amend">수정</button>
                                        <button type="button" class="btn delete">삭제</button>
                                        </div>
                                    </td>
                                `;
                        })
                        .catch((error) => {
                            console.error('실패:', error);
                        });
                }
            }
        }
        //취소
        if (eTarget.classList.contains('cancel')) {
            name.readOnly = true;
            tel.readOnly = true;

            console.log('cancel');
            tr.querySelector('.input-name').value = tr.querySelector('.hidden-name').value;
            tr.querySelector('.input-tel').value = tr.querySelector('.hidden-tel').value;

            tr.querySelector('.btn-wrap').innerHTML = `
                    <button type="button" class="btn amend">수정</button>
                    <button type="button" class="btn delete">삭제</button>
                `;
        }
    });
}

//검색영역 스크립트
if (search) {
    search.querySelector('.btn').addEventListener('click', () => {
        const data = {
            "writer": hiddenWriter.value,
            "select": search.querySelector('select').value,
            "search": search.querySelector('.input-search').value
        };

        fetch('http://127.0.0.1:8000/search', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data),
        })
            .then(data => data.json())
            .then((json) => {
                if (!json || typeof json !== 'object') {
                    alert('잘못된 응답입니다.');
                    return;
                }
                if (typeof json.errors === 'object') {
                    let msg = json.message;
                    for (const field in json.errors) {
                        msg += "\n" + json.errors[field];
                    }
                    alert(msg);
                    return;
                }

                console.log();
                let tableTbody = document.querySelector('.common-table tbody'),
                    pagination = document.querySelector('.pagination-wrap');
                tableTbody.innerHTML = ``;
                if (json.data.length) {
                    json.data.map((ele) => {
                        let trEle = document.createElement('tr');
                        trEle.dataset.idx = `${ele.idx}`;
                        trEle.innerHTML = `
                                <td class="name"><input type="text" class="input-name" readonly="" value="${ele.name}"></td>
                                <td class="tel"><input type="tel" class="input-tel" readonly="" value="${ele.phone_number}"></td>
                                <td class="date">
                                    <div class="date-at">${ele.created_at !== ele.updated_at ? ele.updated_at : ele.created_at}</div>
                                </td>
                                <td class="btn-group">
                                    <div class="btn-wrap">
                                        <button type="button" class="btn amend">수정</button>
                                        <button type="button" class="btn delete">삭제</button>
                                    </div>
                                </td>
                            `;
                        tableTbody.appendChild(trEle);
                    });
                } else {
                    let trEle = document.createElement('tr');
                    trEle.innerHTML = `
                            <tr>
                                <td colspan="5" class="none">검색된 리스트가 없습니다.</td>
                            </tr>
                        `;
                    tableTbody.appendChild(trEle);
                }

                pagination.innerHTML = ``;
            })
            .catch((error) => {
                console.error('실패:', error);
            });
    });
}
