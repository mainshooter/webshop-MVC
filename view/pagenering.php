<?php

  $list = '';
  $list .= '
  <div class="pagenering col-12">
    <ul>';
  for ($i=0; $i < $pages; $i++) {
    $list .= '<li><a href="?pageNumer=' . $i . '">' . $p=$i + 1 . '</a></li>';
  }
  $list .= '</ul></div>';

  echo $list;


?>
