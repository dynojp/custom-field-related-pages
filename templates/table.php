<?php

/**
 * meta box のフィールド一覧テーブル
 *
 * 変数:
 * - $related_pages (array)
 */

// テーブルヘッダー:
?>
<div id="custom-field-related-pages">
<table class="table" width="100%">
  <thead>
    <tr>
      <th width="40%">ラベル</th>
      <th width="40%">URL</th>
      <th width="20%"></th>
    </tr>
  </thead>
  <tbody>
<?php
// テーブルボディ:
// 保存された値がある場合
if ( ! empty($related_pages) ):
  foreach ($related_pages as $field):
?>
    <tr>
      <td><input type="text" name="label[]" value="<?php echo esc_attr( $field['label'] ); ?>" /></td>
      <td><input type="text" name="url[]" value="<?php echo esc_attr( $field['url'] ); ?>" /></td>
      <td><a class="button remove-row" href="#">削除</a></td>
    </tr>
<?php 
  endforeach;
else:
// 保存された値が無い場合
?>
    <tr>
      <td><input type="text" name="label[]" /></td>
      <td><input type="text" name="url[]" /></td>
      <td><a class="button remove-row" href="#">削除</a></td>
    </tr>
<?php 
endif;
// 追加用の非表示行:
?>
    <tr class="empty-row screen-reader-text">
      <td><input type="text" name="label[]" /></td>
      <td><input type="text" name="url[]" /></td>
      <td><a class="button remove-row" href="#">削除</a></td>
    </tr>
  </tbody>
</table>
<?php // 追加用ボタン: ?>
<p><a id="add-row" class="button" href="#">行を追加</a></p>
</div>
