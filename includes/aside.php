<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <!-- App Brand -->
  <div class="app-brand demo">
    <a href="../index.php" class="app-brand-link">
      <span class="app-brand-logo demo me-1">
        <img src="../assets/img/logoWeb.png" width="20" height="20" alt="Logo">
      </span>
      <span class="app-brand-text demo menu-text fw-semibold ms-2">stellarPay</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="mdi mdi-menu d-xl-block align-middle mdi-20px"></i>
    </a>
  </div>
  
  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item active">
      <a href="../index.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
        <div data-i18n="Dashboard">Dashboard</div>
      </a>
    </li>

    <!-- Operations -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Operations</span>
    </li>
    <li class="menu-item">
      <a href="../transactions/pay.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-cash"></i>
        <div data-i18n="Cash In/Out">Pay</div>
      </a>
    </li>
	<li class="menu-item">
      <a href="../transactions/withdraw.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-cash"></i>
        <div data-i18n="Cash In/Out">Withdraw</div>
      </a>
    </li>

    <!-- Transaction History -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Transaction History</span>
    </li>
    <li class="menu-item">
      <a href="../history/transaction_history.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-history"></i>
        <div data-i18n="Transaction History">View Transactions</div>
      </a>
    </li>

    <!-- Support Section -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Support</span>
    </li>
    <li class="menu-item">
      <a href="../support/contact.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-help-circle-outline"></i>
        <div data-i18n="Contact Support">Contact Support</div>
      </a>
    </li>

    <!-- Settings Section -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Settings</span>
    </li>
    <li class="menu-item">
      <a href="#../settings/account.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-cog-outline"></i>
        <div data-i18n="Account Settings">Account Settings</div>
      </a>
    </li>
  </ul>
</aside>