<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>데이터 전송 예제</title>

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css'
          integrity='sha512-Z/def5z5u2aR89OuzYcxmDJ0Bnd5V1cKqBEbvLOiUNWdg9PQeXVvXLI90SE4QOHGlfLqUnDNVAYyZi8UwUTmWQ=='
          crossorigin='anonymous' />
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js'
            integrity='sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=='
            crossorigin='anonymous'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js'
            integrity='sha512-42PE0rd+wZ2hNXftlM78BSehIGzezNeQuzihiBCvUEB3CVxHvsShF86wBWwQORNxNINlBPuq7rG4WWhNiTVHFg=='
            crossorigin='anonymous'></script>
    <script>
        // 직렬화/역직렬화 예제
        const fair = {
            name: '2023 코리아빌드 (킨텍스)',
            startDate: moment('2023-03-01'),
            endDate: moment('2023-03-05')
        }

        console.log('원본 데이터', fair);

        const fairJson = JSON.stringify(fair); // 직렬화

        console.log('직렬화 결과: ' + fairJson);

        const fair2 = JSON.parse(fairJson); // 역직렬화

        console.log('역직렬화 결과', fair2);

    </script>
</head>

<body>
    <div class="container" id="app">
        <form onsubmit="return false">
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
                <button type="button" class="btn btn-primary" @click="saveFair">전송</button>
            </div>
        </form>
    </div>

    <script>
        const keywords = ['abcd', '1234', '하하호호'];

        let app = Vue.createApp({
            data () {
                return {
                    fair: {}, // PHP -> Javascript 객체로 변환
                    keywords: keywords,
                }
            },
            mounted () {
                var self = this;
                $.ajax({
                    type: "get",
                    url: "/sample/api_fair",
                    data: {
                        fairName: '2023 경향하우징페어 (코엑스)',
                    },
                    dataType: "json",
                    success: function (fair) {
                        self.fair = fair;
                    }
                });
            },
            methods: {
                saveFair () {
                    $.ajax({
                        type: "post",
                        url: "/sample/save",
                        data: {
                            fair: this.fair
                        },
                        dataType: "html",
                        success: function (html) {
                            $('#app').append(html);
                        }
                    });
                }
            }
        }).mount('#app');

    </script>
</body>

</html>
