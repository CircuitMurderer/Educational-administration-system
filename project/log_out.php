<body>
<script>
    function setCookie(cname, c_value, ex_secs) {
        let d = new Date();
        d.setTime(d.getTime() + (ex_secs * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + c_value + "; " + expires;
    }

    let jug = confirm("确定要登出吗？");
    if (jug) {
        setCookie("uid", "", -60 * 60);
        alert("成功登出！");
        window.location.replace("index.php");
    } else {
        window.history.go(-1);
    }
</script>
</body>
