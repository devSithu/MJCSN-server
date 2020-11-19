<div class="page-header qb-page-header qb-sub-page-header">
  <nav class="qb-step-nav-material" id="qb-step-navbar">
    <ul>
      <li @if($active >= 1) class="active" @endif>
        <span class="step-num"><i class="material-icons step-check">check</i></span>
        <div class="step-text">概要の入力</div>
      </li>
      <li @if($active >= 2) class="active" @endif>
        <span class="step-num">@if($active < 2) 2 @else <i class="material-icons step-check">check</i>@endif</span>
        <div class="step-text">項目の設定</div>
      </li>
      <li @if($active >= 3) class="active" @endif>
        <span class="step-num">3</span>
        <div class="step-text">完了</div>
      </li>
    </ul>
  </nav>
</div>
