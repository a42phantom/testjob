<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<? //var_dump($arResult); ?>
<div class="table-responsive">
	<table class="table">
	<? $i = 0; $j = 0; ?>
	<? foreach ($arResult['SECTIONS'] as $arSection): ?>
	  <? if ($i < 1): ?>
			<thead>
				<tr>
						<th></th>
							<? foreach($arSection['ITEMS'] as $arItem): ?>
								<th><?=$arItem['PROPERTY_DATE_VALUE']?></th>
							<? endforeach;?>
				</tr>
			</thead>
	  <? endif;?>
	<? $i = $i+1; ?>
	<? endforeach;?>
	<tbody>
			<? foreach($arResult['SECTIONS'] as $arSection):?>
				<tr>
						<td><?=$arSection['NAME']?></td>
					  <? foreach($arSection['ITEMS'] as $arItem): ?>
							<td><?=$arItem['PROPERTY_VALUE_VALUE']?></td>
					  <? endforeach;?>
					
				</tr>
			<? endforeach; ?>
	</tbody>
	</table>
</div>
