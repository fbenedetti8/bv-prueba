<? 
$style = "";
if (is_array($attributes)):
	foreach ($attributes as $key=>$val)
		$style = "$key:$val; ";
endif;

$value = utf8_decode($value);
?>
<td style="padding:2px; vertical-align:middle; text-align:center;">
	<? if( file_exists('.'.$value) ){?>
		<a href="<?=site_url($value);?>" class="fancybox">
			<img src="<?=base_url();?>media/admin/resizer/resizer.php?src=<?=base_url();?><?=$value;?>&w=<?=$width;?>" style="width: <?=$width;?>px; border: 1px solid #ccc; margin: 5%; <?=$style;?>"/>
		</a>
	<? } else { ?>
		<a href="/media/admin/assets/img/no-image.png" class="fancybox">
			<img src="/media/admin/assets/img/no-image.png" style="width: <?=$width;?>px; border: 1px solid #ccc; margin: 5%; <?=$style;?>"/>
		</a>
	<? } ?>
</td>