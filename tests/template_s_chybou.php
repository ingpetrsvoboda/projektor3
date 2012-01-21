<?php 
function tpl_00000000_string_0d01f2fa04283__Bl6z8TNFZZqisH5nGA8lrQ($tpl, $ctx) {
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
	<li><a href="test.php?cesta=Stranka_Ucastnici">Úcastníci</a></li>
	<li><a href="test.php?cesta=Stranka_TypyAkce">Typy akce</a></li>
	<li><a href="test.php?cesta=Stranka_Predpoklady">Predpoklady</a></li>
</ul>

<div id="cesta" class="cesta"><span>Soubor šablony: </span> <span><?php echo phptal_escape($ctx->path($ctx->index_main_kontext, 'souborSablony')); ?>
</span> <span> |
metoda: </span> <span><?php echo phptal_escape($ctx->path($ctx->index_main_kontext, 'metoda')); ?>
</span> <span>
| trída potomka: </span> <span><?php echo phptal_escape($ctx->path($ctx->index_main_kontext, 'tridaPotomka')); ?>
</span>
<span> | metoda potomka: </span> <span><?php echo phptal_escape($ctx->path($ctx->index_main_kontext, 'metodaPotomka')); ?>
</span> <span> |
volá se: </span> <span><?php echo phptal_escape($ctx->path($ctx->index_main_kontext, 'volaSe')); ?>
</span></div>

<div class="seznam">
<h4>"ucastnik.detail.xhtml</h4>
<h3><?php echo phptal_escape($ctx->path($ctx->Stranka_Ucastnici, 'nadpis')); ?>
</h3>

<?php 
$_tmp_1 = $ctx->repeat ;
$_tmp_1->tlacitko = new PHPTAL_RepeatController($ctx->path($ctx->Stranka_Ucastnici, 'tlacitka'))
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
if (!phptal_isempty($_tmp_2 = $ctx->path($ctx->Stranka_Ucastnici, 'zprava', true))):  ;
?>
<?php 
echo phptal_escape($_tmp_2) ;
endif ;
$ctx->noThrow(false) ;
?>
</p>

<?php 
$ctx->noThrow(true) ;
if (!phptal_isempty($ctx->path($ctx->Stranka_Ucastnici, 'seznam', true))):  ;
if (false):  ;
endif ;
$ctx->noThrow(false) ;
?>
<table>
        <tr>
		<?php 
$_tmp_1 = $ctx->repeat ;
$_tmp_1->sloupec = new PHPTAL_RepeatController($ctx->path($ctx->Stranka_Ucastnici, 'hlavickaTabulky/sloupce'))
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
				<img src="img/desc.png" alt="Sestupne" title="Radit sestupne"/>
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
				<img src="img/asc.png" alt="Vzestupne" title="Radit vzestupne"/>
			</a>
                    </span><?php endif; ?>

		</th><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>

	</tr>
	<?php 
$_tmp_2 = $ctx->repeat ;
$_tmp_2->radek = new PHPTAL_RepeatController($ctx->path($ctx->Stranka_Ucastnici, 'seznam'))
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


<div id="ucastnik" class="detail">
    <h4>"ucastnik.detail.xhtml</h4>
<?php 
if (NULL !== ($_tmp_3 = ($ctx->ucastnik_detail_zpet))):  ;
$_tmp_3 = ' href="'.phptal_escape($_tmp_3).'"' ;
else:  ;
$_tmp_3 = '' ;
endif ;
?>
<a<?php echo $_tmp_3 ?>>Zpet</a>
<h3><?php echo phptal_escape($ctx->ucastnik_detail_nadpis); ?>
</h3>
<span><?php 
$ctx->noThrow(true) ;
if (!phptal_isempty($_tmp_1 = $ctx->ucastnik_prihlaseni)):  ;
?>
<?php 
echo phptal_escape($_tmp_1) ;
endif ;
$ctx->noThrow(false) ;
?>
</span>
<ul>
	<?php 
$_tmp_2 = $ctx->repeat ;
$_tmp_2->ucastnik_detail_obsah/polozka = new PHPTAL_RepeatController(_NOTHING_NOTHING_NOTHING_NOTHING_)
 ;
$ctx = $tpl->pushContext() ;
foreach ($_tmp_2->ucastnik_detail_obsah/polozka as $ctx->ucastnik_detail_obsah/polozka): ;
?>
<li></li><?php 
endforeach ;
$ctx = $tpl->popContext() ;
?>


	<?php if ($ctx->ucastnik_detail_potomek_neni): ; ?>
<span>
	<li><?php echo phptal_escape($ctx->path($ctx->ucastnik_detail_obsah, 'ucastnik_id')); ?>
</li>
	</span><?php endif; ?>

	<?php if ($ctx->ucastnik_detail_potomek_neni): ; ?>
<span>
	<li>Registracní dotazník úcastníka 
	<?php 
$ctx->noThrow(true) ;
if (!phptal_isempty($_tmp_3 = ($ctx->path($ctx->ucastnik_detail_obsah, 'odkaz', true)))):  ;
$_tmp_3 = ' href="'.phptal_escape($_tmp_3).'"' ;
else:  ;
$_tmp_3 = '' ;
endif ;
$ctx->noThrow(false) ;
?>
<a<?php echo $_tmp_3 ?>><?php echo phptal_escape($ctx->path($ctx->ucastnik_detail_obsah, 'identifikator')); ?>
</a>
	</li>
	</span><?php endif; ?>

</ul>

<div id="cesta" class="cesta"><span>Soubor šablony: </span> <span><?php echo phptal_escape($ctx->path($ctx->ucastnik_detail_kontext, 'souborSablony')); ?>
</span> <span>
| metoda: </span> <span><?php echo phptal_escape($ctx->path($ctx->ucastnik_detail_kontext, 'metoda')); ?>
</span> <span>
| trída potomka: </span> <span><?php echo phptal_escape($ctx->path($ctx->ucastnik_detail_kontext, 'tridaPotomka')); ?>
</span> <span>
| metoda potomka: </span> <span><?php echo phptal_escape($ctx->path($ctx->ucastnik_detail_kontext, 'metodaPotomka')); ?>
</span> <span>
| volá se: </span> <span><?php echo phptal_escape($ctx->path($ctx->ucastnik_detail_kontext, 'volaSe')); ?>
</span></div>

<!-- %NEXT%  --></div>

</div>


</body>
</html><?php 
/* end */ ;

}

?><?php /* 
*** DO NOT EDIT THIS FILE ***

Generated by PHPTAL from <string 0d01f2fa04283f818e387f87fb3d848a> (edit that file instead) */; ?>