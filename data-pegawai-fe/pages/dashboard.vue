<template>
  <div v-if="user.role_id == 1">
    <b-row class="mb-3">
      <template v-for="(item,i) in data">
        <!-- <b-col cols="12" sm="6" md="4">
          <b-card :title="'Data Usulan '+index[i]">
            <b-row class="mt-3" v-if="user && user.role_id == 2" >
              <b-col class="text-center" cols="4">
                <div>Total </div>
                <div class="font-weight-bold fa-2x">{{data[i].total}}</div>
              </b-col>
              <b-col class="text-center text-muted" cols="4">
                <div>Baru </div>
                <div class="font-weight-bold fa-2x">{{data[i].baru}}</div>
              </b-col>
              <b-col class="text-center text-warning" cols="4">
                <div>Terkirim </div>
                <div class="font-weight-bold fa-2x">{{data[i].terkirim}}</div>
              </b-col>
              <b-col class="text-center text-success" cols="4">
                <div>Diterima </div>
                <div class="font-weight-bold fa-2x">{{data[i].diterima}}</div>
              </b-col>
              <b-col class="text-center text-primary" cols="4">
                <div>Terbit </div>
                <div class="font-weight-bold fa-2x ">{{data[i].terbit}}</div>
              </b-col>
              <b-col class="text-center text-danger" cols="4">
                <div>Revisi </div>
                <div class="font-weight-bold fa-2x">{{data[i].revisi}}</div>
              </b-col>
            </b-row>
            <div v-if="user && user.role_id == 1" >
              <pie-chart-usulan :seriesData="datachart[i]" :id="'id'+i"></pie-chart-usulan>
              <b-card-footer>
                <b-row>
                  <b-col cols="5" class="font-weight-bold">Baru:</b-col>
                  <b-col cols="7" class="text-right">{{data[i].baru}} ({{ (data[i].total > 0 ? (data[i].baru / data[i].total * 100) : 0)| formatDesimal(1)}}%)</b-col>
                </b-row>
                <b-row>
                  <b-col cols="5" class="font-weight-bold">Terkirim:</b-col>
                  <b-col cols="7" class="text-right text-warning">{{data[i].terkirim}} ({{ (data[i].total > 0 ? (data[i].terkirim / data[i].total * 100) : 0)| formatDesimal(1)}}%)</b-col>
                </b-row>
                <b-row>
                  <b-col cols="5" class="font-weight-bold">Diterima:</b-col>
                  <b-col cols="7" class="text-right text-success">{{data[i].diterima}} ({{ (data[i].total > 0 ? (data[i].diterima / data[i].total * 100) : 0)| formatDesimal(1)}}%)</b-col>
                </b-row>
                <b-row>
                  <b-col cols="5" class="font-weight-bold">Terbit:</b-col>
                  <b-col cols="7" class="text-right text-primary">{{data[i].terbit}} ({{ (data[i].total > 0 ? (data[i].terbit / data[i].total * 100) : 0)| formatDesimal(1)}}%)</b-col>
                </b-row>
                <b-row>
                  <b-col cols="5" class="font-weight-bold">Revisi:</b-col>
                  <b-col cols="7" class="text-right text-danger">{{data[i].revisi}} ({{ (data[i].total > 0 ? (data[i].revisi / data[i].total * 100) : 0)| formatDesimal(1)}}%)</b-col>
                </b-row>
              </b-card-footer>
            </div>
          </b-card>
        </b-col> -->
      </template>
    </b-row>
    <div class="d-flex justify-content-between">
      <h3 class="font-weight-bold mb-4">Dashboard</h3>
      <div>
        <FilterDropdown v-model="periode" :options="[
          { value:2022, name:'2022'},
          { value:2021, name:'2021'},
        ]" label="Periode">
          <template #icon>
            <svg class="mr-2" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M13.55 3.1H12.5V1.7C12.5 1.51435 12.4263 1.3363 12.295 1.20503C12.1637 1.07375 11.9857 1 11.8 1C11.6143 1 11.4363 1.07375 11.305 1.20503C11.1737 1.3363 11.1 1.51435 11.1 1.7V3.1H5.5V1.7C5.5 1.51435 5.42625 1.3363 5.29497 1.20503C5.1637 1.07375 4.98565 1 4.8 1C4.61435 1 4.4363 1.07375 4.30503 1.20503C4.17375 1.3363 4.1 1.51435 4.1 1.7V3.1H3.05C2.77152 3.1 2.50445 3.21062 2.30754 3.40754C2.11062 3.60445 2 3.87152 2 4.15V13.95C2 14.2285 2.11062 14.4955 2.30754 14.6925C2.50445 14.8894 2.77152 15 3.05 15H13.55C13.8285 15 14.0955 14.8894 14.2925 14.6925C14.4894 14.4955 14.6 14.2285 14.6 13.95V4.15C14.6 3.87152 14.4894 3.60445 14.2925 3.40754C14.0955 3.21062 13.8285 3.1 13.55 3.1ZM13.2 4.5V5.9H3.4V4.5H13.2ZM3.4 13.6V7.3H13.2V13.6H3.4Z"
                fill="#212121" />
              <path d="M8.99998 8.69995H7.59998V10.1H8.99998V8.69995Z" fill="#212121" />
              <path d="M11.8 8.69995H10.4V10.1H11.8V8.69995Z" fill="#212121" />
              <path d="M6.19999 11.5H4.79999V12.9H6.19999V11.5Z" fill="#212121" />
              <path d="M8.99998 11.5H7.59998V12.9H8.99998V11.5Z" fill="#212121" />
              <path d="M11.8 11.5H10.4V12.9H11.8V11.5Z" fill="#212121" />
            </svg>
          </template>
        </FilterDropdown>
      </div>
    </div>
    <b-row>
      <b-col md="4" cols="6">
        <b-card class="shadow-none border">
          <div class="d-flex justify-content-between mb-2">
            <h5>Statistik Pegawai</h5>
            <i class="bx bx-dots-vertical-rounded h3 mb-0"></i>
          </div>
          <StatistikPegawai />
        </b-card>
      </b-col>
      <b-col md="4" cols="6">
        <b-card class="shadow-none border">
          <div class="d-flex justify-content-between mb-2">
            <h5>Demografi</h5>
            <i class="bx bx-dots-vertical-rounded h3 mb-0"></i>
          </div>
          <ChartDemografi />
        </b-card>
      </b-col>
      <b-col md="4">
        <b-card class="shadow-none border">
          <div class="d-flex justify-content-between mb-2">
            <h5>Total Pegawai</h5>
            <i class="bx bx-dots-vertical-rounded h3 mb-0"></i>
          </div>
          <b-row>
            <b-col cols="6" class="mb-3">
              <label class="text-muted">Pegawai Kontrak</label>
              <h3 class="font-weight-bold">{{(count_contract).toLocaleString('id-ID')}}</h3>
              <div class="text-muted">Pegawai</div>
            </b-col>
            <b-col cols="6" class="mb-3">
              <label class="text-muted">Pegawai Tetap</label>
              <h3 class="font-weight-bold">{{(count_permanent).toLocaleString('id-ID')}}</h3>
              <div class="text-muted">Pegawai</div>
            </b-col>
            <b-col cols="6" class="mb-3">
              <label class="text-muted">Jumlah PNS</label>
              <h3 class="font-weight-bold">{{(count_pns).toLocaleString('id-ID')}}</h3>
              <div class="text-muted">Pegawai</div>
            </b-col>
            <b-col cols="6" class="mb-3">
              <label class="text-muted">Jumlah CPNS</label>
              <h3 class="font-weight-bold">{{(count_cpns).toLocaleString('id-ID')}}</h3>
              <div class="text-muted">Pegawai</div>
            </b-col>
          </b-row>
        </b-card>
      </b-col>
      <b-col sm="12">
        <b-card class="shadow-none border">
          <div class="d-flex justify-content-between mb-2">
            <h5>Jumlah Pegawai Teratas</h5>
            <i class="bx bx-dots-vertical-rounded h3 mb-0"></i>
          </div>
          <b-row class="text-center">
            <div class="col-sm col-6 mb-3">
              <label class="text-muted">Pegawai Tetap</label>
              <h3 class="font-weight-bold text-darkgreen">{{count_permanent.toLocaleString('id-ID')}}</h3>
              <div class="text-muted">Pegawai</div>
            </div>
            <div class="col-sm col-6 mb-3">
              <label class="text-muted">Pegawai Kontrak</label>
              <h3 class="font-weight-bold text-darkgreen">{{count_contract.toLocaleString('id-ID')}}</h3>
              <div class="text-muted">Pegawai</div>
            </div>
            <div class="col-sm col-6 mb-3">
              <label class="text-muted">Jumlah PNS</label>
              <h3 class="font-weight-bold text-darkgreen">{{count_pns.toLocaleString('id-ID')}}</h3>
              <div class="text-muted">Pegawai</div>
            </div>
            <div class="col-sm col-6 mb-3">
              <label class="text-muted">Jumlah CPNS</label>
              <h3 class="font-weight-bold text-darkgreen">{{count_cpns.toLocaleString('id-ID')}}</h3>
              <div class="text-muted">Pegawai</div>
            </div>
          </b-row>
        </b-card>
      </b-col>
    </b-row>
    <b-row>
      <b-col md="8">
        <b-card class="shadow-none border">
          <div class="d-flex justify-content-between mb-2">
            <h5>SIP Akan Berakhir</h5>
            <i class="bx bx-dots-vertical-rounded h3 mb-0"></i>
          </div>
          <div class="table-rounded">
            <b-table-simple hover>
              <b-thead head-variant="gray">
                <b-tr>
                  <b-th>No</b-th>
                  <b-th>Nama</b-th>
                  <b-th>Jabatan</b-th>
                  <b-th>Tanggal Berakhir</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-for="(sip, i) in sip_near_expired">
                  <b-tr>
                    <b-td>{{i+1}}</b-td>
                    <b-td>{{ sip.pegawai.peg_nama_lengkap }}</b-td>
                    <b-td>{{ sip.pegawai.jabatan != null ? sip.pegawai.jabatan.jabatan_nama : '-' }}</b-td>
                    <b-td>{{ sip.tanggal_kadaluarsa_sip | formatDate }}</b-td>
                  </b-tr>
                </template>
              </b-tbody>
            </b-table-simple>
          </div>
        </b-card>
        <b-card class="shadow-none border">
          <div class="d-flex justify-content-between mb-2">
            <h5>STR Akan Berakhir</h5>
            <i class="bx bx-dots-vertical-rounded h3 mb-0"></i>
          </div>

          <div class="table-rounded">
            <b-table-simple hover>
              <b-thead head-variant="gray">
                <b-tr>
                  <b-th>No</b-th>
                  <b-th>Nama</b-th>
                  <b-th>Jabatan</b-th>
                  <b-th>Tanggal Berakhir</b-th>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-for="(str, i) in str_near_expired">
                  <b-tr>
                    <b-td>{{i+1}}</b-td>
                    <b-td>{{ str.pegawai.peg_nama_lengkap }}</b-td>
                    <b-td>{{ str.pegawai.jabatan != null ? str.pegawai.jabatan.jabatan_nama : '-' }}</b-td>
                    <b-td>{{ str.tanggal_kadaluarsa_str | formatDate }}</b-td>
                  </b-tr>
                </template>
              </b-tbody>
            </b-table-simple>
          </div>
        </b-card>
      </b-col>
      <b-col md="4">
        <b-card class="shadow-none border">
          <div class="d-flex justify-content-between mb-2">
            <h5>Mendekati Pensiun</h5>
            <i class="bx bx-dots-vertical-rounded h3 mb-0"></i>
          </div>
          <div v-for="pension in nearest_pension">
            <div class="d-flex">
              <div class="flex-shrink-0 mr-3">
                <b-avatar variant="light" v-if="pension.images != null" :src="pension.images">
                </b-avatar>
                <b-avatar variant="light" v-else
                  :src="'https://www.shutterstock.com/image-vector/default-avatar-profile-icon-social-media-1677509740'">
                </b-avatar>
              </div>
              <div>
                <label class="font-weight-bold mb-0">{{ pension.name }}</label>
                <div class="text-muted">{{ pension.pensiun | formatDate }}</div>
              </div>
            </div>
            <hr>
          </div>
        </b-card>
        <b-card class="shadow-none border">
          <div class="d-flex justify-content-between mb-2">
            <h5>Kontrak Akan Berakhir</h5>
            <i class="bx bx-dots-vertical-rounded h3 mb-0"></i>
          </div>
          <div v-for="contract in contract_almost_over">
            <div class="d-flex">
              <div class="flex-shrink-0 mr-3">
                <b-avatar variant="light" v-if="contract.pegawai.peg_images != null" :src="contract.pegawai.peg_images">
                </b-avatar>
                <b-avatar variant="light" v-else
                  :src="'https://www.shutterstock.com/image-vector/default-avatar-profile-icon-social-media-1677509740'">
                </b-avatar>
              </div>
              <div>
                <label class="font-weight-bold mb-0">{{ contract.pegawai.peg_nama_lengkap }}</label>
                <div class="text-muted">{{ contract.tanggal_habis_kontrak | formatDate }}</div>
              </div>
            </div>
            <hr>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <b-row>
      <!-- <b-col class="mb-3 col-lg-3 col-md-6" v-for="(layanan, index) in layanans" :key="index">
        <div class="card text-center mb-0 h-100">
          <nuxt-link class="card-body" :to="layanan.link">
            <i class="bx mb-4 h1 text-primary" :class="layanan.icon"></i>
            <h5 class="font-size-15 text-uppercase">
              {{ layanan.nama }}
            </h5>
            <p class="text-muted mb-0" v-html="layanan.deskripsi">
            </p>
          </nuxt-link>
        </div>
      </b-col> -->
    </b-row>
  </div>
  <div v-else>
    <b-card>
      <b-card-header>
        <h4>Selamat Datang, {{ user.pegawai.peg_nama_tanpa_gelar }}</h4>
      </b-card-header>
      <b-card-body class="bg-danger"
        v-if="user.pegawai.peg_email == '' || user.pegawai.peg_telp_hp == '' || user.pegawai.peg_rumah_alamat == ''">
        <div>
          <h5 style="color:white">Data Pribadi Anda Belum Lengkap, Silahkan Lengkapi Data Pribadi Anda.</h5>
        </div>
      </b-card-body>
    </b-card>
  </div>
  <div v-else>

  </div>
