<?php 
function tpl_00000000_string_8797c9ea09566__BXjNb7ILJtLQd22UmvDQlA($tpl, $ctx) {
$_thistpl = $tpl ;
$_translator = $tpl->getTranslator() ;
$ctx->setXmlDeclaration('<?xml version="1.0" encoding="UTF-8" ?>',false) ;
?>

<?php $ctx->setDocType('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',false); ?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title><?php echo phptal_escape($ctx->index_main_nadpis); ?>
</title>
	<link rel="stylesheet" type="text/css" href="css/default.css"/>
	<link rel="stylesheet" type="text/css" href="css/highlight.css"/>
</head>
<body>
<div id="debuguj"><?php 
if (NULL !== ($_tmp_1 = ($ctx->debuguj))):  ;
$_tmp_1 = ' href="'.phptal_escape($_tmp_1).'"' ;
else:  ;
$_tmp_1 = '' ;
endif ;
?>
<a<?php echo $_tmp_1 ?>>DEBUGUJ!</a></div>

<h1 id="index_main_nadpis"><?php echo phptal_escape($ctx->index_main_nadpis); ?>
</h1>

<h2 id="menu">Menu</h2>
<ul>
	<li><a href="test.php">Index</a></li>
	<li><a href="test.php?cesta=Stranka_AkceM">Akce</a></li>
	<li><a href="test.php?cesta=Stranka_Ucastnici">Účastníci</a></li>
	<li><a href="test.php?cesta=Stranka_TypyAkce">Typy akce</a></li>
	<li><a href="test.php?cesta=Stranka_Predpoklady">Předpoklady</a></li>
</ul>

<div id="cesta" class="cesta"><span>Soubor šablony: </span> <span><?php echo phptal_escape($ctx->path($ctx->index_main_kontext, 'souborSablony')); ?>
</span> <span> |
metoda: </span> <span><?php echo phptal_escape($ctx->path($ctx->index_main_kontext, 'metoda')); ?>
</span> <span>
| třída potomka: </span> <span><?php echo phptal_escape($ctx->path($ctx->index_main_kontext, 'tridaPotomka')); ?>
</span>
<span> | metoda potomka: </span> <span><?php echo phptal_escape($ctx->path($ctx->index_main_kontext, 'metodaPotomka')); ?>
</span> <span> |
volá se: </span> <span><?php echo phptal_escape($ctx->path($ctx->index_main_kontext, 'volaSe')); ?>
</span></div>

<div class="seznam">
<h4>"ucastnik.detail.xhtml</h4>
<h3><?php echo phptal_escape($ctx->path($ctx->Stranka_TypyAkce, 'nadpis')); ?>
</h3>

<?php 
$_tmp_1 = $ctx->repeat ;
$_tmp_1->tlacitko = new PHPTAL_RepeatController($ctx->path($ctx->Stranka_TypyAkce, 'tlacitka'))
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_1->tlacitko as $ctx->tlacitko): ;
?>
<span>
			<?php 
if (NULL !== ($_tmp_2 = ($ctx->path($ctx->tlacitko, 'odkaz')))):  ;
$_tmp_2 = ' href="'.phptal_escape($_tmp_2).'"' ;
else:  ;
$_tmp_2 = '' ;
endif ;
?>
<a<?php echo $_tmp_2 ?> class="tlacitko"><?php echo phptal_escape($ctx->path($ctx->tlacitko, 'popisek')); ?>
</a>
</span><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>




<p><?php 
$ctx->noThrow(true) ;
if (!phptal_isempty($_tmp_2 = $ctx->path($ctx->Stranka_TypyAkce, 'zprava', true))):  ;
?>
<?php 
echo phptal_escape($_tmp_2) ;
endif ;
$ctx->noThrow(false) ;
?>
</p>

<?php 
$ctx->noThrow(true) ;
if (!phptal_isempty($ctx->path($ctx->Stranka_TypyAkce, 'seznam', true))):  ;
if (false):  ;
endif ;
$ctx->noThrow(false) ;
?>
<table>
        <tr>
		<?php 
$_tmp_1 = $ctx->repeat ;
$_tmp_1->sloupec = new PHPTAL_RepeatController($ctx->path($ctx->Stranka_TypyAkce, 'hlavickaTabulky/sloupce'))
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_1->sloupec as $ctx->sloupec): ;
?>
<th>
                    <?php if ($ctx->path($ctx->sloupec, 'sestupne')): ; ?>
<span>
                        <?php 
if (NULL !== ($_tmp_2 = ($ctx->path($ctx->sloupec, 'sestupne')))):  ;
$_tmp_2 = ' href="'.phptal_escape($_tmp_2).'"' ;
else:  ;
$_tmp_2 = '' ;
endif ;
?>
<a<?php echo $_tmp_2 ?>>
				<img src="img/desc.png" alt="Sestupně" title="Řadit sestupně"/>
			</a>
                    </span><?php endif; ?>

                    <span><?php echo phptal_escape($ctx->path($ctx->sloupec, 'popisek')); ?>
</span>
                    <?php if ($ctx->path($ctx->sloupec, 'sestupne')): ; ?>
<span>
			<?php 
