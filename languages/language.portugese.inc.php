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
     'js_disable'      => 'Seu Javascript está atualmente desabilitado',
     '_bypass_autodel' => 'Ignorar a  auto deletação com este parâmetro',
	 'back_main' => 'Voltar para o inínio',
	 
	 'no_files' 	=> 'Nenhum arquivo',
	 'tabel_no_file' => 'Nenhum arquivo encontrado',
	 '_show' => 'Mostrar',
     '_downloaded' => 'Baixados',
     '_everything' => 'Tudo',	 
	 
     '_maxfilesize' => 'TamMáxArquivo',
     '_minfilesize' => 'TamMinArquivo',
     '_refresh' => 'atualizar',
     '_autodel' => 'Delete automático',
     '_pointboost' => 'PointBoost',
	 '_limitip' => 'Limit-Download',
     '_fakeext' => 'Falsa Extensão',
     '_fakeext_desc' => 'Renomear automaticamente extensões com',
     '_timework' => 'Tempo de funcionamento do RL',
     'wrong_proxy' => 'Endereço de proxy inserido está incorreto',
	 'action' => 'Selecione...',
     'worktime_alert'=> '&raquo; O RL não está em horário de funcionamento, por favor volte mais tarde',

     'use_premix' => 'Usar PremiX',
     'use_proxy' => 'Usar Configurações de Proxy',
     '_proxy' => 'Proxy:',
     '_uname' => 'Usário:',
     '_pass' => 'Senha:',

     'save_to' => 'Salvar em',
     'save_path' => 'Caminho:',
	 
     '_upto' => 'até',

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

     'unauthorized' => 'Você não está autorizado, Conexão Perdida..!',
     'banned' => 'Você está banido, desapareça agora..!',

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
     'maintenance'          => 'Em manutenção...!',
     'premix_used_1'         => 'Você já está usando seu',
     'premix_used_2'         => 'PremiX gratuito por',
     'premix_used_3'         => 'hora(s)!',
     'premix_used_4'         => 'arquivos gratuitos por dia!',
     'sorry_inc'           => 'Desculpe-nos por essa incoveniencia!',
     'quote_alert'=> '&raquo; Alerta da Quantidade de Limite de banda..!',
     'quote_status'=> 'Sorry, O status da Quantidade de banda é:',
     'maxstorage_alert_1'=> '&raquo; Limite de espaço máximo alcançado, delete alguns arquivos',
     'maxstorage_alert_2'=> ' ou espere a deletação automática liberar espaço.',
     'exceed_alert'=> '* excedido o limite máximo;',
     'expired_since'=> '* expirado desde ;',
	 'cpuload_sloadhigh'=> 'Server load too high, come back later;',
     'maxjob_limited_1'=> 'Server is limit download upto ',
     'maxjob_limited_2'=> ' tasks at a time.',	 
	 
     'link_transload'              => 'Arquivo para transferência',
     '_transload'              => 'Transferir Arquivo',
     'referrer'              => 'Referência',
     'add_comment'      => 'Adicionar Comentários',
     'user_stats'   => 'Status do usuário:',
     'limit_leech'   => 'Modo limitado de leech',
     'detect_ip'   => 'IP Detectado:',
     
	 'server_stats'   => 'Status do Servidor:',
	 'log_act'   => 'Log Activity:',
	 'lact_files'   => 'file(s)',
	 'lact_autodeleted'   => 'deleted by autodelete',	 
	 'current_storage'   => 'Espaço atual usado:',
	 'current_traffic'   => 'Banda atual:',
	 'reset_traffic_remain'   => 'Reset Traffic Remaining:',
	 'max_traffic'   => 'quantidade máxima de banda',
	 
     'send_email'  => 'Enviar arquivo por E-mail',
     'email'=> 'E-mail:',
     'split_file'    => 'Separar Arquivos',
     'method'=> 'Método:',
     'tot_com' => 'Comando Total',
     'rfc' => 'RFC 2046',
     'part_size' => 'Tamanho das partes:',
	 
	 
	 
     'save_sett' => 'Salvar configurações',
     'clear_sett' => 'Limpar configurações atuais',
	 
     'plugin_opt' => 'Opções do Plugin:',
     'plugin_disable' => 'Disabilitar todos plugins',
     'plugin_youtube' => 'Transferir Video do Youtube com Qualidade Máxima em formato Mp4 (H264)',
     'plugin_imageshack' => 'ImageShack&reg; - Serviço de Torrent',
     'plugin_megaupl' => 'Megaupload.com Cookie Valor',
	 'plugin_hotfile' => 'Hotfile.com Cookie Valor',
	 'plugin_rs' => 'Rapidshare.com Cookie Value',
     'plugin_buletin' => 'Usar plugin vBulletin',
	 
     '_user' => 'usuário=',
	 '_auth' => 'auth=',
	 '_enc' => 'enc=',
	 	 
     '_sfrefresh' => 'Atualizar',
     'chk_all' => 'Selecionar Todos',
     'chk_unchk' => 'Des-selecionar Todos',
     'chk_invert' => 'Inventer Seleção',
	 
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
     'tabel_cmt' => 'Comentários',
	 
	 
     'curl_notload_1' => 'Você precisa carregar/ativar a extensão cURL (http://www.php.net/cURL) ou configurar no',
     'curl_notload_2' => ' arquivo config.php.',
     'curl_enable' => 'cURL está habilitado',
	 
     'php_below_5' => 'Versão 5 do PHP é altamente recomendável, mas não é obrigatório',
     'php_server_safemode' => 'Cheque se o modo seguro (safe mode) está desligado, pois o script do RL pode não funcionar com o modo seguro ligado',
	 
     'php_server_safemode' => 'Cheque se o modo seguro (safe mode) está desligado, pois o script do RL pode não funcionar com o modo seguro ligado',
	 
     'work_with' => 'Funciona com',
     'link_only' => 'Mostrar apenas Links',
     'kill_link_only' => 'Apenas matar os links',
     'debud_mode' => 'Modo Debug',
     'debud_mode_notice' => 'Troque o modo debug para',
     'max_bound_chk_link_1' => 'Maxímo número',
     'max_bound_chk_link_2' => 'de links alcançado.',
     'check_in' => 'checado em',
	 
     'rs_acc_chk' => 'Checador de contas Rapidshare',
     'modded' => 'Modificado',
     'un_pass' => 'usuário:senha',
     'curl_stat' => 'modo cURL:',
     'curl_notice' => 'não pode usar esse checador sem que o cURL esteja LIGADO',
     '_on' => 'LIGADO',
     '_off' => 'DESLIGADO',
	 
	 
	 //=========================
	 //=index.php
	 
     'path_not_defined' => 'Caminho não foi especificado para salvar este arquivo',
     'size_not_true' => 'Tamanho da parte especificada inválido',
     'url_unknown' => 'Tipo de URL desconhecida',
     'url_only_use' => 'Usar apenas',
     'url_or' => 'ou',
	 
     'downloading' => 'Baixando',
     'prep_dl' => '...Preparando',
     'leeching' => 'Leechando..',
	 
     'back_main' => 'Voltar para o iníncio',
     '_error' => 'Erro!',
     '_redirect_to' => 'foi redirecionado para',
     '_redirecting_to' => 'Redirecionando para:',
     '_saved' => 'Salvado!',
     '_reload' => 'Recarregar',
     '_avg_spd' => 'Velocidade:',
	 
     'error_upd_list' => 'Não foi possível atualizar a lista de arquivos',
     'error_upd_trf_list' => 'Não foi possível atualizar a lista de banda',
	 
     'mail_file_sent' => 'Arquivo enviado para este endereço',
     'mail_error_send' => 'Erro ao estar enviando o arquivo!',
     'delete_link' => 'Link para deletar:',
     'delete_link_notice' => 'Use o link de deletar após você ter feito o download do arquivo<br>para que deixe espaço livre no disco para outros.',
	 'zzzzz' => ''
	 
     );
	 
	 $htxt = array(
  //http.php; it's load from index.php also
     '_pwait'       => 'Por favor espere',
     '_error_retrieve'       => 'Erro ao obter o link',
     '_error_redirectto'       => 'Erro! Você foi redirecionado para',
     '_error_resume'       => 'Falha ao continuar',
     '_error_noresume'       => 'O servidor não suporta a função de continuar',
     '_error_cantsave'       => 'não foi possível salvar no diretório',
     '_error_trychmod'       => 'Tente dar CHMODT 777 na pasta',
     '_error_tryagain'       => 'Tentar Denovo',
     '_error_imposible_record'       => 'It is not possible to carry out a record in the file',
     '_error_misc'       => 'URL inválida ou aconteceu algum erro desconhecido',
     '_con_proxy'       => 'Conectado ao proxy',
     '_con_to'       => 'Conectado a',
     '_sorry_tobig'       => 'Desculpe, seu arquivo é muito grande',
     '_sorry_tosmall'       => 'Desculpe, seu arquivo é muito pequeno',
     '_sorry_quotafull'       => 'Desculpe, insuficiente quantidade de banda',

	 'zzzzz' => ''	 
     );	 
 
 
 // Un-translated :: $optxt
 	 $optxt = array(
     'no_support_upl_serv'   => 'Serviço de envio não suportado!',
     'select_one_file'       => 'Por favor selecione algum arquivo primeiro',
     'del_disabled'       	=> 'Função de deletar desabilitada',
     '_file'       			=> 'Arquivo',
     '_host'       			=> 'Servidor',
     '_port'       			=> 'Porta',
     '_del'       => 'Deletar',
     'these_file'       => 'Estes Arquivos',
     'this_file'       => 'Este Arquivo',
     '_yes'       => 'Sim',
     '_no'       => 'Não',
     '_deleted'       => 'Deletado',
     'couldnt_upd_list'       => 'Couldn\'t update file list. Problem writing to file!',
     'error_delete'       => 'Erro ao deletar',
     'not_found'       => 'Não encontrado!',
     'error_upd_list'       => 'Erro ao atualizar a lista!',
     'couldnt_upd'       => 'Não foi possível atualizar',
     'del_success'       => 'Arquivo(s) deletado(s) com sucesso',
     'split_part'       => 'Separar por partes',
     '_method'       => 'Método',
     'part_size'       => 'Tamanho das partes',
     'invalid_email'       => 'Endereço de E-mail inválido.',
     '_and_del'       => 'e deletado.',
     '_not_del'       => 'não deletado!',
     '_but'       => 'mas',
     'send_for_addr'       => 'enviar isto para o endereço',
     'error_send'       => 'Erro no envio do arquivo!',
     'filetype'       => 'O tipo de arquivo',
     'forbidden_unzip'       => 'está proibido descomprimir',
     'unzip_success'       => 'descomprimido com sucesso',
     'saveto'       => 'Salvar em',
     'del_source_aft_split'       => 'Deletar arquivo original após juntar com sucesso as partes',
     'start_split'       => 'Iniciou a junção do arquivo',
     'of_part'       => 'de partes',
     'use_method'       => 'Usando o método',
     'tot_part'       => 'Total de partes',
     'crc_error'       => 'Não é possível juntar o arquivo. Erro de CRC',
     'crc_error_open'       => 'It is not possible to open source file',
     'split_error'       => 'Não foi possível juntar o arquivo',
	 'piece_exist'       => 'A piece already exists',
	 'crc_exist'       => 'CRC file already exists',
	 'src_notfound'       => 'Source file not found',
	 'dir_inexist'       => 'Directory doesn\'t exist',
	 'error_read_file'       => 'Error reading the file',
	 'error_open_file'       => 'Error opening file',
	 'error_write_file'       => 'Error writing the file',
     'split_error_source_not_del'       => 'Um erro ocorreu. Arquivo original não deletado!',
     'source_del'       => 'Arquivo original deletado.',
     'source_file_is'       => 'Arquivo original é',
     'error_upd_file_exist'       => 'Não foi possível atualizar. O arquivo já existe!',
     'select_crc_file_only'       => 'Selecione apenas o arquivo .crc!',
     'select_crc_file'       => 'Selecione o arquivo .crc!',
     'cant_read_crc_file'       => 'Não é possível ler o arquivo .crc!',
     'merge_file_not_found'       => 'Os arquivos necessários para juntar não encontrados!',
     'file_not_open'       => 'O arquivo não pode ser aberto para escrita!',
     'filesize_unmatch'       => 'Tamanho dos arquivos não são iguais!',
     'perform_crc'       => 'Você deseja checar a performance de um CRC?',
     'recommend'       => '(recomendado)',
 
     'select_action'       => 'Selecione uma ação',
     'add_zip'       => 'Adicionar arquivos para arquivo ZIP',
     'arcv_name'       => 'Nome do Arquivo',
     'no_compress'       => 'Não usar compreessão',
     'no_subdir'       => 'Não incluir diretórios',
     'add_file'       => 'Adicionar arquivos',
     '_arcv'       => 'Arquivo',
     '_arcv_name'       => 'Nome do arquivo',
     'success_created'       => 'criado com sucesso!',
     'compress_notice_1'       => 'Para usar compressão gz ou bz2, escreva na etensão Tar.gz ou Tar.bz2;!',
     'compress_notice_2'       => 'Caso o arquivo já esteja descomprimido com Tar',
     'enter_arc_name'       => 'Por favor insira um nome de arquivo!',
     'ready_exist'       => 'já existe!',
     '_error'       => 'Erro!',
     'arcv_not_created'       => 'Arquivo não criado.',
     'error_occur'       => 'Um erro ocorreu!',
     'was_pack'       => 'foi empacotado',
     'pack_in_arcv'       => 'Empacotado em arquivo',
     'arcv_empty'       => 'O arquivo está vazio.',
     'del_source_aft_upl'       => 'Deletar arquivo original após o envio estar completo',
     'add_extension'       => 'Adicionar extenção',
     '_without'       => 'sem',
     'rename_to'       => 'renomeado para',
     'couldnt_rename_to'       => 'Não foi possível trocar o nome do arquivo',
     'new_name'       => 'Novo nome',
     'no_permision_rename'       => 'Você não tem permissão para trocar o nome de arquivos',
     'success_merge_untes'       => 'mesclado com sucesso, mas não testado!',
     'success_merge'       => 'mesclado com sucesso!',
     'crc32_unmatch'       => 'CRC32 checksum não é igual!',
     'you_selected'       => 'Você selecionou',
     'you_sure_ch_md5'       => 'Você tem certeza que quer trocar o MD5 deste(s) arquivo(s)?',
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
     '_opt' 	=> 'Opções',
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
	 '_user' => 'usuário=',
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
     '_rsure'       => 'Você tem certeza que deseja',
     '_todelete'       => 'deleletar este arquivo',
     '_sucesdelete'       => 'deletado com sucesso!',
     '_thx'       => 'Obrigado.',
     '_inexist'       => 'Arquivo inexistente',

	 'zzzzz' => ''
	 
     );
	 break;  //end case del.php
}

?>