@extends('layouts.app')

@section('content')

@if(\Session::has('success'))
<div class="alert alert-success" role="alert">
    @foreach(Session::get('success') as $men)
    <p>{{$men}}</p>
    @endforeach
</div>
@endif

@if(isset($errors) && count($errors) > 0)
<div class="alert alert-danger" role="alert">
    @foreach($errors as $error)
    <p>{{$error}}</p>
    @endforeach
</div>
@endif

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#semImagens" aria-controls="semImagens" role="tab" data-toggle="tab">Sem Imagens</a></li>
    <li role="presentation"><a href="#comImagens" aria-controls="comImagens" role="tab" data-toggle="tab">Com Imagens</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="semImagens">
        {!! Form::open(['url' => url('/Books/doImportCSV'), 'class' => 'form', 'files'=>true]) !!} 

        <br>
        <p>Para realizar a importação dos livros sem imagens via arquivo CSV, é necessário baixar o arquivo padrão e preenchê-lo corretamente.</p>

        <p><a href="{{url('files/importBooks.csv')}}" target="_blank" class='btn btn-default'><span class='glyphicon glyphicon-save'></span> Arquivo padrão</a> </p>
        <br>

        <div class="alert alert-info" role="alert">
            <p>Campos do arquivo CSV:</p>
            <ul>
                <li>Título: Obrigatório; Máximo de 300 caracteres;</li>
                <li>Autor(es): Obrigatório; Máximo de 200 caracteres;</li>
                <li>Editora: Opcional; Máximo de 100 caracteres;</li>
                <li>Descrição: Opcional; Máximo de 5000 caracteres;</li>
            </ul>
        </div>

        <div class="alert alert-warning" role="alert">
            <p><strong>Atenção!</strong></p>
            <ul>
                <li>Caso o registro não possua algum dos campos obrigatórios preenchidos, ele será ignorado.</li>
                <li>Caso o tamanho do texto ultrapasse o limite máximo do campo, o excedente será desconsiderado.</li>
                <li>Caso já exista um livro com o mesmo título, autor(es) e editora, o mesmo tera seus dados atualizados.</li>
                <li>Caso o registro a ser atualizado possua uma imagem, a mesma não será alterada.</li>
            </ul>
        </div>

        <p>
            <label for="fileCSV">Arquivo CSV: </label>
            {!! Form::file('fileCSV', ['class' => 'form-control'])!!}
        </p>

        <br><br>
        <p>
            {!! Form::submit('Importar dados', ['class' => 'btn btn-success'] ) !!}
            <a href="{{route('Books.index')}}" class="btn btn-warning" >Cancelar</a>
        </p>

        {!! Form::close() !!}

    </div>
    <div role="tabpanel" class="tab-pane" id="comImagens">
        {!! Form::open(['url' => url('/Books/doImportZIP'), 'class' => 'form', 'files'=>true]) !!} 
        <br>
        <p>Para fazer a importação dos livros com imagens é necessário baixar o arquivo padrão ZIP e preencher o arquivo CSV corretamente e anexar as imagens de acordo com as instruções.</p>

        <p><a href="{{url('files/importBooks.zip')}}" target="_blank" class='btn btn-default'><span class='glyphicon glyphicon-save'></span> Arquivo padrão</a> </p>
        <br>

        <div class="alert alert-info" role="alert">
            <p>Campos:</p>
            <ul>
                <li>Título: Obrigatório; Máximo de 300 caracteres;</li>
                <li>Autor(es): Obrigatório; Máximo de 200 caracteres;</li>
                <li>Editora: Opcional; Máximo de 100 caracteres;</li>
                <li>Imagem: Opcional; Extensões permitidas: jpeg, bmp, png, jpg; Deve ser o nome do arquivo acompanhado da extinção, Ex: “Capa do livro.jpg”;</li>
                <li>Descrição: Opcional; Máximo de 5000 caracteres;</li>
            </ul>
        </div>

        <div class="alert alert-warning" role="alert">
            <p><strong>Atenção!</strong></p>
            <ul>
                <li>Caso o registro não possua algum dos campos obrigatórios preenchidos, ele será ignorado.</li>
                <li>Caso o tamanho do texto ultrapasse o limite máximo do campo, o excedente será desconsiderado.</li>
                <li>Caso já exista um livro com o mesmo título, autor(es) e editora, o mesmo tera seus dados atualizados.</li>
                <li>Caso o registro a ser atualizado possua uma imagem e na importação não seja informado uma nova ou esteja com um valor invalido, a imagem antiga não será alterada.</li>
                <li>As imagens devem ficar na raiz do arquivo ZIP, caso estejam dentro de algum outro diretório elas serão desconsideradas.</li>
                <li>Caso a imagem possua uma extensão não suportada, a mesma não será incluída no sistema.</li>
                <li>O nome do arquivo CSV (importBooks.csv) não deve ser modificado, ele também não pode ser movido para dentro de algum outro diretório; caso isso aconteça a importação não ira ocorrer.</li>
            </ul>
        </div>

        <p>
            <label for="fileZIP">Arquivo ZIP: </label>
            {!! Form::file('fileZIP', ['class' => 'form-control'])!!}
        </p>
        <br><br>
        <p>
            {!! Form::submit('Importar dados', ['class' => 'btn btn-success'] ) !!}
            <a href="{{route('Books.index')}}" class="btn btn-warning" >Cancelar</a>
        </p>

        {!! Form::close() !!}
    </div>
</div>

@endsection
