<template>
  <b-card title="Konfigurasi Hari Libur">
    <style scoped>
      .tabel-title{
        font-size: 14px;
        font-weight: bold;
        margin-top: 30px;
      }
      .th-center{
        text-align: center;
      }
      .badge{
        cursor: pointer;
      }
    </style>
  
    <b-container fluid>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tahun</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.year"
          >
            <option value="">Pilih tahun</option>
            <option :value="item" v-for="(item,id) in yearList" :key="id">{{ item }}</option>
          </select>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Bulan</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.month"
          >
            <option value="">Pilih bulan</option>
            <option :value="item.id" v-for="(item,id) in monthList" :key="id">{{ item.name }}</option>
          </select>
        </b-col>
      </b-row>

      <div>
        <div>
          <div class="tabel-title">Bulan {{ getMonthName(form.month) }} Tahun {{ form.year }}</div>
        </div>

        <!-- <div class="form-group row">
          <div class="col-sm-8">

          </div>
          <div class="col-sm-4">
            <div class="row">
              <label class="col-sm-3 col-form-label">
                Cari:
              </label>
              <div class="col-sm-9">
                <input type="text" class="form-control" v-model="searchparams.search" @change="setFilter('search', searchparams.search)">
              </div>
            </div>
          </div>
        </div>   -->
        <b-row>
          <b-table-simple responsive>
            <b-thead head-variant="dark">
              <b-tr>
                <b-th v-for="(item,id) in dayList" :key="id" class="th-center">{{ item }}</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr>
                <!-- <b-td>{{i+1}}</b-td> -->
                <b-td v-for="(day,id) in dayList" :key="id">
                  <b-button variant="light" size="sm" @click="openAddModal(day)" v-if="!hasHariLibur(day)">-</b-button>
                  <b-button variant="light" size="sm" @click="openUpdateModal(day, getHariLiburDetail(day))" v-if="isMasuk(day)">-</b-button>
                  <h5 v-if="isLibur(day)"><b-badge variant="danger" @click="openUpdateModal(day, getHariLiburDetail(day))">Libur</b-badge></h5>
                </b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
          <!-- <div class="col-12">
            <div class="max-width">
              <pagination
                v-model="current"
                :length="paginates"
                :count="datacount"
                :perpage="paginate"
              ></pagination>
            </div>
          </div> -->
        </b-row>
      </div>
    </b-container>
    
    <div class="mt-3">
      <!-- <b-button variant="info" @click="save()"><i class="fa fa-save"></i> Simpan</b-button> -->
      <!-- <nuxt-link :to="'/konfigurasi-hari-libur/'">
        <b-button variant="light">Kembali</b-button>
      </nuxt-link> -->
    </div>

    <div>
      <b-modal id="modal-add" ref="modal-add">
        <template #modal-header="{}">
          <h5>Konfigurasi Hari Libur</h5>
        </template>
        <template #default="{}">
          <b-row class="my-1">
            <b-col sm="12">
              <label>Tanggal</label>
            </b-col>
            <b-col sm="12">
              <input type="text" class="form-control" v-model="form.day" readonly>
            </b-col>
          </b-row>
          <b-row class="my-1">
            <b-col sm="12">
              <label>Status</label>
            </b-col>
            <b-col sm="12">
              <select
                class="form-control"
                v-model="form.status"
              >
                <option value="libur">Libur</option>
                <option value="masuk">Masuk</option>
              </select>
            </b-col>
          </b-row>
        </template>
        <template #modal-footer="{}">
          <!-- Emulate built in modal footer ok and cancel button actions -->
          <b-button size="md" variant="info" @click="save()">
            <i class="fa fa-save"></i> Simpan
          </b-button>
          <b-button size="md" variant="light" @click="cancelAddModal()">
            Batal
          </b-button>
        </template>
      </b-modal>
    </div>
  </b-card>
</template>

<style scoped>
.label-title label{
  font-weight: bold;
  border-bottom: 1px solid #999;
  display: block;
  padding-top: 20px;
}
</style>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { parseParams, stringifyParams } from "@/utils";
import UploadFileCard from '@/components/global/uploadFile'
import { mapGetters } from "vuex"
// import DateDropdown from 'vue-date-dropdown'
import DateDropdown from '@/components/global/DateDropdown'

