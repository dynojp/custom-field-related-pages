<?php

/**
 * Plugin Name: Custom Field Related Pages
 * Description: WordPress において無償・有償のプラグインを使わずに繰り返し可能なカスタムフィールドを実装するサンプル
 * Version: 0.1.0
 * Author: 後藤隼人
 * Author URI: https://dyno.design/
 */

defined( 'ABSPATH' ) || exit;

// カスタムフィールドを登録する
add_action ( 'init', function () {
  // カスタムフィールド `related_pages` を登録する:
  register_post_meta( 'post', 'related_pages', [
    'type' => 'array',
    'single' => true,
    'default' => [],
  ] );
} );

// メタボックスを追加する:
add_action( 'add_meta_boxes', function () {
  // 「関連ページ」のメタボックスを追加する:
  add_meta_box(
    'related_pages', // ID
    '関連ページ', // 表示タイトル
    'slug_display_meta_box', // コールバック
    'post', // 対象スクリーン
    'normal' // コンテキスト（表示位置）
  );
} );
  
/**
 * メタボックス related_pages の表示用コールバック
 */
function slug_display_meta_box($post) {
  $related_pages = get_post_meta( $post->ID, 'related_pages', true );
  wp_nonce_field( basename(__FILE__), 'related_pages' );

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
  if ( ! empty($related_pages) ) {
    foreach ($related_pages as $field) {
  ?>
      <tr>
        <td><input type="text" name="label[]" value="<?php echo esc_attr( $field['label'] ); ?>" /></td>
        <td><input type="text" name="url[]" value="<?php echo esc_attr( $field['url'] ); ?>" /></td>
        <td><a class="button remove-row" href="#">削除</a></td>
      </tr>
  <?php 
    }   
  } else {
  // 保存された値が無い場合
  ?>
      <tr>
        <td><input type="text" name="label[]" /></td>
        <td><input type="text" name="url[]" /></td>
        <td><a class="button remove-row" href="#">削除</a></td>
      </tr>
  <?php 
  } 
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
  <?php
  $assets_dir = plugin_dir_url( __FILE__ ) . 'assets/';

  // CSS ファイルを追加:
  wp_enqueue_style( 
    'custom-field-related-pages', 
    $assets_dir . 'styles.css', 
    [], 
    '0.1.0' 
  );

  // 「削除」と「行を追加」ボタンを機能させる JavaScript コードを追加:
  wp_enqueue_script(
    'custom-field-related-pages',
    $assets_dir . 'script.js',
    ['jquery'],
    '0.1.0',
    true
  );
}

// 投稿保存時にカスタムフィールドを保存する
add_action( 'save_post', function ($post_id) {
  // nonce チェックを行う:
  $nonce_key = 'related_pages';
  if ( ! isset($_POST[$nonce_key]) || 
    ! wp_verify_nonce( $_POST[$nonce_key], basename( __FILE__ ) ) ) {
    return;
  }

  // 自動保存では処理を行わない:
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
    return;
  }

  // ユーザーが編集権限を持たない場合は処理を行わない:
  $post_type = $_POST['post_type'];
  if ( ! current_user_can( 'edit_' . $post_type, $post_id ) ) {
    return;
  }

  // 保存処理を行う:
  $labels = $_POST['label'];
  $urls = $_POST['url'];
  $count = count($labels);

  $new = [];
  foreach ( range(0, count($labels) - 1) as $i ) {
    if ($labels[$i] != '') {
      $new[$i] = [
      'label' => stripslashes( strip_tags( $labels[$i] ) ),
      'url' => stripslashes( strip_tags($urls[$i] ) ),
      ];
    }
  }

  $meta_key = 'related_pages';
  $old = get_post_meta( $post_id, $meta_key, true );
  if ( ! empty($new) && $new != $old ) {
    update_post_meta( $post_id, $meta_key, $new );
  } else if ( empty($new) && $old ) {
    delete_post_meta( $post_id, $meta_key, $old );
  }
} );