</template>

<script>
import axios from 'axios'
import { mapGetters } from "vuex"
import PieChartUsulan from './../components/global/PieChartUsulan'
import StatistikPegawai from '~/components/dashboard/StatistikPegawai.vue'
import ChartDemografi from '../components/dashboard/ChartDemografi.vue'
import FilterDropdown from '~/components/global/FilterDropdown.vue'

export default {
  middleware: 'auth',
  components: { PieChartUsulan, StatistikPegawai, ChartDemografi, FilterDropdown },
  head() {
    return { title: this.$t('dashboard') }
  },

  async asyncData() {
    let dataResp = (await axios.get('dashboard')).data
    let count_male = dataResp.count_male
    let count_female = dataResp.count_female
    let count_contract = dataResp.count_contract
    let count_permanent = dataResp.count_permanent
    let count_cpns = dataResp.count_cpns
    let count_pns = dataResp.count_pns
    let sip_near_expired = dataResp.sip_near_expired
    let str_near_expired = dataResp.str_near_expired
    let contract_almost_over = dataResp.contract_almost_over
    let nearest_pension = dataResp.nearest_pension
    // let f1resp = (await axios.get('pengajuan/statistik')).data
    // let f2resp = (await axios.get('job/count-data')).data
    // console.log(f1resp)

    // let datachart = []
    // $.each(f1resp.data,function (index,item) {
    //   console.log('index',index)
    //   datachart.push([{
    //         name: 'Terbit',
    //         y: item['terbit']
    //     }, {
    //         name: 'Baru',
    //         y: item['baru']
    //     }, {
    //         name: 'Terkirim',
    //         y: item['terkirim']
    //     }, {
    //         name: 'Diterima',
    //         y: item['diterima']
    //     }, {
    //         name: 'Revisi',
    //         y: item['revisi']
    //     }
    //   ]);
    // });

    return {
      // index : f1resp.index,
      // data : f1resp.data,
      // datachart
      count_male,
      count_female,
      count_contract,
      count_permanent,
      count_cpns,
      count_pns,
      sip_near_expired,
      str_near_expired,
      contract_almost_over,
      nearest_pension,
      data: []
    }
  },
  provide() {
    return {
      count_male: this.count_male,
      count_female: this.count_female,
      count_contract: this.count_contract,
      count_permanent: this.count_permanent,
      count_pns: this.count_pns,
      count_cpns: this.count_cpns,
    }
  },
  computed: {
    ...mapGetters({
      user: "auth/user",
    }),
  },
  filters: {
    formatDesimal: function (value, count) {
      if (!value) return "0";
      if (value == null || value == "") {
        return "-";
      }
      var number = parseFloat(value).toFixed(count);
      if (number == 0.0) {
        return "-";
      }
      return number;
    }
  },
  data() {
    return {
      periode: null,
      layanans: [
        {
          nama: 'Rekomendasi Pelantikan JPTP',
          deskripsi: 'Ajukan rekomendasi pelantikan jabatan pimpinan tinggi pratama',
          icon: 'bx-building',
          link: '/list-usulan-camat',
        },
        {
          nama: 'Rekomendasi Camat',
          deskripsi: 'Ajukan rekomendasi pelantikan camat',
          icon: 'bxs-bank',
          link: '/list-usulan-pengangkatan-inspektur',
        },
        {
          nama: 'Rekomendasi Pelantikan JA dan JP',
          deskripsi: 'Ajukan rekomendasi pelantikan jabatan administrator dan jabatan pengawas',
          icon: 'bxs-edit-alt',
          link: '/list-usulan-panitia-seleksi-inspektur',
        },
        {
          nama: 'Rekomendasi Inspektorat',
          deskripsi: 'Ajukan rekomendasi pelantikan Inspektur atau Inspektur Pembantu',
          icon: 'bx-buildings',
          link: '/list-usulan-pelantikan-jptp',
        },
        {
          nama: 'Permohonan Izin Uji Kompetensi / Seleksi Terbuka',
          deskripsi: 'Ajukan Permohonan Izin Uji Kompetensi / Seleksi Terbuka',
          icon: 'bx-news',
          link: '/list-usulan-pelantikan-ja-jp',
        },
        {
          nama: 'Permohonan Pejabat Pemprov sebagai Panitia Seleksi Terbuka JPTP',
          deskripsi: 'Ajukan Permohonan Pejabat Pemprov sebagai Panitia Seleksi Terbuka JPTP',
          icon: 'bxs-envelope',
          link: '/list-usulan-uji-kompetensi',
        },
        {
          nama: 'Penggantian JPTP Sekretaris Daerah',
          deskripsi: 'Ajukan Penggantian JPTP Sekretaris Daerah',
          icon: 'bx-street-view',
          link: '/list-usulan-penggantian-jptp-sekda',
        },
        {
          nama: 'Rekomendasi Pansel Inspektur',
          deskripsi: 'Ajukan rekomendasi panitia seleksi untuk inspektur',
          icon: 'bxs-user-detail',
          link: '/list-usulan-panitia-seleksi-jptp',
        },
      ],
    }
  },
  mounted() {
    this.sortPegawai()
  },
  methods: {
    formatDate(value) {
      if (value) {
        return moment(String(value)).format('DDMMYYYY')
      }
    },
    sortPegawai() {
      let maxPegawai = {
        tetap: this.count_permanent,
        kontrak: this.count_contract,
        pns: this.count_pns,
        cpns: this.count_cpns,
      };
      let sortable = [];
      for (var statusPegawai in maxPegawai) {
        sortable.push([statusPegawai, maxPegawai[statusPegawai]]);
      }
      sortable.sort(function (a, b) {
        return b[1] - a[1];
      })
    }
  }
}
</script>

