# 각 레이어별 데이터 전송 방법

### 직렬화(Serialization)

- 웹은 요청/응답을 Binary 소켓 전송이 아닌 단순한 텍스트(Plain Text) 전송으로 모든 데이터 전송을 처리한다
- 개발에서 사용되는 데이터 구조, 객체 등을 전송하려고 할 때 이기종 영역에 해당 데이터를 전송하기 위해 텍스트화 하는 과정을 **직렬화**라고 한다

### 역직렬화(Deserialization)

### 파서

### JSON

- 브라우저에서 대부분 실행되는 웹의 특성상 자바스크립트를 거의 기본적으로 사용할 수 있는 상태이기 떄문에 클라이언트에 데이터를 전송할 때 가장 많이 사용되는 형태이다
- 문법 구조가 비교적 단순하지만 가독성이 상대적으로 떨어진다는 단점이 존재
- xml 에 비해서 크기가 매우 작은편이다
- 특정한 필드를 추가하지 않는 한 객체의 메타데이터 구조를 잃기 쉽다
- [예제 파일 보기](./application/views/sample/json.php)

### only PHP Side

- `serialize`, `unserialize` 함수를 통해서 실행한다
- PHP 사이드에서 사용되는 인스턴스 들을 저장할 떄 사용됨 (저장하고 DB나 파일로 바꾸어서 저장하는게 일반적)
- [php_serialize](./application/controllers/Sample.php)
- [php_unserialize](./application/controllers/Sample.php)

## PHP -> Frontend (Javascript)

- PHP 의 객체를 Javascript 로 바꾸기 위한 가장 간단한 방법은 json_encode 로 직렬화를 거친 후 직렬화된 내용을 `JSON.parse` 메서드로 객체화 시키는 방법입니다

```php
    # /sample/form  (/application/views/sample/form.php)

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
```

## Form Submit to PHP Backend Side

```html
    <!-- FORM 태그, sample 컨트롤러의 save 메서드에 POST 메서드로 넘긴다 -->
    <!-- 각 input 태그들의 name 속성에 주목하자 -->
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
```

```php
# application/controllers/Sample.php

class Sample {
    # ...

    public function save()
    {
        $all = $this->input->post();        // 전체 POST 객체

        $fair = $this->input->post('fair'); // fair 전시회 정보만 가져오기

        dump($all, $fair);
    }
}

```

## Frontend - Get Data By API with JSON

```html
    <!-- /sample/json  (application/views/sample/json.php) -->
    <script>
        $.ajax({
            type: "get",
            url: "/sample/api_fair",
            data: {
                fairName: '2023 경향하우징페어 (코엑스)',
            },
            dataType: "json",
            success: function (fair) {
                console.log(fair);
            }
        });

    </script>
```

```php
# application/controllers/Sample.php

class Sample {
    # ...

    public function api_fair()
    {
        $fair = new Fair(
            $this->input->get('fairName') ?? '2023 코리아빌드(킨텍스)',
            new DateTime('2023-03-01'),
            new DateTime('2023-03-06'),
            ['abcd', '1234', '하하호호'],
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($fair, JSON_UNESCAPED_UNICODE));
    }
}
```

## Javascript -> PHP Backend Side (with Ajax)

```html

    <script>
        // vue App
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
    </script>

```

- 단 파일 전송시 ajax 설정값을 몇가지 더 추가해야 되고, 전송을 FormData 객체로 해야함 (https://cloud0477.tistory.com/122 참조)
