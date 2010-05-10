<?php

  /*****************************************************
  ** Title........: Rapidleech PlugMod rev. 36B by eqbal Lang Pack
  ** Author.......: Credits to Pramode & Checkmate & Kloon. Mod by: MsNeil & Idoenk
  ** Filename.....: languages.pt.inc.php
  ** Language.....: Brazilian Portuguese
  ** Lang:Mod.....: supremo900
  ** Version......: 0.1
  ** Notes........: *
  ** Updated......: 100307 - YYMMDD
  *****************************************************/
  // Set Charset of this language  
  $charSet = 'charset=ISO-8859-1';
  
  $scrname = substr(basename($_SERVER["PHP_SELF"]), 0, -strlen(strrchr(basename($_SERVER["PHP_SELF"]), ".")));
  $vpage = (!isset($vpage) ? $scrname : $vpage);


  $gtxt = array(
  // general page; commonly load on every page
     'js_disable'      => 'Seu Javascript est� atualmente desabilitado',
     '_bypass_autodel' => 'Ignorar a  auto deleta��o com este par�metro',
	 'back_main' => 'Voltar para o in�nio',
	 
	 'no_files' 	=> 'Nenhum arquivo',
	 'tabel_no_file' => 'Nenhum arquivo encontrado',
	 '_show' => 'Mostrar',
     '_downloaded' => 'Baixados',
     '_everything' => 'Tudo',	 
	 
     '_maxfilesize' => 'TamM�xArquivo',
     '_minfilesize' => 'TamMinArquivo',
     '_refresh' => 'atualizar',
     '_autodel' => 'Delete autom�tico',
     '_pointboost' => 'PointBoost',
	 '_limitip' => 'Limit-Download',
     '_fakeext' => 'Falsa Extens�o',
     '_fakeext_desc' => 'Renomear automaticamente extens�es com',
     '_timework' => 'Tempo de funcionamento do RL',
     'wrong_proxy' => 'Endere�o de proxy inserido est� incorreto',
	 'action' => 'Selecione...',
     'worktime_alert'=> '&raquo; O RL n�o est� em hor�rio de funcionamento, por favor volte mais tarde',

     'use_premix' => 'Usar PremiX',
     'use_proxy' => 'Usar Configura��es de Proxy',
     '_proxy' => 'Proxy:',
     '_uname' => 'Us�rio:',
     '_pass' => 'Senha:',

     'save_to' => 'Salvar em',
     'save_path' => 'Caminho:',
	 
     '_upto' => 'at�',

     'tabel_sz' => 'Tamanho',
	 'tabel_ip' => 'IP Leeched',
     'tabel_dt' => 'Data',
     'tabel_age' => 'File Age',
     'act_del' => 'Deletar',

     '_second' => 'segundos.',
	 
     '_uploading' => 'Enviando Arquivo',
	 
	 'close' => 'Close',
	 
     'chk_txt_matches' => 'Checado Igual',
     'go_match' => 'Checado',	 
     'match_csensitive' => 'Case Sensitive',
     'match_hideunmatch' => 'Hide UnMatch',
	 
	 'days' => 'day(s)',
	 'hours' => 'hour(s)',
	 'minutes' => 'minute(s)',
	 'seconds' => 'second(s)',	 
	 'ago' => 'ago',
	 'less_a_minute' => 'less than a minute',

     'unauthorized' => 'Voc� n�o est� autorizado, Conex�o Perdida..!',
     'banned' => 'Voc� est� banido, desapare�a agora..!',

     'unauthorized_c' => 'Your country is not authorized, Get Lost..!',
     'banned_c' => 'Your country is banned, disappear now..!',
	 
	 );



/*  ====================================================
*/

