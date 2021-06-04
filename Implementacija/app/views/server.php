<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    
    <h1>Server is working...</h1>
    <h2 id="cnt">0</h2>
    <script>  
        
    
    alert("start");
    timer();
    let timeUpdate = setInterval(timer, 120000); 
    let callCnt = 0;
    function timer() {
        window.open('<?= site_url("ServerController/tradingAssistant/")?>', '_blank');
        var cnt = document.getElementById("cnt").textContent;
        cnt = parseInt(cnt);
        cnt += 1;
        document.getElementById("cnt").innerHTML = cnt;
    }
    
    </script>
    
</body>
</html>