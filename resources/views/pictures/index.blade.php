
<a href="{{ route('pictures.create')}}"><button>Add picture</button></a>
<ul>
    @foreach ($pictures as $picture)
        <li>
            <p>
                <a href="{{ route('pictures.show', $picture->id)}}">
                    {{$picture->title}}
                    <img src="{{ route('pictures.show', $picture->id)}}">
                </a>
            </p>
        </li>
    @endforeach
</ul>