export default {
  middleware: 'auth',
  components: {
    UploadFileCard,
    DateDropdown
  },

  head() {
    return { title: this.$t('project') }
  },

  data: () => ({
    disabled: 0,
    readonly: false,
    unitKerjaList: [],
    hariLiburList: [],
    yearList: [],
    monthList: [],
    dayList: [],
    jamKerjaList: [],
    pegawaiSelected: null,
    pegawaiNipSelected: '',
    pegawaiImgSrc: '',
    pegawaiNamaSelected: '',
    daySelected: ''
  }),
  computed: mapGetters({
    user: "auth/user",
  }),
  async asyncData({ app, params }) {
    let satuanKerjaList = [];
    let jamKerjaList = [];
    try{
      let f1 = await axios.get("/form-data/satuan-kerja-list?is_shift=1&has_wh_opd=1");
      satuanKerjaList = f1.data.data;

      // let f2 = await axios.get("/master-jam-kerja/all");
      // jamKerjaList = f2.data.data;
    } catch (error) {
      console.log(error.response);
    }

    let searchparams = parseParams(params.params);
    let paginate = 10;
    searchparams.perpage = paginate;
    let datalist = [];
    let datacount = 0;
    let current = searchparams.page ? parseInt(searchparams.page) : 1;
    
    return {
      form: {
        id: null,
        satuan_kerja_id: searchparams.satuan_kerja_id ? searchparams.satuan_kerja_id : '',
        unit_kerja_id: searchparams.unit_kerja_id ? searchparams.unit_kerja_id : '',
        year: (new Date()).getFullYear(),
        month: (new Date()).getMonth() + 1,
        status: 'libur',
        working_hour_id: '',
        day: ''
      },  
      searchparams,
      current,
      datalist,
      datacount,
      paginate,
      paginates: Math.ceil(datacount / paginate),
      satuanKerjaList,
      jamKerjaList
    };
  },
  mounted: function() {
    if(this.$route.name == 'update_konfigurasi_kuota_pegawai_shift'){
      this.getViewData();
    }

    this.yearList.push((new Date()).getFullYear());

    this.monthList = [
      {
        id: 1,
        name: 'Januari'
      },
      {
        id: 2,
        name: 'Februari'
      },
      {
        id: 3,
        name: 'Maret'
      },
      {
        id: 4,
        name: 'April'
      },
      {
        id: 5,
        name: 'Mei'
      },
      {
        id: 6,
        name: 'Juni'
      },
      {
        id: 7,
        name: 'Juli'
      },
      {
        id: 8,
        name: 'Agustus'
      },
      {
        id: 9,
        name: 'September'
      },
      {
        id: 10,
        name: 'Oktober'
      },
      {
        id: 11,
        name: 'November'
      },
      {
        id: 12,
        name: 'Desember'
      }
    ];

    this.getDayList();
    this.getHariLibur();

    if(this.form.satuan_kerja_id){
      this.getUnitKerja();
      this.getJamkerja();
    }

    if(this.form.unit_kerja_id){
      this.getPegawaiList();
    }
  },
  watch: {
    'form.year': {
      handler(val){
        this.getDayList();
        this.getHariLibur();
      },
    },
    'form.month': {
      handler(val){
        this.getDayList();
        this.getHariLibur();
      },
    }
  },
  methods: {
    async getUnitKerja(){
      this.unitKerjaList = [];
      if(this.form.satuan_kerja_id){
        try{
          let f1 = await axios.get("/form-data/unit-kerja-list/" + this.form.satuan_kerja_id + '?has_pegawai=1');
          let data = f1.data.data;
          console.log(data);
          this.unitKerjaList = data;
        } catch (error) {
          console.log(error.response);
        }
      }
    },
    async getViewData(){
      try{
        let id = this.$route.params.id;
        let f1 = await axios.get("/konfigurasi-hari-libur/view/" + id);
        let data = f1.data.data;
        console.log(data);
        this.form = data;
      } catch (error) {
        console.log(error.response);
      }
    },
    async getHariLibur(){
      this.hariLiburList = [];
      try{
        let f1 = await axios.get("/konfigurasi-hari-libur?year=" + this.form.year + '&month=' + this.form.month);
        this.hariLiburList = f1.data.data;
        console.log(data);
      } catch (error) {
        console.log(error.response);
      }
    },
    async getDayList(){
      const lastDayOfMonth = new Date(this.form.year, this.form.month, 0);
      const lastDate = lastDayOfMonth.getDate();

      this.dayList = [];
      for(let i = 1; i <= lastDate; i++){
        this.dayList.push(i)
      }
    },
    getMonthName(month){
      for(let i = 0; i < this.monthList.length; i++){
        if(this.monthList[i].id == month){
          return this.monthList[i].name;
        }
      }

      return '';
    },
    async getJamkerja(){
      this.jamKerjaList = [];
      try{
        let f1 = await axios.get("/form-data/jam-kerja-list/" + (this.form.satuan_kerja_id ? this.form.satuan_kerja_id : 0) + '?is_shift=true');
        let data = f1.data.data;
        console.log(data);
        this.jamKerjaList = data;
      } catch (error) {
        console.log(error.response);
      }
    },
    changePage() {},
    pageLink(page) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams.page = page;
      return { path: "/konfigurasi-hari-libur/" + stringifyParams(searchparams) };
    },
    sortLink(sort) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams.sort = sort;
      delete searchparams.page;
      return { path: "/konfigurasi-hari-libur/" + stringifyParams(searchparams) };
    },
    setFilter(col, val) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams[col] = val;
      searchparams['satuan_kerja_id'] = this.form.satuan_kerja_id;
      searchparams['unit_kerja_id'] = this.form.unit_kerja_id;
      delete searchparams.page;
      this.$router.replace({
        path: "/konfigurasi-hari-libur/" + stringifyParams(searchparams),
      });
    },
    validateForm() {
      // if (!this.form.satuan_kerja_id || this.form.satuan_kerja_id == '') {
      //   Swal.fire({
      //     icon: 'warning',
      //     title: 'Gagal',
      //     text: 'Kolom Satuan Kerja belum diisi',
      //     timer:5000
      //   })
      //   return false
      // }
      if (!this.form.status || this.form.status == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Status belum diisi',
          timer:5000
        })
        return false
      }
      
      return true
    },
    async save() {
      if (!this.validateForm()) return
      this.isLoading = true
      Swal.fire({
        title: 'Menyimpan Data!',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
      });

      let url = "/konfigurasi-hari-libur/store";
      if(this.form.id){
        url = "/konfigurasi-hari-libur/update";
      }
      
      await axios.post(url, {
          id: this.form.id,
          year: this.form.year,
          month: this.form.month,
          day: this.form.day,
          status: this.form.status,
        })
        .then(({ data }) => {
          console.log(data);
          if (data.success) {
            Swal.close()
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data Telah Tersimpan',
              confirmButtonText: 'Ok',
              timer: 5000
            })
            // this.$router.push("/konfigurasi-hari-libur")
            this.cancelAddModal();
            this.getHariLibur();
            this.getDayList();

          } else{
            Swal.close()
            Swal.fire({
              icon: 'warning',
              title: 'Gagal',
              text: data.message,
              confirmButtonText: 'Ok',
              timer: 5000
            })
          }

        })
        .catch((e) => {
          Swal.close()
          this.isLoading = false
          // this.$swal.fire("Gagal", "Gagal Menyimpan Data !", "error");
          Swal.fire({
            icon: 'warning',
            title: 'Gagal',
            text: 'Gagal Menyimpan Data! '+e,
            confirmButtonText: 'Ok'
          })
        });
    },
    openAddModal(day){
      this.form.id = null;
      this.form.day = day;
      this.$refs['modal-add'].show()
    },
    openUpdateModal(day, hariLiburDetail){
      this.form.id = hariLiburDetail.id;
      this.form.day = day;
      this.form.status = hariLiburDetail.status;
      this.$refs['modal-add'].show()
    },
    cancelAddModal(){
      this.$refs['modal-add'].hide()
      this.form.status = 'libur'
    },
    getHariLiburDetail(day){
      for(let i = 0; i < this.hariLiburList.length; i++){
        if(this.hariLiburList[i].day == day){
          return this.hariLiburList[i];
        }
      }

      return null;
    },
    hasHariLibur(day){
      const hariLibur = this.getHariLiburDetail(day);
      return hariLibur;
    },
    isMasuk(day){
      const hariLibur = this.getHariLiburDetail(day);
      return hariLibur ? (hariLibur.status == 'masuk') : false;
    },
    isLibur(day){
      const hariLibur = this.getHariLiburDetail(day);
      return hariLibur ? (hariLibur.status == 'libur') : false;
    },
  },

}
</script>