switch($vpage)
{
	case "index":
	 $txt = array(
  //main.php it's load from index.php also
     'cpanel'       => 'Painel de Controle',
     'maintenance'          => 'Em manuten��o...!',
     'premix_used_1'         => 'Voc� j� est� usando seu',
     'premix_used_2'         => 'PremiX gratuito por',
     'premix_used_3'         => 'hora(s)!',
     'premix_used_4'         => 'arquivos gratuitos por dia!',
     'sorry_inc'           => 'Desculpe-nos por essa incoveniencia!',
     'quote_alert'=> '&raquo; Alerta da Quantidade de Limite de banda..!',
     'quote_status'=> 'Sorry, O status da Quantidade de banda �:',
     'maxstorage_alert_1'=> '&raquo; Limite de espa�o m�ximo alcan�ado, delete alguns arquivos',
     'maxstorage_alert_2'=> ' ou espere a deleta��o autom�tica liberar espa�o.',
     'exceed_alert'=> '* excedido o limite m�ximo;',
     'expired_since'=> '* expirado desde ;',
	 'cpuload_sloadhigh'=> 'Server load too high, come back later;',
     'maxjob_limited_1'=> 'Server is limit download upto ',
     'maxjob_limited_2'=> ' tasks at a time.',	 
	 
     'link_transload'              => 'Arquivo para transfer�ncia',
     '_transload'              => 'Transferir Arquivo',
     'referrer'              => 'Refer�ncia',
     'add_comment'      => 'Adicionar Coment�rios',
     'user_stats'   => 'Status do usu�rio:',
     'limit_leech'   => 'Modo limitado de leech',
     'detect_ip'   => 'IP Detectado:',
     
	 'server_stats'   => 'Status do Servidor:',
	 'log_act'   => 'Log Activity:',
	 'lact_files'   => 'file(s)',
	 'lact_autodeleted'   => 'deleted by autodelete',	 
	 'current_storage'   => 'Espa�o atual usado:',
	 'current_traffic'   => 'Banda atual:',
	 'reset_traffic_remain'   => 'Reset Traffic Remaining:',
	 'max_traffic'   => 'quantidade m�xima de banda',
	 
     'send_email'  => 'Enviar arquivo por E-mail',
     'email'=> 'E-mail:',
     'split_file'    => 'Separar Arquivos',
     'method'=> 'M�todo:',
     'tot_com' => 'Comando Total',
     'rfc' => 'RFC 2046',
     'part_size' => 'Tamanho das partes:',
	 
	 
	 
     'save_sett' => 'Salvar configura��es',
     'clear_sett' => 'Limpar configura��es atuais',
	 
     'plugin_opt' => 'Op��es do Plugin:',
     'plugin_disable' => 'Disabilitar todos plugins',
     'plugin_youtube' => 'Transferir Video do Youtube com Qualidade M�xima em formato Mp4 (H264)',
     'plugin_imageshack' => 'ImageShack&reg; - Servi�o de Torrent',
     'plugin_megaupl' => 'Megaupload.com Cookie Valor',
	 'plugin_hotfile' => 'Hotfile.com Cookie Valor',
	 'plugin_rs' => 'Rapidshare.com Cookie Value',
     'plugin_buletin' => 'Usar plugin vBulletin',
	 
     '_user' => 'usu�rio=',
	 '_auth' => 'auth=',
	 '_enc' => 'enc=',
	 	 
     '_sfrefresh' => 'Atualizar',
     'chk_all' => 'Selecionar Todos',
     'chk_unchk' => 'Des-selecionar Todos',
     'chk_invert' => 'Inventer Sele��o',
	 
     'act_upload' => 'Enviar',
     'act_ftp' => 'Arquivo em FTP',
     'act_mail' => 'E-Mail',
     'act_boxes' => 'Mass Submits',
     'act_split' => 'Separar Arquivos',
     'act_merge' => 'Juntar Arquivos',
     'act_md5' => 'MD5 Hash',
     'act_pack' => 'Empacotar Arquivos',
     'act_zip' => 'ZIPar Arquivos',
     'act_unzip' => 'Extractar Arquivos (beta)',
     'act_rename' => 'Renomear',
     'act_mrename' => 'Renomear em massa',
	 'act_delete' => 'Delete',
	 
     'tabel_name' => 'Nome',
     'tabel_dl' => 'Link de Download',
     'tabel_cmt' => 'Coment�rios',
	 
	 
     'curl_notload_1' => 'Voc� precisa carregar/ativar a extens�o cURL (http://www.php.net/cURL) ou configurar no',
     'curl_notload_2' => ' arquivo config.php.',
     'curl_enable' => 'cURL est� habilitado',
	 
     'php_below_5' => 'Vers�o 5 do PHP � altamente recomend�vel, mas n�o � obrigat�rio',
     'php_server_safemode' => 'Cheque se o modo seguro (safe mode) est� desligado, pois o script do RL pode n�o funcionar com o modo seguro ligado',
	 
     'php_server_safemode' => 'Cheque se o modo seguro (safe mode) est� desligado, pois o script do RL pode n�o funcionar com o modo seguro ligado',
	 
     'work_with' => 'Funciona com',
     'link_only' => 'Mostrar apenas Links',
     'kill_link_only' => 'Apenas matar os links',
     'debud_mode' => 'Modo Debug',
     'debud_mode_notice' => 'Troque o modo debug para',
     'max_bound_chk_link_1' => 'Max�mo n�mero',
     'max_bound_chk_link_2' => 'de links alcan�ado.',
     'check_in' => 'checado em',
	 
     'rs_acc_chk' => 'Checador de contas Rapidshare',
     'modded' => 'Modificado',
     'un_pass' => 'usu�rio:senha',
     'curl_stat' => 'modo cURL:',
     'curl_notice' => 'n�o pode usar esse checador sem que o cURL esteja LIGADO',
     '_on' => 'LIGADO',
     '_off' => 'DESLIGADO',
	 
	 
	 //=========================
	 //=index.php
	 
     'path_not_defined' => 'Caminho n�o foi especificado para salvar este arquivo',
     'size_not_true' => 'Tamanho da parte especificada inv�lido',
     'url_unknown' => 'Tipo de URL desconhecida',
     'url_only_use' => 'Usar apenas',
     'url_or' => 'ou',
	 
     'downloading' => 'Baixando',
     'prep_dl' => '...Preparando',
     'leeching' => 'Leechando..',
	 
     'back_main' => 'Voltar para o in�ncio',
     '_error' => 'Erro!',
     '_redirect_to' => 'foi redirecionado para',
     '_redirecting_to' => 'Redirecionando para:',
     '_saved' => 'Salvado!',
     '_reload' => 'Recarregar',
     '_avg_spd' => 'Velocidade:',
	 
     'error_upd_list' => 'N�o foi poss�vel atualizar a lista de arquivos',
     'error_upd_trf_list' => 'N�o foi poss�vel atualizar a lista de banda',
	 
     'mail_file_sent' => 'Arquivo enviado para este endere�o',
     'mail_error_send' => 'Erro ao estar enviando o arquivo!',
     'delete_link' => 'Arquivo Salvado:',
     'delete_link' => 'Link para deletar:',
     'delete_link_notice' => 'Use o link de deletar ap�s voc� ter feito o download do arquivo<br>para que deixe espa�o livre no disco para outros.',
	 'zzzzz' => ''
	 
     );
	 
	 $htxt = array(
  //http.php; it's load from index.php also
     '_pwait'       => 'Por favor espere',
     '_error_retrieve'       => 'Erro ao obter o link',
     '_error_redirectto'       => 'Erro! Voc� foi redirecionado para',
     '_error_resume'       => 'Falha ao continuar',
     '_error_noresume'       => 'O servidor n�o suporta a fun��o de continuar',
     '_error_cantsave'       => 'n�o foi poss�vel salvar no diret�rio',
     '_error_trychmod'       => 'Tente dar CHMODT 777 na pasta',
     '_error_tryagain'       => 'Tentar Denovo',
     '_error_imposible_record'       => 'It is not possible to carry out a record in the file',
     '_error_misc'       => 'URL inv�lida ou aconteceu algum erro desconhecido',
     '_con_proxy'       => 'Conectado ao proxy',
     '_con_to'       => 'Conectado a',
     '_sorry_tobig'       => 'Desculpe, seu arquivo � muito grande',
     '_sorry_tosmall'       => 'Desculpe, seu arquivo � muito pequeno',
     '_sorry_quotafull'       => 'Desculpe, insuficiente quantidade de banda',
     '_sorry_insuficient_storage' => 'Desculpe, insuficiente alcan�ado espa�o',

	 'zzzzz' => ''	 
     );	 
 
 
 // Un-translated :: $optxt
 	 $optxt = array(
     'no_support_upl_serv'   => 'Servi�o de envio n�o suportado!',
     'select_one_file'       => 'Por favor selecione algum arquivo primeiro',
     'del_disabled'       	=> 'Fun��o de deletar desabilitada',
     '_file'       			=> 'Arquivo',
     '_host'       			=> 'Servidor',
     '_port'       			=> 'Porta',
     '_del'       => 'Deletar',
     'these_file'       => 'Estes Arquivos',
     'this_file'       => 'Este Arquivo',
     '_yes'       => 'Sim',
     '_no'       => 'N�o',
     '_deleted'       => 'Deletado',
     'couldnt_upd_list'       => 'Couldn\'t update file list. Problem writing to file!',
     'error_delete'       => 'Erro ao deletar',
     'not_found'       => 'N�o encontrado!',
     'error_upd_list'       => 'Erro ao atualizar a lista!',
     'couldnt_upd'       => 'N�o foi poss�vel atualizar',
     'del_success'       => 'Arquivo(s) deletado(s) com sucesso',
     'split_part'       => 'Separar por partes',
     '_method'       => 'M�todo',
     'part_size'       => 'Tamanho das partes',
     'invalid_email'       => 'Endere�o de E-mail inv�lido.',
     '_and_del'       => 'e deletado.',
     '_not_del'       => 'n�o deletado!',
     '_but'       => 'mas',
     'send_for_addr'       => 'enviar isto para o endere�o',
     'error_send'       => 'Erro no envio do arquivo!',
     'filetype'       => 'O tipo de arquivo',
     'forbidden_unzip'       => 'est� proibido descomprimir',
     'unzip_success'       => 'descomprimido com sucesso',
     'saveto'       => 'Salvar em',
     'del_source_aft_split'       => 'Deletar arquivo original ap�s juntar com sucesso as partes',
     'start_split'       => 'Iniciou a jun��o do arquivo',
     'of_part'       => 'de partes',
     'use_method'       => 'Usando o m�todo',
     'tot_part'       => 'Total de partes',
     'crc_error'       => 'N�o � poss�vel juntar o arquivo. Erro de CRC',
     'crc_error_open'       => 'It is not possible to open source file',
     'split_error'       => 'N�o foi poss�vel juntar o arquivo',
	 'piece_exist'       => 'A piece already exists',
	 'crc_exist'       => 'CRC file already exists',
	 'src_notfound'       => 'Source file not found',
	 'dir_inexist'       => 'Directory doesn\'t exist',
	 'error_read_file'       => 'Error reading the file',
	 'error_open_file'       => 'Error opening file',
	 'error_write_file'       => 'Error writing the file',
     'split_error_source_not_del'       => 'Um erro ocorreu. Arquivo original n�o deletado!',
     'source_del'       => 'Arquivo original deletado.',
     'source_file_is'       => 'Arquivo original �',
     'error_upd_file_exist'       => 'N�o foi poss�vel atualizar. O arquivo j� existe!',
     'select_crc_file_only'       => 'Selecione apenas o arquivo .crc!',
     'select_crc_file'       => 'Selecione o arquivo .crc!',
     'cant_read_crc_file'       => 'N�o � poss�vel ler o arquivo .crc!',
     'merge_file_not_found'       => 'Os arquivos necess�rios para juntar n�o encontrados!',
     'file_not_open'       => 'O arquivo n�o pode ser aberto para escrita!',
     'filesize_unmatch'       => 'Tamanho dos arquivos n�o s�o iguais!',
     'perform_crc'       => 'Voc� deseja checar a performance de um CRC?',
     'recommend'       => '(recomendado)',
 
     'select_action'       => 'Selecione uma a��o',
     'add_zip'       => 'Adicionar arquivos para arquivo ZIP',
     'arcv_name'       => 'Nome do Arquivo',
     'no_compress'       => 'N�o usar compreess�o',
     'no_subdir'       => 'N�o incluir diret�rios',
     'add_file'       => 'Adicionar arquivos',
     '_arcv'       => 'Arquivo',
     '_arcv_name'       => 'Nome do arquivo',
     'success_created'       => 'criado com sucesso!',
     'compress_notice_1'       => 'Para usar compress�o gz ou bz2, escreva na etens�o Tar.gz ou Tar.bz2;!',
     'compress_notice_2'       => 'Caso o arquivo j� esteja descomprimido com Tar',
     'enter_arc_name'       => 'Por favor insira um nome de arquivo!',
     'ready_exist'       => 'j� existe!',
     '_error'       => 'Erro!',
     'arcv_not_created'       => 'Arquivo n�o criado.',
     'error_occur'       => 'Um erro ocorreu!',
     'was_pack'       => 'foi empacotado',
     'pack_in_arcv'       => 'Empacotado em arquivo',
     'arcv_empty'       => 'O arquivo est� vazio.',
     'del_source_aft_upl'       => 'Deletar arquivo original ap�s o envio estar completo',
     'add_extension'       => 'Adicionar exten��o',
     '_without'       => 'sem',
     'rename_to'       => 'renomeado para',
     'couldnt_rename_to'       => 'N�o foi poss�vel trocar o nome do arquivo',
     'new_name'       => 'Novo nome',
     'no_permision_rename'       => 'Voc� n�o tem permiss�o para trocar o nome de arquivos',
     'success_merge_untes'       => 'mesclado com sucesso, mas n�o testado!',
     'success_merge'       => 'mesclado com sucesso!',
     'crc32_unmatch'       => 'CRC32 checksum n�o � igual!',
     'you_selected'       => 'Voc� selecionou',
     'you_sure_ch_md5'       => 'Voc� tem certeza que quer trocar o MD5 deste(s) arquivo(s)?',
     'cur_md5'       => 'MD5 atual',
     'new_md5'       => 'Novo MD5',
     'change_md5'       => 'Trocar&nbsp;MD5',

/*
<?echo $optxt['crc32_unmatch'];?>
*/
	 'zzzzz' => ''	 
     );

 
	 break; // end case index
	 
		 
	case "audl":
	$atxt = array(
  //audl.php
     'not_link'       => 'Not LINK',
     '_link' 	=> 'Link',
     '_links' 	=> 'Links',
     '_opt' 	=> 'Op��es',
     '_status' 	=> 'Status',
     '_download' 	=> 'Baixar',
     '_done' 	=> 'PRONTO',
     '_waiting' 	=> 'Esperando...',
     '_started' 	=> 'Iniciado..',
     'audl_start' 	=> 'Iniciar o baixamento automatico',
     'add_link' 	=> 'Adicionar links',
     'acc_imgshack' 	=> 'Usar conta do Imageshack',
     'error_interval' 	=> 'Erros no intervalo de delay (de 1 a 3600 segundos)',
	 'plugin_megaupl' => 'Cookie Megaupload.com',
	 'plugin_hotfile' => 'Cookie Hotfile.com',
	 'plugin_rs' => 'Cookie Rapidshare.com',
	 '_user' => 'usu�rio=',
     '_auth' => 'auth=',
     '_enc' => 'enc=',	 
	 'reach_lim_audl' => 'Sorry you can not proceed more than %link% Links at once.',
	 'auto_check_link' => 'Auto Check Links',


	 'zzzzz' => ''
	 
     );
	break; // end case audl
	 
	
	case "lynx":
	$ltxt = array(
  //lynx.php
     '_fname'       => 'Nome do Arquivo',
     '_b64_desc' 	=> 'Link de download Base64',
     '_term' 	=> '+Termo',
     '_b64link' 	=> 'B64Link',
     '_deletelink' 	=> 'Deletar link',
     '_genlink' 	=> 'Gerar Link',

	 'zzzzz' => ''
     );
	 break;  //end case lynx
	 
	case "del": 	 
  $dtxt = array(
  //del.php; 
     '_rsure'       => 'Voc� tem certeza que deseja',
     '_todelete'       => 'deleletar este arquivo',
     '_sucesdelete'       => 'deletado com sucesso!',
     '_thx'       => 'Obrigado.',
     '_inexist'       => 'Arquivo inexistente',

	 'zzzzz' => ''
	 
     );
	 break;  //end case del.php
}

?>