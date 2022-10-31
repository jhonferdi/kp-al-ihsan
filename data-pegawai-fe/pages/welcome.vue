<template>
  <div>
    <b-navbar toggleable="lg" variant="light" class="shadow-sm bg-white" sticky>
      <b-navbar-brand href="#"><img src="@/assets/images/bkd-logo2.png" style="height:50px;" /></b-navbar-brand>

      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <!-- <b-navbar-nav>
          <b-nav-item href="#">Link</b-nav-item>
          <b-nav-item href="#" disabled>Disabled</b-nav-item>
        </b-navbar-nav> -->

        <!-- Right aligned nav items -->
        <b-navbar-nav class="ml-auto">
          <template v-if="authenticated">
            <nuxt-link :to="{ name: 'dashboard' }"  class="btn btn-outline-primary">
              {{ $t('dashboard') }}
            </nuxt-link>
          </template>
          <template v-else>
            <nuxt-link :to="{ name: 'login' }"  class="btn btn-outline-primary">
              {{ $t('login') }}
            </nuxt-link>
          </template>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
    <div>
      <a href="#" class="opacity-hover d-flex align-items-center justify-content-center" id="return-to-top" @click.prevent="backToTop()" data-uk-scroll="" style="display:none;" >
        <i class="dripicons-arrow-thin-up text-white d-flex" style="font-size:20px;"></i>
        </a>
    </div>
    <div class="bg-white font-size-18">
      <div class="home-btn d-none d-sm-block">
        <!-- <router-link tag="a" to="/" class="text-dark">
          <i class="fas fa-home h2"></i>
        </router-link> -->
        
      </div>
      <section class="padding-top-bottom-150px pt-5">
        <div class="container">
          <div class="row">
            
            <div class="col-lg-6 col-md-12 col-xs-12 center-col text-md-left text-center uk-fiex uk-flex-middle">
            
              <div>
              <h2 class="font-weight-bold mb-4 title-medium text-dark text-capitalize">Rekomendasi Kepegawaian</h2>
                <h3 class="text-primary font-weight-bold mb-4">Selamat datang di Sistem Rekomendasi Kepegawaian.</h3>
                <p>Layanan Rekomendasi Badan Kepegawaian Daerah Pemprov Jabar </p>
                <template v-if="authenticated">
                  <nuxt-link :to="{ name: 'dashboard' }" class="btn btn-lg text-uppercase btn-primary sm-display-table no-margin-bottom md-margin-left-right-auto" style="letter-spacing: .2em;">
                    {{ $t('dashboard') }}
                  </nuxt-link>
                </template>
                <template v-else>
                  <nuxt-link :to="{ name: 'login' }"  class="btn btn-lg text-uppercase btn-primary sm-display-table no-margin-bottom md-margin-left-right-auto" style="letter-spacing: .2em;">
                    {{ $t('login') }}
                  </nuxt-link>
                </template>
              </div>
            </div>
            <div class="col-lg-5 col-md-12 col-xs-12 center-col mt-5 mt-lg-0 text-md-left text-center md-margin-top-75px">
              <img class="img-fluid" src="@/assets/images/pns.png" alt="">
            </div>
          </div>
        </div>
      </section>
    </div>
    <div id="services" data-uk-scrollspy="cls:uk-animation-fade" class="padding-top-bottom-150px bg-gray-transparent uk-scrollspy-inview uk-animation-fade font-size-18">
			<div class="container xs-container-spread">
				<div class="row">
					<div class="col-md-9 col-sm-12 col-xs-12 center-col text-center margin-auto">
						
						<div class="reveal-holder aos-init aos-animate" data-aos="fade-right" data-aos-duration="1500">
						
							<div class="reveal-block right aos-init aos-animate" data-aos="fade-right" data-aos-duration="3000"></div>
							
							<h2 class="font-weight-bold mb-4 title-medium text-dark text-capitalize">Layanan</h2>
						</div>
						
						<h3 class="text-primary font-weight-bold mb-4">Sistem Rekomendasi Kepegawaian</h3>
						
						<p>Ajukan permohonan Anda dengan mudah, transparan, dan akuntabel</p>
					</div>
				</div>
				<div class="margin-top-75px" data-uk-scrollspy="cls: uk-animation-slide-bottom-medium; target: > .row > div ; delay: 75; repeat: false">
				
					
          <div class="row">
            <div class="col-12 text-center">
              <div class="home-wrapper">
                <div class="row">
                  <div class="mb-3 col-lg-3 col-md-6" v-for="(layanan, index) in layanans" :key="index">
                    <div class="card maintenance-box mb-0 h-100">
                      <div class="card-body">
                        <i class="bx mb-4 h1 text-primary" :class="layanan.icon"></i>
                        <h5 class="font-size-15 text-uppercase">
                          {{ layanan.nama }}
                        </h5>
                        <p class="text-muted mb-0" v-html="layanan.deskripsi">
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
				</div>
			</div>
		</div>
    <footer class="bg-white font-size-18">
			<div class="container">
        <div class="row padding-left-right-twelve-percents padding-top-bottom-150px" data-uk-scrollspy="cls: uk-animation-slide-bottom-medium; target: > div; delay: 50; repeat: false">
          <div class="col-lg-4 col-md-6 col-sm-12 center-col col-xs-12 text-md-left text-center uk-scrollspy-inview uk-animation-slide-bottom-medium" style="">
            <div class="mb-3 aos-init aos-animate" data-aos="fade-right" data-aos-duration="1500">
              <img src="@/assets/images/bkd-logo2.png" style="width:200px;" alt="">
            </div>
            <!-- <h3 class="mb-4 text-primary text-lg font-weight-bold">Contact.</h3> -->
            <ul class="list-unstyled no-margin">
              <li class="no-margin"><p class="text-dark text-dark d-flex align-items-baseline"><i class="mr-3 fas fa-envelope"></i> bkd@jabarprov.go.id</p></li>
              <li class="no-margin"><p class="text-dark d-flex align-items-baseline"><i class="mr-3 fas fa-phone"></i> 022- 4235026 / 022-4203960 </p></li>
              <li class="no-margin"><p class="text-dark d-flex align-items-baseline"><i class="mr-3 fas fa-fax"></i> 022-4203960</p></li>
              <li class="no-margin"><p class="text-dark d-flex align-items-baseline"><i class="mr-3 fas fa-home"></i> Jl. Ternate No. 2 Bandung 40115</p></li>
            </ul>
          </div>
            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12 center-col mb-4 mb-md-0 text-center text-md-left uk-scrollspy-inview uk-animation-slide-bottom-medium" style="">
              <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d63373.67493372897!2d107.614732!3d-6.907974!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x74f5b8e72c9fd772!2sGraha%20Aparatur%20Badan%20Kepegawaian%20Daerah%20Provinsi%20Jawa%20Barat!5e0!3m2!1sen!2sus!4v1627378093308!5m2!1sen!2sus" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-12 center-col col-xs-12 mb-4 mb-md-0 text-md-left text-center uk-scrollspy-inview uk-animation-slide-bottom-medium" style="">
            <h3 class="mb-4 text-primary text-lg font-weight-bold">Profil.</h3>
            <ul class="list-unstyled no-margin">
              <li class="no-margin"><p class="text-dark"><a class="text-dark" href="https://bkd.jabarprov.go.id/page/12-profil-kepala-bkd">Profil Kepala BKD</a></p></li>
              <li class="no-margin"><p class="text-dark"><a class="text-dark" href="https://bkd.jabarprov.go.id/page/13-sambutan-kelapa-bkd">Sambutan Kepala BKD</a></p></li>
              <li class="no-margin"><p class="text-dark"><a class="text-dark" href="https://bkd.jabarprov.go.id/page/4-sejarah-bkd-jawa-barat">Sejarah BKD</a></p></li>
              <li class="no-margin"><p class="text-dark"><a class="text-dark" href="https://bkd.jabarprov.go.id/page/2-visi-misi-bkd-jawa-barat">Visi dan Misi</a></p></li>
            </ul>
            <ul class="list-unstyled no-margin md-margin-left-right-auto">
        
              <li class="no-margin d-inline"><p class="text-dark d-inline"><a class="text-blue" href="https://facebook.com/BKDJABAR"><i class="fab fa-facebook-f mr-2"></i> </a></p></li>
              <li class="no-margin d-inline"><p class="text-dark d-inline"><a class="text-info" href="https://twitter.com/bkd_jawa_barat"><i class="fab fa-twitter mr-2"></i> </a></p></li>
              <li class="no-margin d-inline"><p class="text-dark d-inline"><a class="text-primary" href="https://www.instagram.com/bkd.jabar"><i class="fab fa-instagram mr-2"></i> </a></p></li>
              <li class="no-margin d-inline"><p class="text-dark d-inline no-margin-bottom"><a class="text-danger" href="https://plus.google.com/117548974572651648465"><i class="youtube fab fa-google-plus mr-2"></i> </a></p></li>
            </ul>
          </div>
        </div>
      </div>
		</footer>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import AOS from 'aos'
