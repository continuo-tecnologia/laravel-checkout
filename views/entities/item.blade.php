<li class="list-group list-group-item">

    <div class="row">
        <div class="col-6 col-md-4 col-lg-5">{{$item->title}}</div>
        <div class="col-6 col-md-4 col-lg-2 text-center">R$ {{$item->unit_price}}</div>
        <div class="col-12 col-md-4 col-lg-2 text-center">
            <i onclick="removeItemFromCart({{$item->id}})" class="fas fa-minus-circle"></i>
            {{$item->quantity}}
            <i onclick="addItemToCart({{$item->id}})" class="fas fa-plus-circle"></i>
        </div>
        <div class="col-12 col-md-4 col-lg-3 text-center text-md-right">R$ {{$item->price()}}</div>
    </div>
    
</li>