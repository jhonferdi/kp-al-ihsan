<template>
  <div>
    <b-card title="Log Device">
      <div class="form-group row">
        <div class="col-sm-12 text-right">
          <!-- <nuxt-link :to="'/device-log/create'">
            <button class="btn btn-success btn-sm"><i class="fas fa-plus" style="color: #fff" /> Tambah Konfigurasi</button>
          </nuxt-link> -->
        </div>
      </div>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Satuan Kerja</label>
        </b-col>
        <b-col sm="4">
          <select
            class="form-control"
            v-model="searchparams.satuan_kerja_id"
            @change="setFilter('satuan_kerja_id', searchparams.satuan_kerja_id)"
            :disabled="isSatuanKerjaDisabled()"
          >
            <option value="">Semua satuan kerja</option>
            <option :value="item.satuan_kerja_id" v-for="(item,id) in satuanKerjaList" :key="id">{{ item.satuan_kerja_nama }}</option>
          </select>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Unit Kerja</label>
        </b-col>
        <b-col sm="4">
          <select
            class="form-control"
            v-model="searchparams.unit_kerja_id"
            @change="setFilter('unit_kerja_id', searchparams.unit_kerja_id)"
            :disabled="isUnitKerjaDisabled()"
          >
            <option value="">Semua unit kerja</option>
            <option :value="item.unit_kerja_id" v-for="(item,id) in unitKerjaList" :key="id">{{ item.unit_kerja_nama }}</option>
          </select>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Nama Pegawai</label>
        </b-col>
        <b-col sm="4">
          <input type="text" class="form-control" v-model="searchparams.search" @change="setFilter('search', searchparams.search)" @keyup="getPegawai"/>
          <div class="select-pegawai" v-if="searchPegawaiShow">
            <div class="select-pegawai-item" v-for="(item,id) in searchPegawaiList" :key="id" @click="selectPegawai(item)">{{ item.peg_nama }}</div>
            <div class="select-pegawai-item" v-if="searchparams.search && searchPegawaiList.length <= 0">Data tidak ditemukan</div>
          </div>
        </b-col>
      </b-row>

      <br>
      
      <b-table-simple responsive>
        <b-thead head-variant="dark">
          <b-tr>
            <b-th>No</b-th>
            <b-th>Satuan Kerja</b-th>
            <b-th>Unit Kerja</b-th>
            <b-th>Nama Pegawai</b-th>
            <b-th>Device UUID</b-th>
            <b-th>Tanggal Daftar</b-th>
            <b-th>Aksi</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <template v-for="(item, i) in datalist">
            <b-tr :key="i">
              <b-td>{{i+1}}</b-td>
              <b-td>{{ item.pegawai ? (item.pegawai.satuan_kerja ? item.pegawai.satuan_kerja.satuan_kerja_nama : '-') : '-' }}</b-td>
              <b-td>{{ item.pegawai ? (item.pegawai.unit_kerja ? item.pegawai.unit_kerja.unit_kerja_nama : '-') : '-' }}</b-td>
              <b-td>{{ item.pegawai ? item.pegawai.peg_nama : '-' }}</b-td>
              <b-td>{{ item.device_uuid }}</b-td>
              <b-td>{{ item.join_date }}</b-td>
              <b-td>
                <nuxt-link :to="'/device-log/update/'+item.id">
                  <b-button variant="primary" size="sm">
                    <b-icon icon="search"></b-icon>
                  </b-button>
                </nuxt-link>
                <b-button variant="danger" size="sm" @click="deleteItem(item, i)">
                  <b-icon icon="trash"></b-icon>
                </b-button>
              </b-td>
            </b-tr>
          </template>
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
    </b-card>
    
</div>

</template>

<style scoped>
.select-pegawai{
  position: absolute;
  top: 30px;
  left: 12px;
  background: #fff;
  width: calc(100% - 24px);
  min-height: 30px;
  max-height: 200px;
  display: block;
  overflow-x: auto;
  border: 1px solid #ccc;
  z-index: 99;
}

