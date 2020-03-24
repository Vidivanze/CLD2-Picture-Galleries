
<a href="{{ route('pictures.index')}}"><button>Back to gallery</button></a>

<form action="{{ route('pictures.destroy', $picture)}}" method="POST">
    @csrf
    {{method_field('DELETE')}}
    <button type="submit">Delete this image</button>
</form>

<h3>{{$picture->title}}</h3>
<img src="{{ route('pictures.show', $picture->id)}}">