if (NULL !== ($_tmp_2 = ($ctx->path($ctx->sloupec, 'vzestupne')))):  ;
$_tmp_2 = ' href="'.phptal_escape($_tmp_2).'"' ;
else:  ;
$_tmp_2 = '' ;
endif ;
?>
<a<?php echo $_tmp_2 ?>>
				<img src="img/asc.png" alt="Vzestupně" title="Řadit vzestupně"/>
			</a>
                    </span><?php endif; ?>

		</th><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>

	</tr>
	<?php 
$_tmp_2 = $ctx->repeat ;
$_tmp_2->radek = new PHPTAL_RepeatController($ctx->path($ctx->Stranka_TypyAkce, 'seznam'))
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_2->radek as $ctx->radek): ;
?>
<tr>
                <?php 
$_tmp_1 = $ctx->repeat ;
$_tmp_1->sloupecek = new PHPTAL_RepeatController($ctx->radek)
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_1->sloupecek as $ctx->sloupecek): ;
?>
<td>
                    <?php echo phptal_escape($ctx->sloupecek); ?>

                </td><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>

                <?php 
$_tmp_1 = $ctx->repeat ;
$_tmp_1->tlacitko = new PHPTAL_RepeatController($ctx->path($ctx->radek, 'tlacitka'))
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_1->tlacitko as $ctx->tlacitko): ;
?>
<td>
			<?php 
if (NULL !== ($_tmp_3 = ($ctx->path($ctx->tlacitko, 'odkaz')))):  ;
$_tmp_3 = ' href="'.phptal_escape($_tmp_3).'"' ;
else:  ;
$_tmp_3 = '' ;
endif ;
?>
<a<?php echo $_tmp_3 ?> class="tlacitko"><?php echo phptal_escape($ctx->path($ctx->tlacitko, 'popisek')); ?>
</a>
		</td><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>

	</tr><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>

</table><?php endif; ?>


<div class="detail">

<h3><?php echo phptal_escape($ctx->path($ctx->Stranka_TypAkce, 'nadpis')); ?>
</h3>

<?php 
$ctx->noThrow(true) ;
if (!phptal_isempty($ctx->path($ctx->Stranka_TypAkce, 'ulozeno', true))):  ;
if (false):  ;
endif ;
$ctx->noThrow(false) ;
?>
<p>Úspěšně uloženo!</p><?php endif; ?>

<?php 
$ctx->noThrow(true) ;
if (!phptal_isempty($ctx->path($ctx->Stranka_TypAkce, 'ulozeno_chyba', true))):  ;
if (false):  ;
endif ;
$ctx->noThrow(false) ;
?>
<p>Při ukládání došlo k chybě!</p><?php endif; ?>


<?php 
$_tmp_3 = $ctx->repeat ;
$_tmp_3->tlacitko = new PHPTAL_RepeatController($ctx->path($ctx->Stranka_TypAkce, 'tlacitka'))
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_3->tlacitko as $ctx->tlacitko): ;
?>
<span>
			<?php 
if (NULL !== ($_tmp_1 = ($ctx->path($ctx->tlacitko, 'odkaz')))):  ;
$_tmp_1 = ' href="'.phptal_escape($_tmp_1).'"' ;
else:  ;
$_tmp_1 = '' ;
endif ;
?>
<a<?php echo $_tmp_1 ?> class="tlacitko"><?php echo phptal_escape($ctx->path($ctx->tlacitko, 'popisek')); ?>
</a>
</span><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>



<form action="/Proj_100930_vyvoj/test.php?cesta=Stranka_TypyAkce.main(!)~Stranka_TypAkce.detail(!'id!1'zmraz!1)" method="post" name="typakce" id="typakce">
<div>
<input name="typakce_id" type="hidden" value="1"/>
<table border="0">

	<tr>
		<td align="right" valign="top"><b>Název typu akce</b></td>
		<td valign="top" align="left">	Výběrová schůzka<input type="hidden" name="typakce_nazev" value="Výběrová schůzka"/></td>
	</tr>
	<tr>
		<td align="right" valign="top"><b>Trvání akce (dny)</b></td>
		<td valign="top" align="left">	1<input type="hidden" name="typakce_trvaniDni" value="1"/></td>
	</tr>
	<tr>
		<td align="right" valign="top"><b>Hodiny/den</b></td>
		<td valign="top" align="left">	2<input type="hidden" name="typakce_hodinyDen" value="2"/></td>
	</tr>
	<tr>
		<td align="right" valign="top"><b>Minimální počet účastníků</b></td>
		<td valign="top" align="left">	1<input type="hidden" name="typakce_minPocetUc" value="1"/></td>
	</tr>
	<tr>
		<td align="right" valign="top"><b>Maximální počet účastníků</b></td>
		<td valign="top" align="left">	999<input type="hidden" name="typakce_maxPocetUc" value="999"/></td>
	</tr>
</table>
</div>
</form>

<!-- %NEXT%  -->

</div>

</div>


</body>
</html><?php 
/* end */ ;

}

?><?php /* 
*** DO NOT EDIT THIS FILE ***

Generated by PHPTAL from <string 8797c9ea09566f7bc93952fc538df02d> (edit that file instead) */; ?>