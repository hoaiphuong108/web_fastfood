<?php
http_response_code(404);
?>

<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>404 - Page Not Found</title>

    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;700&display=swap" rel="stylesheet">

    <style>
    * {
        box-sizing: border-box
    }

    html,
    body {
        height: 100%;
        margin: 0;
        font-family: "Cabin", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        color: black;

    }

    .wrap {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        margin-left: 210px;
    }

    .card {
        text-align: center;
        max-width: 900px;
        width: 90%;
        padding: 20px;
    }

    .label {
        font-size: 14px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: black;
        margin-bottom: 10px;
    }

    .big404 {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2px;
        line-height: 1;
        color: red;
    }

    .digit {
        font-weight: 900;
        font-size: calc(90px + 9vw);
        color: red;
        position: relative;
        display: inline-block;
        padding: 0 6px;
        letter-spacing: -6px;
    }

    .digit.zero {
        border-radius: 999px;
        padding: 0 30px;
        box-shadow: inset 0 0 0 14px rgba(255, 255, 255, 0.0);
        color: red;
        margin: 0 -45px;
        z-index: 3px;
    }

    .digit.zero::after {
        content: "";
        position: absolute;
        right: 8%;
        top: 6%;
        height: 70%;
        width: 4px;
        transform: translateX(1px);
        border-radius: 2px;
        opacity: 0.98;
    }

    .desc {
        margin-top: 14px;
        font-size: 20px;
        color: black;
    }

    .actions {
        margin-top: 22px;
    }

    .btn {
        display: inline-block;
        text-decoration: none;
        padding: 10px 18px;
        border-radius: 6px;
        background: transparent;
        border: 1px solid black;
        color: black;
        font-weight: 600;
        transition: all .18s ease;
    }

    .btn:hover {
        background: black;
        color: #fff;
    }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card" role="main" aria-labelledby="pageTitle">
            <div class="label" id="pageTitle">Rất tiếc! Không tìm thấy trang</div>

            <div class="big404" aria-hidden="true">
                <span class="digit">4</span>
                <span class="digit zero">0</span>
                <span class="digit">4</span>
            </div>

            <div class="desc">
                Xin lỗi, nhưng trang bạn yêu cầu không tồn tại.
            </div>

            <div class="actions">
                <a class="btn" href="/web_fastfood/admin/index.php" title="Back to home">Quay về trang chủ</a>
            </div>
        </div>
    </div>
</body>

</html>