<template>
  <div>
    <div id="preloader">
      <div id="status">
        <div class="spinner-chase">
          <div class="chase-dot"></div>
          <div class="chase-dot"></div>
          <div class="chase-dot"></div>
          <div class="chase-dot"></div>
          <div class="chase-dot"></div>
          <div class="chase-dot"></div>
        </div>
      </div>
    </div>
    <div id="layout-wrapper">
      <NavBar />
      <SideBar :is-condensed="isMenuCondensed" :type="leftSidebarType" :width="layoutWidth" />
      <!-- ============================================================== -->
      <!-- Start Page Content here -->
      <!-- ============================================================== -->

      <div class="main-content">
        <div class="page-content">
          <!-- Start Content-->
          <div class="container-fluid">
             <nuxt />
          </div>
        </div>
        <Footer />
      </div>
    </div>
  </div>
</template>

<script>
// import Navbar from '~/components/Navbar'
import NavBar from "@/components/skote/nav-bar";
import SideBar from "@/components/skote/side-bar";
import Footer from "@/components/skote/footer";

export default {
  components: {
    NavBar, SideBar,Footer
  },
  data() {
    return {
      isMenuCondensed: false,
      layoutType:"vertical",
      layoutWidth:"fluid",
      leftSidebarType:"dark",
      loader:false,
      topbar:"dark",

    };
  },
  created: () => {
    document.body.removeAttribute("data-layout", "horizontal");
    document.body.removeAttribute("data-topbar", "dark");
    document.body.removeAttribute("data-layout-size", "boxed");
    document.body.classList.remove("auth-body-bg");
  },
  methods: {
    toggleMenu() {
      document.body.classList.toggle("sidebar-enable");

      if (window.screen.width >= 992) {
        // eslint-disable-next-line no-unused-vars
        // router.afterEach((routeTo, routeFrom) => {
        //   document.body.classList.remove("sidebar-enable");
        //   document.body.classList.remove("vertical-collpsed");
        // });
        document.body.classList.toggle("vertical-collpsed");
      } else {
        // eslint-disable-next-line no-unused-vars
        // router.afterEach((routeTo, routeFrom) => {
        //   document.body.classList.remove("sidebar-enable");
        // });
        document.body.classList.remove("vertical-collpsed");
      }
      this.isMenuCondensed = !this.isMenuCondensed;
    },
    toggleRightSidebar() {
      document.body.classList.toggle("right-bar-enabled");
    },
    hideRightSidebar() {
      document.body.classList.remove("right-bar-enabled");
    },
  },
  mounted() {
    if (this.loader === true) {
      document.getElementById("preloader").style.display = "block";
      document.getElementById("status").style.display = "block";

      setTimeout(function () {
        document.getElementById("preloader").style.display = "none";
        document.getElementById("status").style.display = "none";
      }, 2500);
    } else {
      document.getElementById("preloader").style.display = "none";
      document.getElementById("status").style.display = "none";
    }
  },
}
</script>