import 'aos/dist/aos.css'

export default {
  layout: 'simple-2',

  data: () => ({
    layanans: [
      {
        nama: 'Rekomendasi Pelantikan JPTP',
        deskripsi: 'Ajukan rekomendasi pelantikan jabatan pimpinan tinggi pratama',
        icon: 'bx-building',
      },
      {
        nama: 'Rekomendasi Camat',
        deskripsi: 'Ajukan rekomendasi pelantikan camat',
        icon: 'bxs-bank',
      },
      {
        nama: 'Rekomendasi Pelantikan JA dan JP',
        deskripsi: 'Ajukan rekomendasi pelantikan jabatan administrator dan jabatan pengawas',
        icon: 'bxs-edit-alt',
      },
      {
        nama: 'Rekomendasi Inspektorat',
        deskripsi: 'Ajukan rekomendasi pelantikan Inspektur atau Inspektur Pembantu',
        icon: 'bx-buildings',
      },
      {
        nama: 'Permohonan Izin Uji Kompetensi / Seleksi Terbuka',
        deskripsi: 'Ajukan Permohonan Izin Uji Kompetensi / Seleksi Terbuka',
        icon: 'bx-news',
      },
      {
        nama: 'Permohonan Pejabat Pemprov sebagai Panitia Seleksi Terbuka JPTP',
        deskripsi: 'Ajukan Permohonan Pejabat Pemprov sebagai Panitia Seleksi Terbuka JPTP',
        icon: 'bxs-envelope',
      },
      {
        nama: 'Penggantian JPTP Sekretaris Daerah',
        deskripsi: 'Ajukan Penggantian JPTP Sekretaris Daerah',
        icon: 'bx-street-view',
      },
      {
        nama: 'Rekomendasi Pansel Inspektur',
        deskripsi: 'Ajukan rekomendasi panitia seleksi untuk inspektur',
        icon: 'bxs-user-detail',
      },
    ],
    title: process.env.appName
  }),

  head () {
    return { title: this.$t('home') }
  },
  methods:{
    backToTop:function(){
      $("body,html").animate({
        scrollTop: 0
      }, 500)
    },
    updateScroll:function() {
      if(window.scrollY > 0){
        $('#return-to-top').css('visibility','visible');
        $('#return-to-top').removeClass('opacity-0');
      }else{
        $('#return-to-top').css('visibility','hidden');
        $('#return-to-top').addClass('opacity-0');
      }
    },
  },
  computed: mapGetters({
    authenticated: 'auth/check'
  }),
  mounted() {
    window.addEventListener('scroll', this.updateScroll);
    AOS.init();
  }
}
</script>

