<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact</title>
    {{-- style --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<body>
<div class="wrap">
    <header class="common-header">
        <div class="inner">
            <h1 class="common-title">
                <a href="/contact" class="link">
                    <svg width="81" height="25" viewBox="0 0 81 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9.17676 2.29516H3.11076V7.60339H9.20841V9.86919H3.10548V17.6647H0.468094V0H9.17148L9.17676 2.29516Z"
                            fill="#1D1D1B"></path>
                        <path d="M24.6318 17.6647H16.2555V0H18.914V15.3989H24.6318V17.6647Z" fill="#1D1D1B"></path>
                        <path
                            d="M39.9683 9.40216L39.3933 10.926C40.7723 11.4832 41.916 12.5099 42.627 13.829C43.3381 15.1481 43.5719 16.6769 43.2883 18.1518C43.0046 19.6267 42.2213 20.9552 41.0733 21.9082C39.9253 22.8612 38.4846 23.3789 36.9997 23.3722C35.5149 23.3654 34.0789 22.8345 32.9394 21.8711C31.8 20.9076 31.0285 19.572 30.758 18.0946C30.4875 16.6172 30.735 15.0905 31.4578 13.778C32.1806 12.4654 33.3333 11.4493 34.7172 10.9047L34.1291 9.37815C32.3943 10.0616 30.9498 11.3366 30.0449 12.9829C29.1399 14.6292 28.8314 16.5435 29.1726 18.3954C29.5137 20.2473 30.4831 21.9206 31.9134 23.1264C33.3438 24.3322 35.1452 24.9949 37.0069 25C38.8685 25.0051 40.6735 24.3523 42.1103 23.1544C43.547 21.9564 44.5254 20.2885 44.8765 18.4385C45.2275 16.5885 44.9293 14.6725 44.0332 13.0212C43.1371 11.37 41.6994 10.0872 39.9683 9.39416V9.40216Z"
                            fill="#3FB6E8"></path>
                        <path d="M37.8531 0.790001H36.2047V16.8721H37.8531V0.790001Z" fill="#3FB6E8"></path>
                        <path
                            d="M63.8894 17.6647H61.2863L52.7464 6.18893C52.6334 6.04598 52.5278 5.89722 52.4299 5.74324C52.2796 5.51906 52.124 5.2762 51.9578 5.01466C51.7917 4.75312 51.6308 4.4809 51.4778 4.23537C51.3248 3.98984 51.2141 3.78435 51.1402 3.64824V17.6541H48.6875V0H51.4778L59.6274 11.0701C59.7619 11.2623 59.9201 11.4918 60.0994 11.7614C60.2788 12.0309 60.4555 12.2951 60.6269 12.5807C60.7984 12.8662 60.9592 13.1144 61.1069 13.3813C61.2546 13.6482 61.3707 13.835 61.4524 13.9871V0H63.892L63.8894 17.6647Z"
                            fill="#1D1D1B"></path>
                        <path d="M80.4681 2.29516H76.0716V17.6647H73.4342V2.29516H69.0007V0H80.4681V2.29516Z"
                              fill="#1D1D1B"></path>
                    </svg>
                    <span class="txt">CONTACT</span>
                </a>
            </h1>
        </div>
    </header>
    <section class="main-container">
        <div class="inner">
            <h2 class="is-blind">본문영역</h2>
            <input type="hidden" name="writer" value="{{ $id }}">
            @if($id === '')
                <div class="label-box">
                    <label>
                        <span class="txt">사용자 이름</span>
                        <input type="text" class="input-name" name="id" placeholder="한글 이름을 입력해주세요.">
                    </label>
                    <button type="button" class="btn">전송</button>
                </div>
            @else
                <div class="info-wrap">
                    <p class="info">
                        안녕하세요. {{$id}}님
                        <br>
                        <span>연락처를 입력해보세요.</span>
                    </p>
                    <div class="search-wrap">
                        <select name="search-select" class="select-search">
                            <option value="name">이름</option>
                            <option value="phone_number">연락처</option>
                        </select>
                        <input type="text" class="input-search" placeholder="검색어를 입력하세요.">
                        <button type="button" class="btn">전송</button>
                    </div>
                </div>
                <hr>
                <div class="name-box">
                    <input type="text" class="input-name" placeholder="한글 이름을 입력해주세요.">
                    <input type="tel" class="input-tel" placeholder="연락처를 입력해주세요.">
                    <button type="button" class="btn">전송</button>
                </div>
                <div class="table-wrap">
                    <table class="common-table">
                        <colgroup>
                            <col class="col-1">
                            <col class="col-2">
                            <col class="col-3">
                            <col class="col-4">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>이름</th>
                            <th>연락처</th>
                            <th>등록일</th>
                            <th><span class="is-blind">버튼</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($list) > 0)
                            @foreach($list as $key => $val)
                                <tr data-idx="{{ $val->idx }}">
                                    <td class="name">
                                        <input type="hidden" class="hidden-name" value="{{ $val->name }}">
                                        <input type="text" class="input-name" readonly value="{{ $val->name }}">
                                    </td>
                                    <td class="tel">
                                        <input type="hidden" class="hidden-tel" value="{{ $val->phone_number }}">
                                        <input type="tel" class="input-tel" readonly value="{{ $val->phone_number }}">
                                    </td>
                                    <td class="date">
                                        <div
                                            class="date-at">{{ $val->created_at !== $val->updated_at ? $val->updated_at : $val->created_at }}</div>
                                    </td>
                                    <td class="btn-group">
                                        <div class="btn-wrap">
                                            <button type="button" class="btn amend">수정</button>
                                            <button type="button" class="btn delete">삭제</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="none">등록된 리스트가 없습니다.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrap">
                    {{ $list->links() }}
                </div>
            @endif
        </div>
    </section>
</div>

<script src="{{asset('js/index.js')}}"></script>
</body>
</html>
