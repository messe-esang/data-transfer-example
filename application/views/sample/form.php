<?php

use MesseEsang\App\Fair;

/**
 * @var Fair $fair
 */

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>데이터 폼전송 예제</title>

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css'
          integrity='sha512-Z/def5z5u2aR89OuzYcxmDJ0Bnd5V1cKqBEbvLOiUNWdg9PQeXVvXLI90SE4QOHGlfLqUnDNVAYyZi8UwUTmWQ=='
          crossorigin='anonymous' />

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>

<body>
    <div class="container" id="app">
        <form method="post" action="/sample/save">
            <div class="mb-3">
                <label for="fairName" class="form-label">이름</label>
                <input type="text" class="form-control" name="fair[name]" id="fairName" aria-describedby="fairName"
                       placeholder="이름을 입력해주세요"
                       v-model="fair.name">
                <small id="fairName" class="form-text text-muted">이름을 입력해주세요</small>
            </div>
            <div class="mb-3">
                <label for="fairStartDate" class="form-label">시작일</label>
                <input type="text" class="form-control" name="fair[startDate]" id="fairStartDate"
                       aria-describedby="fairStartDateHelp" placeholder="시작일을 입력해주세요"
                       v-model="fair.startDate">
                <small id="fairStartDateHelp" class="form-text text-muted">시작일을 입력해주세요</small>
            </div>
            <div class="mb-3">
                <label for="fairEndDate" class="form-label">종료일</label>
                <input type="text" class="form-control" name="fair[endDate]" id="fairEndDate"
                       aria-describedby="fairEndDateHelp" placeholder="종료일을 입력해주세요"
                       v-model="fair.endDate">
                <small id="fairEndDateHelp" class="form-text text-muted">종료일을 입력해주세요</small>
            </div>
            <div class="form-check form-check-inline mb-3" v-for="keyword of keywords">
                <input class="form-check-input" type="checkbox" :id="'keyword_' + keyword"
                       :value="keyword"
                       name="fair[keywords][]"
                       v-model="fair.keywords">
                <label class="form-check-label" :for="'keyword_' + keyword">{{keyword}}</label>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">전송</button>
            </div>
        </form>
    </div>

    <script>
        const keywords = ['abcd', '1234', '하하호호'];

        let app = Vue.createApp({
            data () {
                return {
                    fair: JSON.parse('<?= json_encode($fair) ?>'), // PHP -> Javascript 객체로 변환
                    keywords: keywords,
                }
            }
        }).mount('#app');

    </script>

</body>

</html>