<style scoped>
.opacity-0{
  opacity: 0;
}
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.title {
  font-size: 85px;
}

.laravel {
  color: #2e495e;
}

.nuxt {
  color: #00c48d;
}
body{
  font-size: 18px;
}
.title-medium {
    font-size: 75px;
    line-height: 1.1;
}
.center-col {
    float: none;
    margin-left: auto;
    margin-right: auto;
}
.padding-top-bottom-150px {
    padding-top: 150px;
    padding-bottom: 150px;
}
.padding-left-right-twelve-percent {
    padding-left: 12%;
    padding-right: 12%;
}
@media (max-width: 767px){
  .title-medium{
      font-size: 46px !important;
  }
}
.bg-image{
  width: 100%;
  height: auto;
  background-size: cover;
  display: block;
  position: relative;
  overflow: hidden;
  background-repeat: no-repeat;
  background-position: center center;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  z-index: 1;
}
.overlay-white:after {
  content: '';
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  position: absolute;
  z-index: -1;
  opacity: 0.85;
  background: #fff;
}
#return-to-top {
  position: fixed;
  bottom: 40px;
  right: 40px;
  background: #525fe6;
  width: 50px;
  height: 50px;
  line-height: 45px;
  border-radius: 100%;
  color: #fff !important;
  z-index: 999 !important;
  text-align: center;
  display: none;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
@media (max-width: 767px){
  #return-to-top {
      right: 45px !important;
  }
}
#return-to-top:hover{
	transform: scale(1.2);
	-webkit-transform: scale(1.2);
	-moz-transform: scale(1.2);
	-ms-transform: scale(1.2);
}
.opacity-hover:hover{
	opacity: 0.6;
}
</style>
