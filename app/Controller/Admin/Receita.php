<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use clsTinyButStrong;

include_once "app/Utils/tbs_class.php";
include_once "app/Utils/plugins/tbs_plugin_opentbs.php";

class Receita extends Page
{

  /**
   * Método responsável por renderizar a view de Relatório
   * @return string
   */
  public static function getReceita($request)
  {
    /* CONTEÚDO DA HOME */
    $content = View::render('admin/modules/receitas/index', []);

    /* RETORNA A PÁGINA COMPLETA */
    return parent::getPanel('Receitas > GMFARM', $content, 'receitas');
  }

  /**
   * Método responsável por criar o Relatório
   * @return string
   */
  public static function setReceita($request)
  {
    /* DADOS DO POST */
    $postVars = $request->getPostVars();
    $inputs = $postVars['medicacao'];
    $obs = array($postVars['obs']);
    $date = array(date('d/M/y'));
    $time = array(date('H:m'));

    $arr = [];
    for ($i = 1; $i <= count($inputs); $i++) {
      array_push($arr, "medicacao$i");
    }

    $array_type1 = array_combine($arr, $inputs);
    $array_type2 = array_combine(array("observacao"), $obs);
    $array_type3 = array_combine(array("date"), $date);
    $array_type4 = array_combine(array("time"), $time);

    $TBS = new clsTinyButStrong;
    $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
    $template = 'resources/view/admin/modules/receitas/Receituario.docx';
    $nome = 'Receituario.docx';
    $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);
    $TBS->MergeBlock('blk1', $array_type1);
    $TBS->MergeBlock('blk2', $array_type2);
    $TBS->MergeBlock('blk3', $array_type3);
    $TBS->MergeBlock('blk4', $array_type4);

    $TBS->PlugIn(OPENTBS_DELETE_COMMENTS);

    $save_as = (isset($_POST['save_as']) && (trim($_POST['save_as']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['save_as']) : '';
    $output_file_name = str_replace('.', '_' . date('Y-m-d') . $save_as . '.', $nome);
    if ($save_as === '') {
      $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name);
      exit();
    } else {
      $TBS->Show(OPENTBS_FILE, $output_file_name);
      exit("Arquivo [$output_file_name] já foi criado.");
    }
  }
}
