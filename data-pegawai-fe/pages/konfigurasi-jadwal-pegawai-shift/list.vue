<template>
  <b-card title="Konfigurasi Jadwal Kerja Pegawai Shift">
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
          <label>Satuan Kerja</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.satuan_kerja_id"
            :disabled="isSatuanKerjaDisabled()"
          >
            <option value="">Pilih satuan kerja</option>
            <option :value="item.satuan_kerja_id" v-for="(item,id) in satuanKerjaList" :key="id">{{ item.satuan_kerja_nama }}</option>
          </select>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Unit Kerja</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.unit_kerja_id"
            :disabled="isUnitKerjaDisabled()"
          >
            <option value="">Pilih unit kerja</option>
            <option :value="item.unit_kerja_id" v-for="(item,id) in unitKerjaList" :key="id">{{ item.unit_kerja_nama }}</option>
          </select>
        </b-col>
      </b-row>
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

      <div v-if="form.unit_kerja_id">
        <div>
          <div class="tabel-title">Bulan {{ getMonthName(form.month) }} Tahun {{ form.year }}</div>
        </div>

        <div class="form-group row">
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
        </div>  
        <b-row>
          <b-table-simple responsive>
            <b-thead head-variant="dark">
              <b-tr>
                <b-th style="min-width: 200px;">NAMA PEGAWAI</b-th>
                <b-th v-for="(item,id) in dayList" :key="id" class="th-center">{{ item }}</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-for="(item, i) in pegawaiList">
                <b-tr :key="i">
                  <!-- <b-td>{{i+1}}</b-td> -->
                  <b-td class="row">
                    <div class="col-3">
                      <img :src="('https://siap.jabarprov.go.id/uploads/'+ item.peg_foto)" v-if="item.peg_foto" :alt="'FOTO'" style="width: 50px;vertical-align:top;'">
                    </div>
                    <div class="col-9" style="padding-left: 20px">
                      {{ item.peg_nama }}
                    </div>
                  </b-td>
                  <b-td v-for="(day,id) in dayList" :key="id">
                    <b-button variant="info" size="sm" @click="openAddModal(day, item)" v-if="!hasWhpShift(day, item.whp_shift)">Atur</b-button>
                    <h5 v-if="isWhpShiftMasuk(day, item.whp_shift)"><b-badge variant="success" @click="openUpdateModal(day, item, getWhpShiftDetail(day, item.whp_shift))">{{ getWhpShiftWorkingHour(day, item.whp_shift) }}</b-badge></h5>
                    <h5 v-if="isWhpShiftLibur(day, item.whp_shift)"><b-badge variant="danger" @click="openUpdateModal(day, item, getWhpShiftDetail(day, item.whp_shift))">Libur</b-badge></h5>
                    <h5 v-if="isWhpShiftCuti(day, item.whp_shift)"><b-badge variant="danger" @click="openUpdateModal(day, item, getWhpShiftDetail(day, item.whp_shift))">Cuti</b-badge></h5>
                  </b-td>
                  <!-- <b-td>
                    <nuxt-link :to="'/konfigurasi-jadwal-pegawai-shift/update/'+item.id">
                      <b-button variant="warning" size="sm">
                        <b-icon icon="pencil"></b-icon>
                      </b-button>
                    </nuxt-link>
                    <b-button variant="danger" size="sm" @click="deleteItem(item, i)">
                      <b-icon icon="trash"></b-icon>
                    </b-button>
                  </b-td> -->
                </b-tr>
              </template>
              <b-tr :key="-1" v-if="pegawaiList.length <= 0">
                <b-td :colspan="dayList.length + 1" class="text-center">Tidak ada data</b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
          <div class="col-12">
            <div class="max-width">
              <pagination
                v-model="current"
                :length="paginates"
                :count="datacount"
                :perpage="paginate"
              ></pagination>
            </div>
          </div>
        </b-row>
      </div>
    </b-container>
    
    <div class="mt-3">
      <!-- <b-button variant="info" @click="save()"><i class="fa fa-save"></i> Simpan</b-button> -->
      <!-- <nuxt-link :to="'/konfigurasi-jadwal-pegawai-shift/'">
        <b-button variant="light">Kembali</b-button>
      </nuxt-link> -->
    </div>

    <div>
      <b-modal id="modal-add" ref="modal-add">
        <template #modal-header="{}">
          <h5>Konfigurasi Jam Kerja Shift</h5>
        </template>
        <template #default="{}">
          <b-row class="my-1">
            <b-col sm="12">
              <label>Pegawai</label>
            </b-col>
            <b-col sm="12">
              <img :src="pegawaiImgSrc" v-if="pegawaiImgSrc" :alt="'FOTO'" style="width: 50px;margin-right: 10px;vertical-align:top;'">
              {{ pegawaiNamaSelected }}
              <!-- <input type="text" class="form-control" v-model="pegawaiNamaSelected" readonly> -->
            </b-col>
          </b-row>
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
              <b-form-group v-slot="{ status }">
                <b-form-radio v-model="form.status" :aria-describedby="status" name="status" value="masuk">Masuk</b-form-radio>
                <b-form-radio v-model="form.status" :aria-describedby="status" name="status" value="libur">Libur</b-form-radio>
                <b-form-radio v-model="form.status" :aria-describedby="status" name="status" value="cuti">Cuti</b-form-radio>
              </b-form-group>
              <!-- <select
                class="form-control"
                v-model="form.status"
              >
                <option value="masuk">Masuk</option>
                <option value="libur">Libur</option>
                <option value="cuti">Cuti</option>
              </select> -->
            </b-col>
          </b-row>
          <b-row class="my-1" v-if="form.status == 'masuk'">
            <b-col sm="12">
              <label>Jam Kerja</label>
            </b-col>
            <b-col sm="12">
              <select
                class="form-control"
                v-model="form.working_hour_id"
              >
                <option value="">Pilih jam kerja</option>
                <option :value="item.id" v-for="(item,id) in jamKerjaList" :key="id">{{ item.name }}</option>
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
    pegawaiList: [],
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
        status: 'masuk',
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
    // if(this.$route.name == 'update_konfigurasi_kuota_pegawai_shift'){
    //   this.getViewData();
    // }

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

    if(this.form.satuan_kerja_id){
      this.getUnitKerja();
      this.getJamkerja();
    }

    if(this.form.unit_kerja_id){
      this.getPegawaiList();
    }

    console.log(this.user);
    if(this.user.satuan_kerja_id){
      this.form.satuan_kerja_id = this.user.satuan_kerja_id;
    }

    if(this.user.unit_kerja_id){
      this.form.unit_kerja_id = this.user.unit_kerja_id;
    }
  },
  watch: {
    'form.satuan_kerja_id': {
      handler(val){
        this.getUnitKerja();
        this.getJamkerja();
      },
    },
    'form.unit_kerja_id': {
      handler(val){
        this.getPegawaiList();
      },
    },
    'form.year': {
      handler(val){
        this.getDayList();
      },
    },
    'form.month': {
      handler(val){
        this.getDayList();
      },
    }
  },
  methods: {
    isSatuanKerjaDisabled(){
      if(this.user.satuan_kerja_id){
        this.form.satuan_kerja_id = this.user.satuan_kerja_id;
        return true;
      }
      return false;
    },
    isUnitKerjaDisabled(){
      if(this.user.unit_kerja_id){
        this.form.unit_kerja_id = this.user.unit_kerja_id;
        return true;
      }
      return false;
    },
    async getPegawaiList(){
      let searchparams = this.searchparams;
      let paginate = 10;
      searchparams.perpage = paginate;
      searchparams.unit_kerja_id = this.form.unit_kerja_id;

      let datalist = [];
      let datacount = 0;
      let f1 = await axios.get("/form-data/pegawai", {
        params: {
          searchparams: searchparams,
        },
      });
      let f1resp = (await f1).data;
      datalist = f1resp.data;
      datacount = f1resp.count;
      
      let current = searchparams.page ? parseInt(searchparams.page) : 1;
      let sort = searchparams.sort ? searchparams.sort : "all";
      this.searchparams = searchparams;
      this.current = current;
      this.sort = sort;
      this.pegawaiList = datalist;
      this.datacount = datacount
      this.paginate = paginate;
      this.paginates= Math.ceil(datacount / paginate);

      // this.pegawaiList = [];
      // if(this.form.unit_kerja_id){
      //   try{
      //     let f1 = await axios.get("/form-data/pegawai", {
      //       unit_kerja_id: this.form.unit_kerja_id
      //     });
      //     let data = f1.data.data;
      //     console.log(data);
      //     this.pegawaiList = data;
      //   } catch (error) {
      //     console.log(error.response);
      //   }
      // }
    },
    async getUnitKerja(){
      this.unitKerjaList = [];
      if(this.form.satuan_kerja_id){
        try{
          let data = [];
          if(this.user.role_id == 1){
            let f1 = await axios.get("/form-data/unit-kerja-list/" + this.form.satuan_kerja_id + '?has_pegawai=1');
            data = f1.data.data;
          } else {
            let f1 = await axios.get("/form-data/unit-kerja-list/" + this.form.satuan_kerja_id);
            data = f1.data.data;
          }
          this.unitKerjaList = data;
        } catch (error) {
          console.log(error.response);
        }
      }
    },
    async getViewData(){
      try{
        let id = this.$route.params.id;
        let f1 = await axios.get("/konfigurasi-jadwal-pegawai-shift/view/" + id);
        let data = f1.data.data;
        console.log(data);
        this.form = data;
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
    getMonthName(month){
      for(let i = 0; i < this.monthList.length; i++){
        if(this.monthList[i].id == month){
          return this.monthList[i].name;
        }
      }

      return '';
    },
    changePage() {},
    pageLink(page) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams.page = page;
      return { path: "/konfigurasi-jadwal-pegawai-shift/" + stringifyParams(searchparams) };
    },
    sortLink(sort) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams.sort = sort;
      delete searchparams.page;
      return { path: "/konfigurasi-jadwal-pegawai-shift/" + stringifyParams(searchparams) };
    },
    setFilter(col, val) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams[col] = val;
      searchparams['satuan_kerja_id'] = this.form.satuan_kerja_id;
      searchparams['unit_kerja_id'] = this.form.unit_kerja_id;
      delete searchparams.page;
      this.$router.replace({
        path: "/konfigurasi-jadwal-pegawai-shift/" + stringifyParams(searchparams),
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
      if(this.form.status == 'masuk'){
        if (!this.form.working_hour_id || this.form.working_hour_id == '') {
          Swal.fire({
            icon: 'warning',
            title: 'Gagal',
            text: 'Kolom Jam Kerja belum diisi',
            timer:5000
          })
          return false
        }
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

      let url = "/konfigurasi-jadwal-pegawai-shift/store";
      if(this.form.id){
        url = "/konfigurasi-jadwal-pegawai-shift/update";
      }
      
      await axios.post(url, {
          id: this.form.id,
          satuan_kerja_id: this.form.satuan_kerja_id,
          unit_kerja_id: this.form.unit_kerja_id,
          year: this.form.year,
          month: this.form.month,
          day: this.form.day,
          status: this.form.status,
          working_hour_id: this.form.working_hour_id,
          peg_id: this.pegawaiSelected.peg_id
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
            // this.$router.push("/konfigurasi-jadwal-pegawai-shift")
            this.cancelAddModal();
            this.getPegawaiList();
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
    openAddModal(day, data){
      this.form.id = null;
      this.form.day = day;
      this.pegawaiSelected = data;
      this.pegawaiNipSelected = data ? data.peg_nip : '';
      this.pegawaiImgSrc = 'https://siap.jabarprov.go.id/uploads/'+ (data ? data.peg_foto : '');
      this.pegawaiNamaSelected = data ? data.peg_nama : '';
      this.$refs['modal-add'].show()
    },
    openUpdateModal(day, data, whpShiftDetail){
      this.form.id = whpShiftDetail.id;
      this.form.day = day;
      this.form.status = whpShiftDetail.status;
      this.form.working_hour_id = whpShiftDetail.working_hour_id;
      this.pegawaiSelected = data;
      this.pegawaiNipSelected = data ? data.peg_nip : '';
      this.pegawaiImgSrc = 'https://siap.jabarprov.go.id/uploads/'+ (data ? data.peg_foto : '');
      this.pegawaiNamaSelected = data ? data.peg_nama : '';
      this.$refs['modal-add'].show()
    },
    cancelAddModal(){
      this.$refs['modal-add'].hide()
      this.form.status = 'masuk'
      this.form.working_hour_id = ''
    },
    getWhpShiftDetail(day, whpShift){
      if(whpShift){
        if(whpShift.whp_shift_detail.length > 0){
          for(let i = 0; i < whpShift.whp_shift_detail.length; i++){
            if(whpShift.whp_shift_detail[i].day == day){
              return whpShift.whp_shift_detail[i];
            }
          }
        }
      }

      return null;
    },
    hasWhpShift(day, whpShift){
      const getWhpShiftDetail = this.getWhpShiftDetail(day, whpShift);
      return getWhpShiftDetail;
    },
    isWhpShiftMasuk(day, whpShift){
      const getWhpShiftDetail = this.getWhpShiftDetail(day, whpShift);
      return getWhpShiftDetail ? (getWhpShiftDetail.status == 'masuk') : false;
    },
    isWhpShiftCuti(day, whpShift){
      const getWhpShiftDetail = this.getWhpShiftDetail(day, whpShift);
      return getWhpShiftDetail ? (getWhpShiftDetail.status == 'cuti') : false;
    },
    isWhpShiftLibur(day, whpShift){
      const getWhpShiftDetail = this.getWhpShiftDetail(day, whpShift);
      return getWhpShiftDetail ? (getWhpShiftDetail.status == 'libur') : false;
    },
    getWhpShiftWorkingHour(day, whpShift){
      const getWhpShiftDetail = this.getWhpShiftDetail(day, whpShift);
      let workingHourName = getWhpShiftDetail ? (getWhpShiftDetail.working_hour ? getWhpShiftDetail.working_hour.name : '-') : '-';
      if(workingHourName){
        const workingHourNameArr = workingHourName.split(' ');
        workingHourName = workingHourNameArr[1] ? workingHourNameArr[1] : workingHourNameArr[0];
      }
      return workingHourName;
    }
  },

}
</script>
