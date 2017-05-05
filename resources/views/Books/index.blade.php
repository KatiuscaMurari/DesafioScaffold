@extends('layouts.app')

@section('content')

<a href="{{route('Books.create')}}" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Cadastrar</a>
<a href="{{url('/Books/importBooks')}}" class="btn btn-default"><span class="glyphicon glyphicon-import"></span> Importar</a>
<br><br>

@if(\Session::has('success'))
<div class="alert alert-success" role="alert">
    @foreach(Session::get('success') as $men)
    <p>{{$men}}</p>
    @endforeach
</div>
@endif

@if(isset($errors) && count($errors) > 0)
<div class="alert alert-danger" role="alert">
    @foreach($errors->all() as $error)
    <p>{{$error}}</p>
    @endforeach
</div>
@endif

@if(count($books) <= 0)
<div class="alert alert-info" role="alert">
    <span class="glyphicon glyphicon-info-sign"></span> Não existem livros cadastrados!
</div>
@else    
<table class="table table-striped">
    <thead>
        <tr>
            <th width="25%">Nome</th>
            <th width="15%">Autor</th>
            <th width="10%">Editora</th>
            <th width="35%">Descrição</th>
            <th style="text-align: center" width="80px" >Imagem</th>
            <th style="text-align: center" width="80px">Ações</th>
        </tr>
    </thead>    
    <tbody>
        @foreach($books as $book)
        <tr>
            <td>{{$book->title}}</td>
            <td>{{$book->authors}}</td>
            <td>{{$book->publisher}}</td>
            <td>
                {!! str_limit($book->description, $limit = 100, $end = '...') !!}</td>
            <td align="center">
                @if(isset($book->image) && !empty($book->image))
                <a href="{{url($book->image)}}" target="_blank"><img src="{{$book->image}}" height="50px"></a>
                @endif
            </td>
            </td>
            <td align="center">
                {!! Form::open(['route' => ['Books.destroy', $book->id], 'method' => 'DELETE', 'class' => 'form-delete']) !!}                
                <a href="{{route('Books.edit', $book->id)}}"><span class="glyphicon glyphicon-pencil"></span></a>
                <button type="submit" class="btnDelte"><span class="glyphicon glyphicon-trash"></span></button>
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class='divPagination'>{{ $books->links() }}</div>
@endif
</div>


@endsection
