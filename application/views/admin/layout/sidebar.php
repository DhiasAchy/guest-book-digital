<div class="left side-menu">

  <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
    <i class="mdi mdi-close"></i>
  </button>

  <!-- LOGO -->
  <div class="topbar-left">
    <div class="text-center">
      <!--<a href="index.html" class="logo"><i class="mdi mdi-assistant"></i> Urora</a>-->
      <a href="index.html" class="logo">
        <img src="<?= base_url(); ?>assets/admin/images/logo-lg.png" alt="" class="logo-large">
      </a>
    </div>
  </div>


  <div class="sidebar-inner slimscrollleft" id="sidebar-main">

    <div id="sidebar-menu">
      <ul>
        <li class="menu-title">Main</li>

        <li>
          <a href="<?= base_url('admin/dashboard'); ?>" class="waves-effect">
            <i class="mdi mdi-view-dashboard"></i>
            <span> Dashboard
              <!-- <span class="badge badge-pill badge-primary float-right">7</span> -->
            </span>
          </a>
        </li>

        <li class="menu-title">Menu</li>

        <!-- <li>
            <a href="<?= base_url('admin/product'); ?>" class="waves-effect"> <i class="mdi mdi-account-check"></i>Product</a>
        </li> -->

        <!-- <li class="has_sub">
          <a href="javascript:void(0);" class="waves-effect">
            <i class="mdi mdi-microsoft"></i>
            <span> Master Data </span>
            <span class="float-right">
              <i class="mdi mdi-chevron-right"></i>
            </span>
          </a> -->
        <!--           <ul class="list-unstyled">
            <li>
              <a href="<?= base_url('admin/master_detail'); ?>"> <i class="ti-angle-double-right"></i>Master Detail</a>
            </li>
          </ul> -->

        <!-- <ul class="list-unstyled">
            <li>
              <a href="<?= base_url('admin/master_category'); ?>"> <i class="ti-angle-double-right"></i>Master Category</a>
            </li>
          </ul>
        </li> -->
        <!-- 
        <li>
          <a href="<?= base_url('admin/master_category'); ?>" class="waves-effect">
            <i class="mdi mdi-microsoft"></i>
            <span> Master Category </span>
          </a>
        </li> -->

        <!-- <li>
          <a href="<?= base_url('admin/user'); ?>" class="waves-effect">
            <i class="mdi mdi-account-check"></i>
            <span> Management Users </span>
          </a>
        </li> -->

        <!-- <li>
          <a href="<?= base_url('admin/project'); ?>" class="waves-effect">
            <i class="mdi mdi-clipboard-text">
            </i> Project </a>
        </li> -->

        <li>
          <a href="<?= base_url('admin/achievement'); ?>" class="waves-effect">
            <i class="mdi mdi-inbox"></i>
            <span> Achievement </span>
          </a>
        </li>
        <li>
          <a href="<?= base_url('admin/promo'); ?>" class="waves-effect">
            <i class="mdi mdi-inbox"></i>
            <span> Promo </span>
          </a>
        </li>
        <!-- <li>
          <a href="<?= base_url('admin/inbox'); ?>" class="waves-effect">
            <i class="mdi mdi-inbox"></i>
            <span> Inbox </span>
          </a>
        </li> -->
        <li class="has_sub">
          <a href="javascript:void(0);" class="waves-effect">
            <i class="mdi mdi-table"></i>
            <span> Product </span>
            <span class="float-right">
              <i class="mdi mdi-chevron-right"></i>
            </span>
          </a>
          <ul class="list-unstyled">
            <li>
              <a href="<?= base_url('admin/product/collection'); ?>" class="waves-effect">
                <span> Collection </span>
              </a>
            </li>
            <li>
              <a href="<?= base_url('admin/product/brand'); ?>" class="waves-effect">
                Brand
              </a>
            </li>
            <li>
              <a href="<?= base_url('admin/product/product'); ?>" class="waves-effect">
                Product
              </a>
            </li>
            <li>
              <a href="<?= base_url('admin/product/best_selling'); ?>" class="waves-effect">
                Best Selling
              </a>
            </li>
          </ul>
        </li>
        <li class="has_sub">
          <a href="javascript:void(0);" class="waves-effect">
            <i class="mdi mdi-table"></i>
            <span> News </span>
            <span class="float-right">
              <i class="mdi mdi-chevron-right"></i>
            </span>
          </a>
          <ul class="list-unstyled">
            <li>
              <a href="<?= base_url('admin/news/category'); ?>" class="waves-effect">
                Category
              </a>
            </li>
            <li>
              <a href="<?= base_url('admin/news/news'); ?>" class="waves-effect">
                <span> News </span>
              </a>
            </li>
          </ul>
        </li>

      </ul>
    </div>
    <div class="clearfix"></div>
  </div>
  <!-- end sidebarinner -->
</div>
<!-- Left Sidebar End -->