<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>CRAWL TOOL</title>
</head>
<body>
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-3">CRAWL TOOL OF 3D</h1>
            <p class="lead">Maximum 2304 products</p>
            <p class="lead"> 48 pages & 1 page = 48 products</p>
            <hr class="my-2">
            <p>More info</p>
        </div>

        <div class="content">
            <form action="{{route('crawl')}}" method = "POST">
                @csrf
                <label for="">URL web: </label>  
                <input type="text" name="url" style="width:800px; margin-left: 54px">
                <br>
                <label for="">Số trang có :   </label>
                <input type="text" name="number_page" style="width:300px; margin-left: 30px">
                <br>
                <label for="">Tên file để lưu:  </label>
                <input type="text" name="name" style="width:300px; margin-left: 16px">
                
                <br>
                <button class="btn-primary">Crawl</button>
            </form>
            
        </div>

    </div>

</body>
</html>