<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\BookFormRequests;
use App\Http\Requests\BookImportFormRequests;
use App\Http\Requests\BookImportZipRequests;
use Excel;
use ZipArchive;

class BookController extends Controller {

    private $book;

    public function __construct(Book $book) {
        $this->book = $book;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $books = $this->book->orderBy('title', 'asc')->paginate(20);
        $pageTitle = 'Livros';

        return view('books.index', compact('pageTitle', 'books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $pageTitle = 'Livros - Cadastrar';

        return view('books.create-edit', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookFormRequests $request) {
        $dataForm = $request->all();
        $insert = $this->book->create($dataForm);
        $sucess = false;

        if ($insert) {
            if ($request->hasFile('image')) {
                $imageName = $insert->id . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(base_path() . '/public/images/books/', $imageName);

                $dataForm = $request->all();
                $dataForm['image'] = "images/books/" . $imageName;

                $book = $this->book->find($insert->id);
                $update = $book->update($dataForm);

                if ($update)
                    $sucess = true;
            } else
                $sucess = true;
        }

        if ($sucess)
            return redirect()->route("Books.index")->with('success', ['Registro criado com sucesso!']);
        else
            return redirect()->route("Books.index")->with(['errors' => 'Falha ao salvar registro!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $book = $this->book->find($id);
        $pageTitle = 'Livros - Editar';

        return view('books.create-edit', compact('pageTitle', 'book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\BookImportFormRequests  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookFormRequests $request, $id) {
        $book = $this->book->find($id);
        $dataForm = $request->all();

        if ($request->hasFile('image')) {
            $imageName = $id . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path() . '/public/images/books/', $imageName);

            $dataForm['image'] = "images/books/" . $imageName;
        }

        $update = $book->update($dataForm);

        if ($update)
            return redirect()->route("Books.index")->with('success', ['Registro atualizado com sucesso!']);
        else
            return redirect()->route("Books.index")->with(['errors' => 'Falha ao atualizar registro!']);
    }

    /**
     * Remove the specified resource from'storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        //Deleta a imagem
        $book = $this->book->find($id);

        if (file_exists($book->image))
            unlink($book->image);


        //Deleta o registro
        $delete = $this->book->destroy($id);

        if ($delete)
            return redirect()->route("Books.index")->with('success', ['Registro deletado com sucesso!']);
        else
            return redirect()->route("Books.index")->with(['errors' => 'Falha ao deletar registro!']);
    }

    /*
     * Retorna a tela de importação de dados
     */

    public function importBooks() {
        $pageTitle = 'Livros - Importar';

        return view("Books.import", compact('pageTitle'));
    }

    /*
     * Processa a importação em CSV
     */

    public function doImportCSV(BookImportFormRequests $request) {

        if ($request->hasFile('fileCSV'))
            $this->processImportCSV($request->file('fileCSV')->getRealPath());

        return redirect("/Books/importBooks")->with('success', ['Registros importados com sucesso!']);
    }

    /*
     * Processa a importação em ZIP (Com imagens)
     */

    public function doImportZIP(BookImportZipRequests $request) {

        $file = $request->file('fileZIP')->getRealPath();
        $tempFolder = 'importZip/' . date("Y-m-d_H-i-s") . '/';

        $zip = new ZipArchive;
        $res = $zip->open($file);
        if ($res === TRUE) {
            $zip->extractTo($tempFolder);
            $zip->close();

            $sucess = $this->processImportCSV($tempFolder . 'importBooks.csv', $tempFolder);

            $this->deleteDir($tempFolder);
        } else {
            return redirect("/Books/importBooks")->with('errors', ['Erro ao importar registros!']);
        }

        if ($sucess === true)
            return redirect("/Books/importBooks")->with('success', ['Registros importados com sucesso!']);
        else
            return redirect("/Books/importBooks");
    }

    /*
     * Le e processa os dados do CSV de importação
     * @file                path real do arquivo CSV
     * @folderImage         pasta onde a imagem esta armazenada
     * @urlRedirectError    url para redirecionar em caso de erro
     */

    private function processImportCSV($file, $folderImage = '', $urlRedirectError = "/Books/importBooks") {
        $img = ['jpeg', 'bmp', 'png', 'jpg'];

        if (!file_exists($file))
            return redirect($urlRedirectError)->with('errors', ['O arquivo csv não foi encontrado para realizar a importação!']);

        //Proessa os registros
        $data = Excel::load($file, function($reader) {
                    
                })->get();

        if (!empty($data) && $data->count()) {
            foreach ($data->toArray() as $key => $value) {

                if (!empty($value['titulo']) && !empty($value['autores'])) {
                    $item = [
                        'title' => substr($value['titulo'], 0, 300)
                        , 'authors' => substr($value['autores'], 0, 200)
                        , 'publisher' => empty($value['editora']) || !isset($value['editora']) ? '' : substr($value['editora'], 0, 100)
                        , 'description' => empty($value['descricao']) || !isset($value['descricao']) ? '' : substr($value['descricao'], 0, 5000)
                    ];

                    $book = $this->book
                            ->where('title', $item['title'])
                            ->where('authors', $item['authors'])
                            ->where('publisher', $item['publisher'])
                            ->first();

                    if(!isset($value['imagem']))
                        $value['imagem'] = '';
                        
                    $extencion = substr($value['imagem'], strripos($value['imagem'], '.') + 1);

                    $hasImage = !empty($value['imagem']) && !empty($folderImage) && file_exists($folderImage . $value['imagem']) && in_array($extencion, $img);

                    if (isset($book->id) && $book->id > 0) {

                        //Caso exista o campo de imagem e o mesmo esteja preenchido, realiza os procedimentos de salvamento da imagem
                        if ($hasImage == true) {
                            $extencion = substr($value['imagem'], strripos($value['imagem'], '.') + 1);
                            $item['image'] = "images/books/" . $book->id .'.'. $extencion;
                            @unlink($item['image']);
                            rename($folderImage . $value['imagem'], $item['image']);
                        }

                        $update = $book->update($item);

                        if (!$update)
                            return redirect($urlRedirectError)->with('errors', ['Erro ao atualizar o registro!']);
                    } else {
                        $insert = $this->book->create($item);

                        //Caso exista o campo de imagem e o mesmo esteja preenchido, realiza os procedimentos de salvamento da imagem
                        if ($hasImage == true) {
                            $book = $this->book
                                    ->where('title', $item['title'])
                                    ->where('authors', $item['authors'])
                                    ->where('publisher', $item['publisher'])
                                    ->first();

                            $extencion = substr($value['imagem'], strripos($value['imagem'], '.') + 1);
                            $item['image'] = "images/books/" . $book->id . '.' . $extencion;
                            rename($folderImage . $value['imagem'], $item['image']);

                            $book->update($item);
                        }

                        if (!$insert)
                            return redirect($urlRedirectError)->with('errors', ['Erro ao incluir o registro!']);
                    }
                }
            }
        }

        return true;
    }

    /*
     * Exclui um diretio, recursivo
     * @dir     caminho do diretorio
     */

    private function deleteDir($dir) {

        //Excluir arquivos temporarios
        $files = glob($dir . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dir);
    }

}
