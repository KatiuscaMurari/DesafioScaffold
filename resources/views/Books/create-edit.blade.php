@extends('layouts.app')

@section('content')


@if(isset($errors) && count($errors) > 0)
<div class="alert alert-danger" role="alert">
    @foreach($errors->all() as $error)
    <p>{{$error}}</p>
    @endforeach
</div>
@endif

@if( isset($book))
{!! Form::model( $book, ['route' => ['Books.update', $book->id], 'class' => 'form', 'method' => 'PUT','files'=>true]) !!} 
@else
{!! Form::open(['route' => 'Books.store', 'class' => 'form','files'=>true]) !!} 
@endif

<p class="textObrigatorio">Campos com * são obrigatórios.</p>
<div class="form-group">
    <label for="name">Título: <span class="textObrigatorio">*</span></label>
    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Título *']) !!}
</div>

<div class="form-group">
    <label for="authors">Autor(es): <span class="textObrigatorio">*</span></label>
    {!! Form::text('authors', null, ['class' => 'form-control', 'placeholder' => 'Autor(es) *']) !!}
</div>

<div class="form-group">
    <label for="publisher">Editora: </label>
    {!! Form::text('publisher', null, ['class' => 'form-control', 'placeholder' => 'Editora']) !!}
</div>

<div class="form-group">
    <label for="image">Imagem: </label>
    {!! Form::file('image', ['class' => 'form-control'])!!}
    @if( isset($book->image))
    <br>
    <a href="{{url($book->image)}}" target="_blank"><img src="{{url($book->image)}}" height="200px"></a>
    @endif
</div>

<div class="form-group">
    <label for="description">Descrição: </label>
    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Descrição']) !!}
</div>
<p>
    {!! Form::submit('Salvar', ['class' => 'btn btn-success'] ) !!}
    <a href="{{route('Books.index')}}" class="btn btn-warning" >Cancelar</a>
</p>
{!! Form::close() !!}

@endsection