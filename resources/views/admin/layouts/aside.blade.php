<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ URL('/') }}/admin" class="app-brand-link">
            <h4>{{ env('APP_NAME') }}</h4>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <!-- Dashboards -->
        <li class="menu-item">
            <a href="{{ URL::to('admin') }}" class="menu-link">
                <div data-i18n="Dashboards">
                    <i class="bx bx-card bx-sm align-middle"></i>
                    Dashboards
                </div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('income_expense.accounts_head') }}" class="menu-link">
                <i class="bx bx-book bx-sm align-middle"></i>
                <div data-i18n="Accounts-Head">Accounts Head</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('income_expense.account_sub_head') }}" class="menu-link">
                <i class="bx bx-book bx-sm align-middle"></i>
                <div data-i18n="Accounts-Sub-Head">Accounts Sub Head</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('income_expense.daily_transactions') }}" class="menu-link">
                <i class="bx bx-money bx-sm align-middle"></i>
                <div data-i18n="Daily-Transaction">Daily Transaction</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('requisition.index') }}" class="menu-link">
                <i class="bx bx-file bx-sm align-middle"></i>
                <div data-i18n="requisition">Requisition</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('requisition.company.index') }}" class="menu-link">
                <i class="bx bx-buildings bx-sm align-middle"></i>
                <div data-i18n="Company">Company</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('requisition.purpose.index') }}" class="menu-link">
                <i class="bx bx-target-lock bx-sm align-middle"></i>
                <div data-i18n="Purpose">Purpose</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('requisition.payee.index') }}" class="menu-link">
                <i class="bx bx-dollar bx-sm align-middle"></i>
                <div data-i18n="Payee">Payee</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('requisition.bank.index') }}" class="menu-link">
                <i class="bx bx-credit-card bx-sm align-middle"></i>
                <div data-i18n="Bank">Bank</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('requisition.bank-account.index') }}" class="menu-link">
                <i class="bx bx-book bx-sm align-middle"></i>
                <div data-i18n="Bank">Bank Account</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('requisition.cheque.index') }}" class="menu-link">
                <i class="bx bx-receipt bx-sm align-middle"></i>
                <div data-i18n="ChequeBook">Cheque Book</div>
            </a>
        </li>

    </ul>
</aside>
<!-- / Menu -->