.select-pegawai-item{
  padding: 7px 10px;
  cursor: pointer;
}
</style>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { parseParams, stringifyParams } from "@/utils";
import UploadFileCard from '@/components/global/uploadFile'
import DateDropdown from '@/components/global/DateDropdown'
import { mapGetters } from "vuex"

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
    base_url:process.env.apiUrl.substr(0,(process.env.apiUrl.length)-4),
    isloading: false,
    tempid:'',
    tempindex:'',
    satuan_kerja_id: '',
    unitKerjaList: [],
    searchPegawaiShow: false,
    searchPegawaiList: []
  }),
  watchQuery: ["params"],
  async asyncData({ app, params, store }) {
    let searchparams = parseParams(params.params);
    let paginate = 20;
    searchparams.perpage = paginate;
    searchparams.satuan_kerja_id = searchparams.satuan_kerja_id ? searchparams.satuan_kerja_id : '';
    searchparams.unit_kerja_id = searchparams.unit_kerja_id ? searchparams.unit_kerja_id : '';
    let datalist = [];
    let datacount = 0;
    let current = searchparams.page ? parseInt(searchparams.page) : 1;
    
    let satuanKerjaList = [];
    try{
      let f1 = await axios.get("/form-data/satuan-kerja-list");
      satuanKerjaList = f1.data.data;
    } catch (error) {
      console.log(error.response);
    }
    console.log(searchparams);

    return {
      searchparams,
      current,
      datalist,
      datacount,
      paginate,
      paginates: Math.ceil(datacount / paginate),
      satuanKerjaList
    };
  },
  mounted() {
    this.loadData();
    console.log('mounted');
    this.getUnitkerja();

    if(this.user.satuan_kerja_id && this.user.unit_kerja_id){
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams['satuan_kerja_id'] = this.user.satuan_kerja_id;
      searchparams['unit_kerja_id'] = this.user.unit_kerja_id;
      this.$router.replace({
        path: "/device-log/" + stringifyParams(searchparams),
      });
    } else if(this.user.satuan_kerja_id){
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams['satuan_kerja_id'] = this.user.satuan_kerja_id;
      this.$router.replace({
        path: "/device-log/" + stringifyParams(searchparams),
      });
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
  methods: {
    isSatuanKerjaDisabled(){
      if(this.user.satuan_kerja_id){
        return true;
      }
      return false;
    },
    isUnitKerjaDisabled(){
      if(this.user.unit_kerja_id){
        return true;
      }
      return false;
    },
    async getUnitkerja(){
      this.unitKerjaList = [];
      if(this.searchparams.satuan_kerja_id){
        try{
          let f1 = await axios.get("/form-data/unit-kerja-list/" + this.searchparams.satuan_kerja_id);
          let data = f1.data.data;
          console.log(data);
          this.unitKerjaList = data;
        } catch (error) {
          console.log(error.response);
        }
      }
    },
    async getPegawai(){
      if(this.searchparams.search){
        try{
          let f1 = await axios.get("/device-logs/pegawai-list?search=" + this.searchparams.search);
          let data = f1.data.data;
          console.log(data);
          this.searchPegawaiList = data;
          this.searchPegawaiShow = true;
        } catch (error) {
          console.log(error.response);
        }
      }
    },
    selectPegawai(pegawai){
      const self = this;
      setTimeout(function(){
        self.searchparams.search = pegawai.peg_nama;
        self.searchPegawaiShow = false;
        self.setFilter('search', pegawai.peg_nama);
      }, 100);
    },
    async loadData(){
      let searchparams = this.searchparams;
      let paginate = 20;
      searchparams.perpage = paginate

      let datalist = [];
      let datacount = 0;
      let f1 = await axios.get("/device-logs", {
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
      this.datalist = datalist;
      this.datacount = datacount
      this.paginate = paginate;
      this.paginates= Math.ceil(datacount / paginate);
    },
    changePage() {},
    pageLink(page) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams.page = page;
      return { path: "/device-log/" + stringifyParams(searchparams) };
    },
    sortLink(sort) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams.sort = sort;
      delete searchparams.page;
      return { path: "/device-log/" + stringifyParams(searchparams) };
    },
    promptSearch() {
      let search = prompt("Masukkan kata kunci");
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      if (search) {
        searchparams.search = search;
      } else {
        delete searchparams.search;
      }
      this.$router.replace({
        path: "/device-log/" + stringifyParams(searchparams),
      });
    },
    setFilter(col, val) {
      let searchparams = JSON.parse(JSON.stringify(this.searchparams));
      searchparams[col] = val;
      delete searchparams.page;
      this.$router.replace({
        path: "/device-log/" + stringifyParams(searchparams),
      });
    },
    validateForm(){
      if (!this.no_rekomendasi || this.no_rekomendasi == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom No. Rekomendasi belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.perihal_rekomendasi || this.perihal_rekomendasi == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Perihal Rekomendasi belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.tgl_surat_rekomendasi || this.tgl_surat_rekomendasi == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Tanggal Surat Rekomendasi belum diisi',
          timer:5000
        })
        return false
      }
      return true;
    },
    async deleteItem(data, index){
      console.log(data)
      let confirmation
      await Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Menghapus " + data.pegawai.peg_nama + ' - ' + data.device_uuid,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#999',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batalkan'
      }).then((result) => {
        confirmation = result.isConfirmed
      });
      if (confirmation) {
      this.isloading = true;
      Swal.fire({
        title: 'Menghapus Data!',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
      })
      await axios.post("/device-logs/delete", {
            id: data.id,
          })
          .then(({ data }) => {
            // this.$toast.success(data.message, {
            //   icon: "check",
            //   iconPack: "fontawesome",
            //   duration: 5000
            // });
            console.log(data);
            if (data.success) {
              this.isloading = false;
              Swal.close()
              this.loadData();
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data Telah Terhapus',
                confirmButtonText: 'Ok',
                timer: 5000
              })

            } else{
              this.isloading = false;
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
            this.isloading = false;
            Swal.close()
            // this.$swal.fire("Gagal", "Gagal Menyimpan Data !", "error");
            Swal.fire({
              icon: 'warning',
              title: 'Gagal',
              text: 'Gagal Menghapus Data! '+e,
              confirmButtonText: 'Ok'
            })
          });
      }
    },
    async exportpdf(usulan,index){
      console.log(usulan)

      await axios.get("/export/pdf");
    }
  },
  watch: {
    current(newval) {
      this.$router.replace(this.pageLink(newval));
    },
    'searchparams.satuan_kerja_id': {
      handler(val){
        console.log('AAA');
        this.getUnitkerja();
      },
    }
  },
}
</script>
<style scoped>
  .step-done{
      background: #bee690;
  }
  .step-onprogress{
      background: #97b2ee ;
      animation: step-hightlight 1s alternate infinite;
  }
  .step-done h6,
  .step-onprogress h6{
    color: #333 !important;
    }
</style>
