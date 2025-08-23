    @foreach ($collections as $collection)
        <div class="col-md-6 col-sm-12 mb-5">
            <div class="card card-blog">
                <div class="card-image">
                    <a href="{{route('client.collection.detail', $collection->slug)}}"> 
                        <img class="img card-image-top" src="{{ $collection->thumbnail }}">
                    </a>
                </div>
                <div class="table mt-2 p-3">
                    <a href="#" class="card-caption">{{ $collection->name }}</a>
                    <hr class="border-3 w-25 my-2">
                    <p class="card-description"> {{ $collection->short_description }} </p>
                </div>
            </div>
        </div>
    @endforeach
    <div class="pagination_tgnt d-flex justify-content-center">
        {!! $collections->links('pagination::bootstrap-4') !!}
    </div>