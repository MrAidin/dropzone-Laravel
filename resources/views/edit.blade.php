<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css"/>
    <style>
        .dz-preview .dz-image img{
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
        }
    </style>
    <title>Edit Post</title>
</head>
<body>
<div class="row">
    <div class="col-md-10 offset-1">
        <div class="mr-3">
            <form action="{{route('edit-product.edit',$product->id)}}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    عنوان
                    <input type="text" value="{{$product->title}}" name="title" class="form-control">
                </div>
                <br>
                <div class="form-group">
                    گالری تصاویر
                    <input type="hidden" name="photo_id[]" id="product-photo">
                    <div id="photo" class="dropzone"></div>
                </div>
                <div class="form-group">
                    <br>
                      تصاویر
                    @foreach($product->photos as $photo)
                    <img src="{{$photo->path}}" width="100" >
                    @endforeach
                </div>
                <div class="form-group">
                    <br>
                    <input type="submit" value="ارسال" class="btn btn-success">
                </div>

            </form>

        </div>

    </div>

</div>
</body>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>

<script>
    Dropzone.autoDiscover = false;
    var photos = {{$product->photos->pluck('id')}};
    var photosGallery = [];
    $.each(photos,function(key,value){
        console.log(value);
        photosGallery.push(value)
    });
    document.getElementById("product-photo").value = photosGallery
    var drop = new Dropzone('#photo', {
        acceptedFiles: ".png,.jpg,.jpeg",
        addRemoveLinks: true,


        init:function (){
            $.ajax({
                url: "{{route('getProductImage',$product->id)}}",
                type: "get",
                datatype: 'json',
                success: function (data) {
                    $.each(data,function (key,value){
                      var mockFile = {name:value.id,size:value.original_name};
                        drop.emit("addedfile",mockFile);
                        drop.emit("thumbnail",mockFile,value.path);
                        drop.emit("complete",mockFile);
                    })
                }
            });
        },


        removedfile: function (file) {
            console.log('delete create');
            var id = file.previewElement.querySelector('[data-dz-name]').innerHTML;
            console.log(photosGallery);
            console.log(id);
            // console.log(photos);
            removeImage(photosGallery, id)
            console.log(photosGallery);
            var _ref;
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;


        },
        url: "{{route('upload')}}",
        sending: function (file, xhr, formData) {
            formData.append("_token", "{{csrf_token()}}")

        },
        success: function (file, response) {
            photosGallery.push(response.photo_id)
            document.getElementById("product-photo").value = photosGallery

            console.log(photosGallery);

            var fileuploded = file.previewElement.querySelector("[data-dz-name]")
            console.log('before to ' + fileuploded.innerHTML);
            fileuploded.innerHTML = response.photo_id;
            console.log('after ' + fileuploded.innerHTML);


        }
    });

    function removeImage( array, item) {
       let id = parseInt(item);
        for (var i = 0; i < array.length; i++) {
            if (array[i] === id) {
                array.splice(i, 1);
            }
        }

        var form = {
            "_token": "{{ csrf_token() }}",
            photo_id: id,
            all_photo_id : photosGallery,
            product_id : parseInt({{$product->id}})
        };
        $.ajax({
            url: "{{route('delete.photo.update')}}",
            type: "post",
            data: form,
            success: function (data) {
                console.log(data);
                document.getElementById("product-photo").value = array
            }
        });
    }

</script>


</html>
