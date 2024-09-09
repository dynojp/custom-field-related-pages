jQuery(document).ready(($) => {
  const $parent = $("#custom-field-related-pages");
  const $table = $parent.find("table");

  // 「行を追加」ボタンがクリックされたら新たな行を追加する:
  $parent.find("#add-row").on("click", () => {
    const $row = $(".empty-row").clone(true);
    $row.removeClass("empty-row screen-reader-text");
    $row.insertBefore($table.find("tbody>tr:last"));
    return false;
  });

  // 「削除」ボタンがクリックされたら行を削除する:
  $table.on("click", ".remove-row", (event) => {
    $(event.target).parents("tr").remove();
    return false;
  });
});
